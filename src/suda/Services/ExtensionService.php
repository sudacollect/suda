<?php

namespace Gtd\Suda\Services;

use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use View;
use Artisan;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\FileviewFinder;
use Illuminate\Support\Facades\Config;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use Gtd\Suda\Models\Operate;
use Gtd\Suda\Traits\SettingTrait;

class ExtensionService
{
    use SettingTrait;
    
    public array $extensions = [];
    public array $extension = [];
    public string $ext_slug = '';
    
    protected $extension_paths = null;
    protected $extension_cache = null;
    public $cache_store = '';
    
    public function __construct(){
        
        $this->files = new Filesystem;
        $this->cache_store = config('sudaconf.admin_cache','file');
    }

    // 
    public function use($ext_slug)
    {
        $this->ext_slug = $ext_slug;
        $extensions = Cache::store($this->cache_store)->get('suda_cache_extensions');
        $ext = Arr::get($extensions, $ext_slug);
        if($ext)
        {
            $ext['path'] = extension_path(ucfirst($ext_slug));
            $this->extension = $ext;
            return $this;
        }
        $extensions = Cache::store($this->cache_store)->get('suda_cache_composer_extensions');
        $extensions = new Collection($extensions);
        $ext = $extensions->where('slug',$ext_slug)->first();
        if($ext)
        {
            $ext['path'] = $ext['install-path'];
            $this->extension = $ext;
            return $this;
        }
        return $this;
    }

    public function installedExtensions($reverse = false) {
        
        $extensions = [];
        if(Cache::store($this->cache_store)->has('suda_cache_installed')){
            $extensions = Cache::store($this->cache_store)->get('suda_cache_installed');
        }else{
            $setting_exts = $this->getSettingByKey('suda_installed_extensions','extension');
            if($setting_exts){
                $extensions = $setting_exts;
                Cache::store($this->cache_store)->forever('suda_cache_installed',$extensions);
            }
        }
        return $extensions;
    }
    
    /**
     * setting.disable_role = true
     * not allow to set role permission for this ext
     * @param bool $reverse
     * @return array
     **/
    public function availableRoleExtensions($reverse = false)
    {
        
        $extensions = [];
        $extensions = $this->installedExtensions($reverse);

        foreach($extensions as $k=>$extension)
        {
            if(array_key_exists('setting',$extension) && isset($extension['setting']['disable_role']) && $extension['setting']['disable_role'])
            {
                Arr::forget($extensions,$k);
            }
        }
        return $extensions;
    }
    
    public function localExtensions($force = false): array
    {
       // read from cache
       $extensions = Cache::store($this->cache_store)->get('suda_cache_extensions');
          
       if (!$extensions || $force) {
           $extensions = $this->updateLocalCache();
       }

       $extensions = new Collection($extensions);
       $available_exts = $this->installedExtensions();

       $keys = ($extensions)
            ->keys()
            ->filter(function ($key) use ($available_exts) {
                return !array_key_exists($key, $available_exts);
            })
            ->map(function ($key) {
                return $key;
            })
            ->toArray();
        
        
        return $extensions->whereIn('slug',$keys)->toArray();
    }
    
