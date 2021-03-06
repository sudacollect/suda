<?php

namespace Gtd\Suda\Services;

use View;
use Artisan;
use Illuminate\Support\Arr;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\FileviewFinder;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use Illuminate\Routing\Router;
use Gtd\Suda\Models\Setting;
use Gtd\Suda\Models\Operate;

class ExtensionService {
    
    public $extensions = [];
    
    protected $extension_paths = null;
    protected $extension_cache = null;
    public $cache_store = '';
    
    public function __construct(){
        
        $this->files = new Filesystem;
        $this->cache_store = config('sudaconf.admin_cache','file');
    }
    
    public function availableExtensions($reverse = false) {
        
        $extensions = [];
        if(Cache::store($this->cache_store)->has('cache_avaliable_extensions')){
            $extensions = Cache::store($this->cache_store)->get('cache_avaliable_extensions');
        }else{
            $setting_exts = Setting::where(['key'=>'extensions','group'=>'extension'])->first();
            if($setting_exts){
                
                $extensions = unserialize($setting_exts->values);
                Cache::store($this->cache_store)->forever('cache_avaliable_extensions',$extensions);
            }
        }
        
        return $extensions;
        
    }

    public function availableRoleExtensions($reverse = false)
    {
        
        $extensions = [];
        $extensions = $this->availableExtensions($reverse);

        foreach($extensions as $k=>$extension)
        {
            if(array_key_exists('setting',$extension) && isset($extension['setting']['disable_role']) && $extension['setting']['disable_role'])
            {
                Arr::forget($extensions,$k);
            }
        }
        
        return $extensions;
        
    }
    
    public function allExtensions($force = false) {
       
       if (is_null($this->extension_cache) || $force) {
           
          $this->extension_cache = [];
          
          //?????????????????????
          $extensions = Cache::store($this->cache_store)->get('cache_extension');
          
          if($extensions && !$force){
              $this->extension_cache = $extensions;
          }else{
              $this->updateCache();
          }
          
       }
       return $this->extension_cache;
       
    }
    
    //??????????????????
    public function updateCache(&$msg=''){
        $this->extension_cache = [];
        
        //????????????
        $extension_paths = [];
        if(!$this->files->exists(extension_path())){
            $this->files->makeDirectory(extension_path());
        }
        $extension_paths = $this->files->directories(extension_path());
        
        
        if(empty($extension_paths)){
            $this->writeExtensionCache([]);
            return;
        }
        
        $extensions = [];
        
        if(!empty($extension_paths)){
            
            foreach($extension_paths as $ext_path){
                $ext_path = basename($ext_path);
                
                $ext_dir_path = extension_path($ext_path);
                
                if($this->files->exists($ext_dir_path.'/config.php')){
                    
                    $ext_config = require_once($ext_dir_path.'/config.php');
                    
                    if($ext_config){
                        
                        if($this->checkExtKey($ext_config)){

                            if(isset($ext_config['setting']) && isset($ext_config['setting']['default_page']))
                            {
                                $ext_config['default_page_url'] = 'extension/'.$ext_config['slug'].'/'.$ext_config['setting']['default_page'];
                            }
                            else
                            {
                                $ext_config['default_page_url'] = 'entry/extension/'.$ext_config['slug'];
                            }

                            $extensions[$ext_config['slug']] = $ext_config;
                        }else{
                            // $msg = $ext_config['name'].'??????.';
                            // return false;
                        }

                        
                    }
                }else{
                    $msg = $ext_path.'???????????????.';
                    return false;
                }
            }
        }
        
        $this->extension_cache = $extensions;
        
        $this->writeExtensionCache($extensions);
        
    }
    
    protected function writeExtensionCache($extensions){
        
        //???????????????
        Cache::store($this->cache_store)->forever('cache_extension', $extensions);
        
        //????????????
        
        $setting_exts = Setting::where(['key'=>'extensions','group'=>'extension'])->first();
        if($setting_exts){
            
            //$extensions = unserialize($setting_exts->values);
            
            Setting::where(['key'=>'extensions','group'=>'extension'])->update([
                'values' => serialize($extensions)
            ]);

        }

        if($extensions)
        {
            // ?????????????????????
            if(Cache::store(config('sudaconf.admin_cache','file'))->has('cache_avaliable_extensions')){
                $avaliable_extensions = Cache::store(config('sudaconf.admin_cache','file'))->get('cache_avaliable_extensions');
                $need_removes = array_diff_key($avaliable_extensions,$extensions);
                $avaliable_news = array_diff_key($avaliable_extensions,$need_removes);
                Cache::store(config('sudaconf.admin_cache','file'))->forever('cache_avaliable_extensions',$avaliable_news);
            }
        }else{
            Cache::store(config('sudaconf.admin_cache','file'))->forever('cache_avaliable_extensions',[]);
        }
        
    }
    
