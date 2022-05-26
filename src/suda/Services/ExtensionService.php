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
          
          //读取所有的应用
          $extensions = Cache::store($this->cache_store)->get('cache_extension');
          
          if($extensions && !$force){
              $this->extension_cache = $extensions;
          }else{
              $this->updateCache();
          }
          
       }
       return $this->extension_cache;
       
    }
    
    //更新应用缓存
    public function updateCache(&$msg=''){
        $this->extension_cache = [];
        
        //所有应用
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
                            // $msg = $ext_config['name'].'出错.';
                            // return false;
                        }

                        
                    }
                }else{
                    $msg = $ext_path.'应用不完整.';
                    return false;
                }
            }
        }
        
        $this->extension_cache = $extensions;
        
        $this->writeExtensionCache($extensions);
        
    }
    
    protected function writeExtensionCache($extensions){
        
        //写配置文件
        Cache::store($this->cache_store)->forever('cache_extension', $extensions);
        
        //可用应用
        
        $setting_exts = Setting::where(['key'=>'extensions','group'=>'extension'])->first();
        if($setting_exts){
            
            //$extensions = unserialize($setting_exts->values);
            
            Setting::where(['key'=>'extensions','group'=>'extension'])->update([
                'values' => serialize($extensions)
            ]);

        }

        if($extensions)
        {
            // 对比删除的应用
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
    
    //获取当前应用
    public function getExtension($extenion_slug,$force=false){

        if($force)
        {
            //直接读取配置
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
                        $msg = $extenion_slug.'配置key错误.';
                        return false;
                    }
                }
            }else{
                $msg = $extenion_slug.'应用不完整.';
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
    
    //获取当前应用
    public function getExtensionMenu(){
        
        if(Cache::store($this->cache_store)->has('cache_extension_menu')){
            $extension_menus = Cache::store($this->cache_store)->get('cache_extension_menu');
            return $extension_menus;
        }
        return false;
    }
    
    //更新菜单
    public function addMenuCache($ext_slug){
        
        //菜单更新
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

    //获取应用导航
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

    //更新导航缓存
    public function addNaviCache($ext_slug)
    {
        
        //菜单更新
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
    
    //启用应用
    public function enableExtension($extenion_slug,$force=false,&$msg){
        
        $ext = false;
        if($ext = $this->getExtension($extenion_slug,true)){
            
            $available_exts = $this->availableExtensions();
            
            if(array_key_exists($extenion_slug,$available_exts) && !$force){
                $msg = '应用已经安装启用';
                return true;
            }
            
            //应用key检查
            if(!$this->checkExtKey($ext)){
                $msg = $ext['name'].'配置信息出错，请修改后重新安装.';
                return false;
            }
            
            $available_exts[$ext['slug']] = $ext;
            
            //保存可用应用
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
            
            
            //发布静态资源
            $this->runPublish($extenion_slug);
                        
            //安装数据表
            $this->runMigrate($extenion_slug);
            
            //#TODO 支持设置预定义数据
            
            
            
            $msg = '应用安装成功';
            return true;
        }
        
        $msg = '无法获取应用信息，请先刷新应用列表';
        return false;
        
    }
    
    
    //更新数据表
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
        
        // //更新基础目录,删除并重建
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
    
    //更新静态资源
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
        
        //安装静态资源
        if($filesystem->exists(app_path($ucf_extension_dir.'/'.ucfirst($extension_slug).'/publish/assets'))){
            $filesystem->copyDirectory(app_path($ucf_extension_dir.'/'.ucfirst($extension_slug).'/publish/assets'),$dest_folder.'/assets');
        }
        
        
    }
    
    //禁用应用
    public function disableExtension($extension_slug,&$msg){
        $ext = false;
        if($ext = $this->getExtension($extension_slug)){
            
            $available_exts = $this->availableExtensions();
            
            if(!array_key_exists($extension_slug,$available_exts)){
                $msg = '应用没有正确安装';
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
        
        $msg = '应用信息不存在';
        return false;
    }

    //设置应用的quickin
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


    //获取quickin应用
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


    //重新排序
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

        //重新排序后更新缓存
        Cache::store($this->cache_store)->forever('cache_avaliable_extensions',$new_orders);

        Cache::store($this->cache_store)->forever('dash_extension_quickin',$new_quickins);

        Cache::store($this->cache_store)->forever('extension_navi',$new_navis);
        
        return true;
    }
    
    //应用Logo
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
    
    //应用extkey本地验证
    private function checkExtKey($ext_config=[]){
        
        

        return true;

    }
    
}