    // update local cache
    public function updateLocalCache(&$msg='')
    {
        $extension_paths = [];
        if(!$this->files->exists(extension_path())){
            $this->files->makeDirectory(extension_path());
        }
        $extension_paths = $this->files->directories(extension_path());
        
        if(empty($extension_paths)){
            $this->writeCache([]);
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

                        if($ext_config = $this->checkConfig($ext_config))
                        {
                            if(isset($ext_config['setting']) && isset($ext_config['setting']['default_page']))
                            {
                                $ext_config['default_page_url'] = 'extension/'.$ext_config['slug'].'/'.$ext_config['setting']['default_page'];
                            }
                            else
                            {
                                $ext_config['default_page_url'] = 'entry/extension/'.$ext_config['slug'];
                            }

                            if($this->files->exists($ext_dir_path.'/icon.png'))
                            {
                                $ext_config['logo'] = $ext_dir_path.'/icon.png';
                            }else{
                                $ext_config['logo'] = public_path(config('sudaconf.core_assets_path').'/images/empty_extension_icon.png');
                            }

                            $extensions[$ext_config['slug']] = $ext_config;
                        }
                        
                    }
                }else{
                    $msg = $ext_path.' config error.';
                    return false;
                }
            }
        }
        
        $this->writeCache($extensions);
        
        return $extensions;
    }

    // check config format
    protected function checkConfig(array $ext_config)
    {
        $static_keys = ['name','slug','website','author','email','description','version','date','setting'];
        $config = Arr::where($ext_config, function(string|array $value, string $key) use ($static_keys){
            if(in_array($key,$static_keys))
            {
                if($key == 'setting')
                {
                    return is_array($value);
                }else{
                    return is_string($value);
                }
            }
        });
        $contains = Arr::has($config, $static_keys);
        if($contains)
        {
            return $config;
        }
        return false;
    }

    protected function writeCache(array $extensions){
        
        //写配置文件
        Cache::store($this->cache_store)->forever('suda_cache_extensions', $extensions);
        
        //可用应用
        $setting_exts = $this->getSettingByKey('suda_extensions','extension');
        if($setting_exts){
            $this->saveSettingByKey('suda_extensions','extension',$extensions,'serialize');
        }
    }
    
    public function updateExtensionCache($ext_slug,&$msg=''){
        
        return $this->install($ext_slug,true,$msg);
        
    }
    
    //获取当前应用
    public function getExtension($ext_slug)
    {
        $this->ext_slug = $ext_slug;
        $extensions = Cache::store($this->cache_store)->get('suda_cache_extensions');
        $ext = Arr::get($extensions, $ext_slug);
        if($ext)
        {
            $ext['path'] = extension_path(ucfirst($ext_slug));
            $this->extension = $ext;
            return $ext;
        }
        $extensions = Cache::store($this->cache_store)->get('suda_cache_composer_extensions');
        $extensions = new Collection($extensions);
        $ext = $extensions->where('slug',$ext_slug)->first();
        if($ext)
        {
            $ext['path'] = $ext['install-path'];
            $this->extension = $ext;
            return $ext;
        }
        return false;
    }

    // after extension
    public function menu($type,$options)
    {
        $menus = [];
        $menus = $this->getMenu();

        if(array_key_exists('suda',$menus)){
            unset($menus['suda']);
        }
        if(array_key_exists('suda',$menus)){
            unset($menus['suda']);
        }
        
        $dash_menus = [];
        
        $type = 'view_suda::admin.menu.display.'.$type;
        
        $options['extension_slug'] = $this->ext_slug;
        
        $options['menu_style'] = 'normal';
        
        $options = $this->menuOptions($options);
        
        $has_children = false;
        if(property_exists($options,'current_menu'))
        {
            $current_key = array_key_first((array)$options->current_menu);
            if(isset($menus[$current_key]) && isset($menus[$current_key]['children']) && count($menus[$current_key]['children'])>0)
            {
                $has_children = true;
            }
        }

        $data = [
            'items'         => $menus,
            'menu_name'     => $this->ext_slug,
            'return_menus'  => $dash_menus,
            'has_children'  => $has_children,
            'view'          => $type,
            'options'       => $options
        ];

        return $data;
    }

    protected function menuOptions($options)
    {
        
        $options = (object) $options;
        
        //获取当前语言
        if (!isset($options->locale)) {
            $options->locale = app()->getLocale();
        }
        
        //获取侧栏样式设置
        $sidemenu = Cache::store(config('sudaconf.admin_cache','file'))->get('suda_cache_sidebar_style_'.Auth::guard('operate')->user()->id);
        
        if($sidemenu && array_key_exists('style',$sidemenu)){
            $sidemenu_style = $sidemenu['style'];
        }else{
            //默认展开菜单
            $sidemenu_style = 'flat';
        }
        $options->sidemenu_style = $sidemenu_style;
        
        $operate = Auth::guard('operate')->user();
        $options->operate = $operate;
        
        $options->in_extension = true;
        
        return $options;
    }

    // after getExtension
    public function getMenu()
    {
        $menu_path='';
        if($this->extension){
            $menu_path = $this->extension['path'].'/menu.php';
        }
        if(file_exists($menu_path)){
            return require_once $menu_path;
        }
        return [];
    }

    public function menuDisplay($type,$options)
    {
        
        $data = $this->menu($type,$options);

        return new \Illuminate\Support\HtmlString(
            \Illuminate\Support\Facades\View::make($data['view'], $data)->render()
        );
        
    }

    // after getExtension
    public function getAuth()
    {
        $auth_path = '';
        if($this->extension){
            $auth_path = $this->extension['path'].'/auth_setting.php';
        }

        if(file_exists($auth_path)){
            return require_once $auth_path;
        }
        return [];
    }
    
    //启用应用
    public function install($ext_slug,$force=false,&$msg)
    {
        
        $ext = false;
        if($ext = $this->getExtension($ext_slug)){
            
            $available_exts = $this->installedExtensions();
            
            if(array_key_exists($ext_slug,$available_exts) && !$force){
                $msg = __('suda_lang::press.extensions.install_again');
                return true;
            }

            // #TODO check depencies
            
            $available_exts[$ext['slug']] = $ext;

            //save installed
            $this->saveSettingByKey('suda_installed_extensions','extension',$available_exts,'serialize');
            
            Cache::store($this->cache_store)->forever('suda_cache_installed',$available_exts);

            $this->addMenuCache($ext_slug, $ext['path']);
            $this->addNaviCache($ext_slug, $ext['path']);
            $this->runPublish($ext_slug, $ext['path']);
            $this->runMigrate($ext['path']);
            
            //#TODO 支持设置预定义数据
            
            $msg = __('suda_lang::press.extensions.install_ok');
            return true;
        }
        
        $msg = __('suda_lang::press.extensions.can_not_get_ext');
        return false;
    }
    
    //获取应用导航
    public function getNavis($operate=false)
    {
        $navis = Cache::store($this->cache_store)->get('extension_navi',[]);
        if($operate)
        {
            if(!\Gtd\Suda\Auth\OperateCan::superadmin($operate))
            {
                if(isset($operate->role->permissions['exts']))
                {
                    $keys = array_flip(array_keys($operate->role->permissions['exts']));
                    $navis = array_intersect_key($navis,$keys);
                    return $navis;
                }   
                return [];
            }
        }
        return $navis;
    }   

    //更新菜单
    public function addMenuCache($ext_slug, $ext_path){
        
        //菜单更新
        $ext_menu = [];
        if($this->files->exists($ext_path.'/menu.php')){
            $ext_menu = require_once($ext_path.'/menu.php');
        }
        Cache::store($this->cache_store)->forever('suda_cache_ext.'.$ext_slug.'menu',$ext_menu);
        
    }

    public function removeMenuCache($ext_slug){
        
        if(Cache::store($this->cache_store)->has('suda_cache_ext.'.$ext_slug.'menu')){
            Cache::store($this->cache_store)->forget('suda_cache_ext.'.$ext_slug.'menu');
        }
    }
    
    //更新导航缓存
    public function addNaviCache($ext_slug, $ext_path)
    {
        //菜单更新
        $ext_navi = [];
        
        if($this->files->exists($ext_path.'/custom_navi.php')){
            $ext_navi = $this->files->requireOnce($ext_path.'/custom_navi.php');
        }
        
        if(is_array($ext_navi) && !empty($ext_navi))
        {
            $filtered = Arr::where($ext_navi, function (array|string $value, int|string $key) {
                if(is_array($value) && count($value) > 1)
                {
                    $value = Arr::where($value, function (string $v, string $k) {
                        if(in_array($k,['name','url','target','icon','blade_icon','children']) && !empty($v))
                        {
                            return $v;
                        }
                    });
                    $contains = Arr::has($value, ['name','url','target']);
                    return $contains;
                }
            });

            // $filtered = Arr::map(array_values($filtered), function (array $value, int $key) {
            //     $new_value = array_merge([
            //         'name'  => '',
            //         'url'   => '',
            //         'target' => '_self',
            //         'icon'  => 'ion-home',
            //         'children' => [],
            //     ], $value);
            //     return $new_value;
            // });

            $filtered = array_values($filtered);

            $navis = $this->getNavis();
            $navis[$ext_slug] = $filtered;
            Cache::store($this->cache_store)->forever('extension_navi',$navis);
        }
        
    }
    
    public function removeNaviCache($ext_slug)
    {
        $navis = $this->getNavis();
        if(isset($navis[$ext_slug]))
        {
            Arr::forget($navis,$ext_slug);
        }
        Cache::store($this->cache_store)->forever('extension_navi',$navis);
    }

    //更新数据表
    protected function runMigrate($ext_path)
    {

        $filesystem = $this->files;
        
        $from_dir = $ext_path.'/publish/database/migrations';
        $from_path = Str::replace(base_path(),'',$from_dir);

        $file_list = [];
        $sub_directories = [];
        if($filesystem->exists($from_dir)){
            $file_list = $filesystem->files($from_dir);
            $sub_directories = $filesystem->directories($from_dir);
        }
        
        if($file_list)
        {
            Artisan::call('migrate --force --path='.$from_path);
        }

        if($sub_directories)
        {
            foreach($sub_directories as $sub_path)
            {
                Artisan::call('migrate --force --path='.$sub_path);
            }            
        }
    }

    protected function runMigrateBack($ext_path)
    {
        
        $filesystem = $this->files;
        
        $from_dir = $ext_path.'/publish/database/migrations';
        $from_path = Str::replace(base_path(),'',$from_dir);

        $file_list = [];
        $sub_directories = [];
        if($filesystem->exists($from_dir)){
            $file_list = $filesystem->files($from_dir);
            $sub_directories = $filesystem->directories($from_dir);
        }
        
        if($file_list)
        {
            Artisan::call('migrate:rollback --force --path='.$from_path);
        }

        if($sub_directories)
        {
            foreach($sub_directories as $sub_path)
            {
                Artisan::call('migrate:rollback --force --path='.$sub_path);
            }            
        }
    }
    
    //更新静态资源
    protected function runPublish($ext_slug, $ext_path)
    {
        $filesystem = $this->files;

        $extension_dir = config('sudaconf.extension_dir','extensions');
        $ucf_extension_dir = ucfirst($extension_dir);
        
        if(!$filesystem->exists(public_path($extension_dir))){
            $filesystem->makeDirectory(public_path($extension_dir));
        }
        
        $dest_folder = public_path($extension_dir.'/'.strtolower($ext_slug));
        if(!$filesystem->exists($dest_folder)){
            $filesystem->makeDirectory($dest_folder);
        }
        
        // copy assets
        if($filesystem->exists($ext_path.'/publish/assets')){
            $filesystem->deleteDirectory($dest_folder.'/assets');
            $filesystem->copyDirectory($ext_path.'/publish/assets',$dest_folder.'/assets');
        }
        // copy logo
        if($filesystem->exists($ext_path.'/icon.png')){
            $filesystem->delete($dest_folder.'/icon.png');
            $filesystem->copy($ext_path.'/icon.png',$dest_folder.'/icon.png');
        }
    }

    protected function removePublish($ext_slug)
    {
        $filesystem = $this->files;
        $extension_dir = config('sudaconf.extension_dir','extensions');
        $ucf_extension_dir = ucfirst($extension_dir);
        
        $dest_folder = public_path($extension_dir.'/'.strtolower($ext_slug));
        if($filesystem->exists($dest_folder)){
            $filesystem->deleteDirectory($dest_folder);
        }
    }
    
    //禁用应用
    public function uninstall($ext_slug, $drop_table = false, &$msg=''){
        $ext = false;
        $available_exts = $this->installedExtensions();

        if(!array_key_exists($ext_slug,$available_exts)){
            $msg = __('suda_lang::press.extensions.not_installed');
            return false;
        }

        // #TODO check depencies
        // 如果应用被依赖，则不能卸载

        unset($available_exts[$ext_slug]);
            
        $this->saveSettingByKey('suda_installed_extensions','extension',$available_exts,'serialize');
        
        Cache::store($this->cache_store)->forever('suda_cache_installed',$available_exts);

        $ext = $this->getExtension($ext_slug);

        $this->removeMenuCache($ext_slug);
        $this->removeNaviCache($ext_slug);
        $this->removePublish($ext_slug);
        $this->setQuickin($ext_slug,0);

        if($drop_table && $ext)
        {
            $this->runMigrateBack($ext['path']);
        }
        
        return true;
    }

    //设置应用的quickin
    public function setQuickin($ext_slug,$status=0,&$msg='')
    {
        
        $quickins = [];
        if(Cache::store($this->cache_store)->has('suda_dash_extensions')){
            $quickins = Cache::store($this->cache_store)->get('suda_dash_extensions');
        }
        
        if(in_array($ext_slug,$quickins) && $status==0){
            $quickins = array_diff($quickins,[$ext_slug]);
            Cache::store($this->cache_store)->forever('suda_dash_extensions',$quickins);
            return true;
        }
        if(!in_array($ext_slug,$quickins) && $status==1){
            array_push($quickins,$ext_slug);
            Cache::store($this->cache_store)->forever('suda_dash_extensions',$quickins);
            return true;
        }
        return true;
    }

    //获取quickin应用
    public function getQuickins()
    {
        
        $quickins = [];

        if(Cache::store($this->cache_store)->has('suda_dash_extensions')){
            $quickins = Cache::store($this->cache_store)->get('suda_dash_extensions');
        }

        $extensions = $this->installedExtensions();

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
        
        $extensions = $this->installedExtensions();
        $quickins = $this->getQuickins();
        $navis = $this->getNavis();

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
            if(isset($navis[$slug]))
            {
                $new_navis[$slug] = $navis[$slug];
            }
        }

        //重新排序后更新缓存
        Cache::store($this->cache_store)->forever('suda_cache_installed',$new_orders);

        Cache::store($this->cache_store)->forever('suda_dash_extensions',$new_quickins);

        Cache::store($this->cache_store)->forever('extension_navi',$new_navis);
        
        return true;
    }
    
    public function composerExtensions()
    {
        
        // read from cache
        $extensions = Cache::store($this->cache_store)->get('suda_cache_composer_extensions');
          
        if (!$extensions) {
           $extensions = $this->updateComposerCache();
        }
        if(count($extensions) > 0)
        {
            $extensions = new Collection($extensions);
            $available_exts = $this->installedExtensions();
            
            $extensions = $extensions->filter(function ($item,$key) use ($available_exts) {
                    return !array_key_exists($item['slug'], $available_exts);
                })
                ->toArray();
        }
       
        return $extensions;
        // return $extensions->whereIn('type-name',$keys)->toArray();
    }

    // composer ext
    public function updateComposerCache(&$msg=''){
        $vendor_path = base_path('vendor');
        if ($this->files->exists($vendor_path.'/composer/installed.json')) {
            $extensions = new Collection();

            // Load all packages installed by composer.
            $installed = json_decode($this->files->get($vendor_path.'/composer/installed.json'), true);

            // Composer 2.0 changes the structure of the installed.json manifest
            $installed = $installed['packages'] ?? $installed;

            $installedSet = [];

            $composerJsonConfs = [];

            foreach ($installed as $package) {
                $name = Arr::get($package, 'name');
                if (empty($name)) {
                    continue;
                }

                if (Arr::get($package, 'type') === 'suda-extension') {
                    $packagePath = isset($package['install-path'])
                        ? $vendor_path.'/composer/'.$package['install-path']
                        : $vendor_path.'/'.$name;
                    
                    if($this->files->exists($packagePath.'/config.php'))
                    {
                        $ext_config = $this->files->requireOnce($packagePath.'/config.php');
                        if($ext_config = $this->checkConfig($ext_config))
                        {
                            if($this->files->exists($packagePath.'/icon.png'))
                            {
                                $ext_config['logo'] = $packagePath.'/icon.png';
                            }else{
                                $ext_config['logo'] = public_path(config('sudaconf.core_assets_path').'/images/empty_extension_icon.png');
                            }
                            $package['config'] = $ext_config;
                            $composerJsonConfs[$packagePath] = $package;
                        }
                    }
                    
                }
            }

            foreach ($composerJsonConfs as $path => $package) {
                $installedSet[Arr::get($package, 'name')] = true;
                $config = Arr::get($package, 'config');
                $config['type'] = $package['type'];
                $config['install-path'] = $path;
                $config['type-name'] = $package['name'];
                $config['license'] = $package['license'];

                if(isset($config['setting']) && isset($config['setting']['default_page']))
                {
                    $config['default_page_url'] = 'extension/'.$config['slug'].'/'.$config['setting']['default_page'];
                }
                else
                {
                    $config['default_page_url'] = 'entry/extension/'.$config['slug'];
                }

                $extensions->put(Arr::get($package, 'name'), $config);
            }
            
            // $extensions = $this->detectComposerInstalled($extensions);
            $this->writeComposerCache($extensions->toArray());
            
            return $extensions->toArray();
        }
        return [];

    }

    protected function detectComposerInstalled(Collection $exts): Collection
    {
        if($exts->count()<1)
        {
            return $exts;
        }
        $available_exts = $this->installedExtensions();
        $keys = ($exts)
            ->keys()
            ->filter(function ($key) use ($available_exts) {
                return !array_key_exists($key, $available_exts);
            })
            ->map(function ($key) {
                return $key;
            })
            ->toArray();
        
        return $exts->whereIn('type-name',$keys);
    }

    protected function writeComposerCache(array $extensions)
    {
        
        // cache
        Cache::store($this->cache_store)->forever('suda_cache_composer_extensions', $extensions);
        
        // setting
        $setting_exts = $this->getSettingByKey('suda_composer_extensions','extension');
        if($setting_exts){
            $this->saveSettingByKey('suda_composer_extensions','extension',$extensions,'serialize');
        }
        
    }
}