    public function updateExtensionCache($slug,&$msg=''){
        
        return $this->enableExtension($slug,true,$msg);
        
    }
    
    //??????????????????
    public function getExtension($extenion_slug,$force=false){

        if($force)
        {
            //??????????????????
            $ext_dir_path = extension_path(ucfirst($extenion_slug));
            
            
            if($this->files->exists($ext_dir_path.'/config.php')){
                
                $ext_config = require_once($ext_dir_path.'/config.php');
                
                if($ext_config){
                    
                    if($this->checkExtKey($ext_config)){
                        
                        if(isset($ext_config['setting']) && isset($ext_config['setting']['default_page']))
                        {
                            $ext_config['default_page_url'] = 'extension/'.$ext_config['slug'].'/'.$ext_config['setting']['default_page'];
                        }
                        else
                        {
                            $ext_config['default_page_url'] = 'entry/extension/'.$ext_config['slug'];
                        }

                        return $ext_config;
                    }else{
                        $msg = $extenion_slug.'??????key??????.';
                        return false;
                    }
                }
            }else{
                $msg = $extenion_slug.'???????????????.';
                return false;
            }

        }else{

            if(Cache::store($this->cache_store)->has('cache_extension')){
                $extensions = Cache::store($this->cache_store)->get('cache_extension');
                if(array_key_exists($extenion_slug,$extensions)){
                    return $extensions[$extenion_slug];
                }
            }

        }
        
    }
    
    //??????????????????
    public function getExtensionMenu(){
        
        if(Cache::store($this->cache_store)->has('cache_extension_menu')){
            $extension_menus = Cache::store($this->cache_store)->get('cache_extension_menu');
            return $extension_menus;
        }
        return false;
    }
    
    //????????????
    public function addMenuCache($ext_slug){
        
        //????????????
        $ext_menu = [];
        $ext_dir_path = extension_path(ucfirst($ext_slug));
        if($this->files->exists($ext_dir_path.'/menu.php')){
            $ext_menu = require_once($ext_dir_path.'/menu.php');
        }
        
        // $extension_menus = [];
        // if(Cache::store($this->cache_store)->has('extension.'.$ext_slug.'.menu_file')){
        //     $extension_menus = Cache::store($this->cache_store)->get('extension.'.$ext_slug.'.menu_file');
        // }
        
        // $extension_menus[$ext_slug] = $ext_menu;
        Cache::store($this->cache_store)->forever('extension.'.$ext_slug.'.menu_file',$ext_menu);
        
    }

    public function removeMenuCache($ext_slug){
        
        if(Cache::store($this->cache_store)->has('extension.'.$ext_slug.'.menu_file')){
            Cache::store($this->cache_store)->forget('extension.'.$ext_slug.'.menu_file');
        }
    }

    //??????????????????
    public function getExtensionNavi($operate=false)
    {
        $extension_navis = Cache::store($this->cache_store)->get('extension_navi',[]);
        if($operate)
        {
            if($operate->user_role < 6)
            {
                //suda-exts
                if(isset($operate->role->permissions['exts']))
                {
                    $keys = array_flip(array_keys($operate->role->permissions['exts']));
                    $navis = array_intersect_key($extension_navis,$keys);
                    return $navis;
                }
                
                return [];
            }
        }
        return $extension_navis;
    }   

    //??????????????????
    public function addNaviCache($ext_slug)
    {
        
        //????????????
        $ext_navi = [];
        $ext_dir_path = extension_path(ucfirst($ext_slug));
        if($this->files->exists($ext_dir_path.'/custom_navi.php')){
            $ext_navi = require_once($ext_dir_path.'/custom_navi.php');
        }
        
        $extension_navi = $this->getExtensionNavi();
        $extension_navi[$ext_slug] = $ext_navi;

        Cache::store($this->cache_store)->forever('extension_navi',$extension_navi);
        
    }
    
