<?php
 
namespace Gtd\Suda;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;

use Arrilot\Widgets\Facade as Widget;
use Arrilot\Widgets\AsyncFacade as AsyncWidget;

class Sudacore
{
    protected static $name = 'Suda';
    protected static $version = '5.1.1';//2021-05-01
    protected static $author = 'SUDA';
    protected static $email = 'hello@suda.gtd.xyz';
    private static $instance;
    private static $extend_admin_routes = [];
    private static $extend_web_routes = [];
    private static $extend_mobile_routes = [];
    private static $extend_api_routes = [];
    
    public static function getInstance()
    {
        if ( is_null( self::$instance ) )
        {
          self::$instance = new self();
        }
        return self::$instance;
    }
    
    public static function routes()
    {
        require suda_path('routes/suda_route.php');
    }
    
    public static function erLog($data){
        error_log(var_export($data,true),3,__FILE__.'location.log');
    }
    
    
    
    public static function getExtendAdminRoutes(){
        
        if(count(self::$extend_admin_routes)<1){
            self::extendRoutes();
        }
        
        foreach(self::$extend_admin_routes as $route){
            
            require($route);
    
        }
        
    }
    
    public static function getExtendWebRoutes(){
        
        if(count(self::$extend_web_routes)<1){
            self::extendRoutes();
        }
        
        foreach(self::$extend_web_routes as $route){
    
            require($route);
    
        }
        
    }
    
    public static function getExtendMobileRoutes(){
        
        if(count(self::$extend_mobile_routes)<1){
            self::extendRoutes();
        }
        
        foreach(self::$extend_mobile_routes as $route){
    
            require($route);
    
        }
        
    }
    
    public static function getExtendApiRoutes(){
        
        if(count(self::$extend_api_routes)<1){
            self::extendRoutes();
        }
        
        foreach(self::$extend_api_routes as $route){
    
            require($route);
    
        }
        
    }
    
    //获取应用路由信息
    private static function extendRoutes(){

        $extension_dir = config('sudaconf.extension_dir','extensions');
        $ucf_extension_dir = ucfirst($extension_dir);

        $extend_path = $ucf_extension_dir;
        
        $files = new Filesystem;
        if(Cache::store(config('sudaconf.admin_cache','file'))->has('cache_avaliable_extensions')){
            $extensions = Cache::store(config('sudaconf.admin_cache','file'))->get('cache_avaliable_extensions');
            
            $extend_web_routes = [];
            $extend_mobile_routes = [];
            $extend_admin_routes = [];
            $extend_api_routes = [];
            
            //$extend = ['name','slug','version','install_time','app_key']
            foreach($extensions as $extend){
                if($files->exists(app_path($extend_path.'/'.ucfirst($extend['slug']).'/routes/web.php'))){
                    $extend_web_routes[] = app_path($extend_path.'/'.ucfirst($extend['slug']).'/routes/web.php');
                }
                
                if($files->exists(app_path($extend_path.'/'.ucfirst($extend['slug']).'/routes/mobile.php'))){
                    $extend_mobile_routes[] = app_path($extend_path.'/'.ucfirst($extend['slug']).'/routes/mobile.php');
                }
                
                if($files->exists(app_path($extend_path.'/'.ucfirst($extend['slug']).'/routes/admin.php'))){
                    $extend_admin_routes[] = app_path($extend_path.'/'.ucfirst($extend['slug']).'/routes/admin.php');
                }
                
                if($files->exists(app_path($extend_path.'/'.ucfirst($extend['slug']).'/routes/api.php'))){
                    $extend_api_routes[] = app_path($extend_path.'/'.ucfirst($extend['slug']).'/routes/api.php');
                }
            }
            
            self::$extend_web_routes = $extend_web_routes;
            self::$extend_mobile_routes = $extend_mobile_routes;
            self::$extend_admin_routes = $extend_admin_routes;
            self::$extend_api_routes = $extend_api_routes;
            
        }else{
            //暂无安装应用
        }
        
    }
    
    public static function adminHost()
    {
        return config('sudaconf.admin_host','');
        return request()->getHost();
    }

    public static function webHost()
    {
        return config('sudaconf.web_host',request()->getHost());
        return request()->getHost();
    }

    public static function mobileHost()
    {
        return config('sudaconf.mobile_host',self::webHost());
        return request()->getHost();
    }

    public static function apiHost()
    {
        return config('sudaconf.api_host',self::webHost());
        return request()->getHost();
    }
    
    public static function widget($widget_name,$config=[]){
        
        $widgets = config('sudaconf.widget',false);
        
        if(!$widgets){
            return;
        }

        if($widget_name=='suda_widget_extend')
        {
            $widgets = config('suda_custom.widget_extends',[]);
            $widget_name = isset($config['extend'])?$config['extend']:'';
        }
        
        $widgets = self::existWidget($widget_name,$widgets);
        
        if(is_string($widgets)){
            if(array_key_exists('async',$config)){
                return AsyncWidget::run($widgets,$config);
            }
            return Widget::run($widgets,$config);
        }elseif(is_array($widgets)){
            // foreach($widgets as $widget){
                
            //     //
                
            // }
        }
    }
    
    private static function existWidget($widget_name,$widget_group){
        
        $widgets=[];
        
        if(strpos($widget_name,'.')){
            $widgets = explode('.',$widget_name);
            $widget = $widgets[0];
        }else{
            $widget = $widget_name;
        }
        
        if(is_array($widget_group)){
            $left = $right = false;
            if(array_key_exists('left',$widget_group))
            {
                if(array_key_exists($widget,$widget_group['left'])){
                    $left = true;
                }    
            }
            if(array_key_exists('right',$widget_group))
            {
                if(array_key_exists($widget,$widget_group['right'])){
                    $right = true;
                }    
            }
            if(array_key_exists($widget,$widget_group) || ($left || $right)){
                
                if($widgets && count($widgets)>1){
                    return self::existWidget($widgets[1],$widget_group[$widget]);
                }
            
                return $widget_group[$widget];
            }
        }
        
        return $widget;
    }
    
    public static function sysName(){
        return self::$name;
    }
    public static function sysVersion(){
        return self::$version;
    }
    
    public static function sysInfo(){
        return [
            'name'      => self::$name,
            'version'   => self::$version,
            'author'    => self::$author,
            'email'     => self::$email,
        ];
    }
    
}