    public function removeNaviCache($ext_slug)
    {
        $extension_navi = $this->getExtensionNavi();
        if(isset($extension_navi[$ext_slug]))
        {
            Arr::forget($extension_navi,$ext_slug);
        }
        Cache::store($this->cache_store)->forever('extension_navi',$extension_navi);
    }
    
    //????????????
    public function enableExtension($extenion_slug,$force=false,&$msg){
        
        $ext = false;
        if($ext = $this->getExtension($extenion_slug,true)){
            
            $available_exts = $this->availableExtensions();
            
            if(array_key_exists($extenion_slug,$available_exts) && !$force){
                $msg = '????????????????????????';
                return true;
            }
            
            //??????key??????
            if(!$this->checkExtKey($ext)){
                $msg = $ext['name'].'?????????????????????????????????????????????.';
                return false;
            }
            
            $available_exts[$ext['slug']] = $ext;
            
            //??????????????????
            $setting_exts = Setting::where(['key'=>'extensions','group'=>'extension'])->first();
            if($setting_exts){
                Setting::where(['key'=>'extensions','group'=>'extension'])->update([
                    'values' => serialize($available_exts)
                ]);
            }else{
                $settingModel = new Setting;
                $settingModel->fill([
                    
                    'key'=>'extensions',
                    'group'=>'extension',
                    'values'=>serialize($available_exts),
                    'type'=>'text'
                    
                ])->save();
            }
            
            Cache::store($this->cache_store)->forever('cache_avaliable_extensions',$available_exts);
            
            $this->addMenuCache($extenion_slug);

            $this->addNaviCache($extenion_slug);
            
            
            //??????????????????
            $this->runPublish($extenion_slug);
                        
            //???????????????
            $this->runMigrate($extenion_slug);
            
            //#TODO ???????????????????????????
            
            
            
            $msg = '??????????????????';
            return true;
        }
        
        $msg = '???????????????????????????????????????????????????';
        return false;
        
    }
    
    
    //???????????????
    protected function runMigrate($extension_slug)
    {

        $extension_dir = config('sudaconf.extension_dir','extensions');
        $ucf_extension_dir = ucfirst($extension_dir);

        
        $filesystem = new Filesystem;
        
        $from_path = app_path($ucf_extension_dir.'/'.ucfirst($extension_slug).'/publish/database/migrations');

        // $to_dir_path = 'database/migrations/'.$extension_dir.'/'.ucfirst($extension_slug);
        // $to_path = base_path($to_dir_path);
        
        $file_list = [];
        $sub_directories = [];
        if($filesystem->exists($from_path)){
            $file_list = $filesystem->files($from_path);
            $sub_directories = $filesystem->directories($from_path);
        }
        
        // //??????????????????,???????????????
        // if($filesystem->exists($to_path)){
        //     $filesystem->deleteDirectory($to_path);
        // }

        // $filesystem->makeDirectory($to_path);
        // $filesystem->copyDirectory($from_path,$to_path);
        
        
        if($file_list){
            Artisan::call('migrate --force --path=app/'.$ucf_extension_dir.'/'.ucfirst($extension_slug).'/publish/database/migrations');
        }

        

        if($sub_directories)
        {
            foreach($sub_directories as $sub_path)
            {
                Artisan::call('migrate --force --path=app/'.$ucf_extension_dir.'/'.ucfirst($extension_slug).'/publish/database/migrations'.'/'.pathinfo($sub_path, PATHINFO_BASENAME));
            }
            
        }


        
    }
    
    //??????????????????
    protected function runPublish($extension_slug){
        
        $filesystem = new Filesystem;

        $extension_dir = config('sudaconf.extension_dir','extensions');
        $ucf_extension_dir = ucfirst($extension_dir);
        
        if(!$filesystem->exists(public_path($extension_dir))){
            $filesystem->makeDirectory(public_path($extension_dir));
        }
        
        $dest_folder = public_path($extension_dir.'/'.strtolower($extension_slug));
        if(!$filesystem->exists($dest_folder)){
            $filesystem->makeDirectory($dest_folder);
        }
        
        //??????????????????
        if($filesystem->exists(app_path($ucf_extension_dir.'/'.ucfirst($extension_slug).'/publish/assets'))){
            //??????????????????????????????
            $filesystem->deleteDirectory($dest_folder.'/assets');
            $filesystem->copyDirectory(app_path($ucf_extension_dir.'/'.ucfirst($extension_slug).'/publish/assets'),$dest_folder.'/assets');
        }
        
        
    }
    
    //????????????
    public function disableExtension($extension_slug,&$msg){
        $ext = false;
        if($ext = $this->getExtension($extension_slug)){
            
            $available_exts = $this->availableExtensions();
            
            if(!array_key_exists($extension_slug,$available_exts)){
                $msg = '????????????????????????';
                return false;
            }
            
            unset($available_exts[$extension_slug]);
            
            $setting_exts = Setting::where(['key'=>'extensions','group'=>'extension'])->first();
            
            if($setting_exts){
                Setting::where(['key'=>'extensions','group'=>'extension'])->update([
                    'values' => serialize($available_exts)
                ]);
            }else{
                $settingModel = new Setting;
                $settingModel->fill([
                    
                    'key'=>'extensions',
                    'group'=>'extension',
                    'values'=>serialize($available_exts),
                    'type'=>'text'
                    
                ])->save();
            }
            
            Cache::store($this->cache_store)->forever('cache_avaliable_extensions',$available_exts);
            $this->removeMenuCache($extension_slug);
            $this->removeNaviCache($extension_slug);
            
            return true;
        }
        
        $msg = '?????????????????????';
        return false;
    }

    //???????????????quickin
    public function setQuickin($slug,$status=0,&$msg='')
    {
        
        $quickins = [];
        if(Cache::store($this->cache_store)->has('dash_extension_quickin')){
            $quickins = Cache::store($this->cache_store)->get('dash_extension_quickin');
        }
        
        if(in_array($slug,$quickins) && $status==0){
            $quickins = array_diff($quickins,[$slug]);
            Cache::store($this->cache_store)->forever('dash_extension_quickin',$quickins);
            return true;
        }
        if(!in_array($slug,$quickins) && $status==1){
            array_push($quickins,$slug);
            Cache::store($this->cache_store)->forever('dash_extension_quickin',$quickins);
            return true;
        }
        return true;
    }


    //??????quickin??????
    public function getQuickins()
    {
        
        $quickins = [];

        if(Cache::store($this->cache_store)->has('dash_extension_quickin')){
            $quickins = Cache::store($this->cache_store)->get('dash_extension_quickin');
        }

        $extensions = $this->availableExtensions();

        $quickinss = [];
        if($quickins)
        {
            foreach($quickins as $slug){
                if(array_key_exists($slug,$extensions)){
                    $quickinss[$slug] = $extensions[$slug];
                }
            }
        }
        

        return $quickinss;

    }


    //????????????
    public function resort($order,&$msg='')
    {
        
        $extensions = $this->availableExtensions();
        $quickins = $this->getQuickins();
        $extension_navi = $this->getExtensionNavi();

        $new_orders = [];
        $new_quickins = [];
        $new_navis = [];
        
        foreach($order as $slug)
        {
            if(isset($extensions[$slug]))
            {
                $new_orders[$slug] = $extensions[$slug];
            }
            if(isset($quickins[$slug]))
            {
                $new_quickins[] = $slug;
            }
            if(isset($extension_navi[$slug]))
            {
                $new_navis[$slug] = $extension_navi[$slug];
            }
        }

        //???????????????????????????
        Cache::store($this->cache_store)->forever('cache_avaliable_extensions',$new_orders);

        Cache::store($this->cache_store)->forever('dash_extension_quickin',$new_quickins);

        Cache::store($this->cache_store)->forever('extension_navi',$new_navis);
        
        return true;
    }
    
    //??????Logo
    public function getIcon($ext_slug){

        $extension_dir = config('sudaconf.extension_dir','extensions');
        $ucf_extension_dir = ucfirst($extension_dir);
        
        $files = new Filesystem;
        $path = app_path($ucf_extension_dir.'/' . ucfirst($ext_slug) . '/icon.png');
        
        if (!$files->exists($path)) {
            $path = app_path($ucf_extension_dir.'/' . ucfirst($ext_slug) . '/icon.jpg');
            if (!$files->exists($path)) {
                return false;
            }
        }

        $file = $files->get($path);
        $type = $files->mimeType($path);
        
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
        
    }
    
    //??????extkey????????????
    private function checkExtKey($ext_config=[]){
        
        

        return true;

    }
    
}
