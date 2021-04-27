<?php
/**
 * ThemeService.php
 * 模板服务
 * date 2017-09-06 14:41:54
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */
 
namespace Gtd\Suda\Services;

use View;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\FileviewFinder;

use Arrilot\Widgets\Facade as Widget;
use Arrilot\Widgets\AsyncFacade as AsyncWidget;

use Gtd\Suda\Models\Setting;
use Gtd\Suda\Models\ThemeWidget;
use Gtd\Suda\Models\Menu;
use Gtd\Suda\Models\Extension;
use Gtd\Suda\Services\ExtensionService;

class ThemeService {
    
    protected $extension = 'php';
    
    protected $themePaths = null;
    protected $themeCache = [];

    protected static $current_app = '';
    protected static $current_theme = '';
    
    public function __construct(){
        
        $this->files = new Filesystem;
        $this->apps = config('sudaconf.apps',['site','admin','mobile']);

    }
    
    public function availableThemes($app = 'site',$force = false)
    {

        if(!$app || !in_array($app,$this->apps)){
            exit('Suda Error! App '.$app.' Not Found');
        }

        if (is_null($this->themeCache) || !isset($this->themeCache[$app]) || $force) { 
          
           //读取所有模板
           $themes = Cache::store(config('sudaconf.admin_cache','file'))->get('theme_'.$app);
          
           if($themes && !$force){
               $this->themeCache[$app] = $themes;
           }else{
               $this->updateCache($app);
           }
        }
        if(isset($this->themeCache[$app])){
            return $this->themeCache[$app];
        }
        return [];
    }
    
    //更新模板信息缓存
    public function updateCache($app)
    {
        $app_dir_path = theme_path($app);
        if(!$this->files->exists($app_dir_path)){
            return;
        }

        //模板风格目录，支持多套风格
        $themes_path = $this->files->directories($app_dir_path);

        if(empty($themes_path)){
            return;
        }

        $themes = [];

        foreach($themes_path as $theme_path){
            $theme_basename = basename($theme_path);
            
            if(array_key_exists($theme_basename,$themes)){
                continue;
            }

            if($this->files->exists($theme_path.'/theme.php')){
                $theme = require_once($theme_path.'/theme.php');
                if($theme && is_array($theme)){
                    $theme[$theme_basename]['screenshot'] = 'theme/'.$app.'/'.$theme_basename.'/screenshot.png';
                    $themes[$theme_basename] = $theme[$theme_basename];
                }
            }else{
                //忽略缺少配置的模板
            }
            
        }
        
        //默认主题启用
        if(count($themes) > 0 && !array_key_exists($this->getTheme($app),$themes)){
            $this->setTheme($app,config('sudaconf.theme.'.$app,'default'));
        }

        if(count($themes)>0){
            $this->themeCache[$app] = $themes;
            //写配置文件
            Cache::store(config('sudaconf.admin_cache','file'))->forever('theme_'.$app, $themes);
        }
        
    }
    
    //设置当前主题
    public function setTheme($app_name,$theme_name,$return = false)
    {

        $theme = Setting::where('key',$app_name.'_theme')->where('group','theme')->first();

        $themes = $this->availableThemes($app_name);
        
        if(array_key_exists($theme_name,$themes)){

            if($theme){
                $theme->update([
                    'values'=>$theme_name,
                ]);
            }else{
                $settingModel = new Setting;
                $settingModel->insert(['key'=>$app_name.'_theme','group'=>'theme','type'=>'text','values'=>$theme_name]);
            }
            
            if($return){
                return true;
            }

        }
        
        if($return){
            return false;
        }
    }

    //获取当前主题
    public function getTheme($app_name='')
    {

        $theme = Setting::where('key',$app_name.'_theme')->where('group','theme')->first();
        
        if($theme){
            return $theme->values;
        }else{
            if($app_name){
                $theme_name = config('sudaconf.theme.'.$app_name,'default');
                return $theme_name;
            }
        }
        return 'default';
    }

    public static function setCurrentApp($app)
    {
        self::$current_app = $app;
    }
    public static function setCurrentTheme($theme)
    {
        self::$current_theme = $theme;
    }
    
    //模板解析
    public function render($app,$view,$view_source,$data,$theme='')
    {
        
        if(!$app || !in_array($app,$this->apps)){
            exit('Suda Error! App '.$app.'/theme Not Found');
        }
        
        self::setCurrentApp($app);

        if( $app == 'admin' ){
            if(empty($theme) || !$theme){
                $theme = $this->getTheme($app);
            }
        }else{
            $theme = $this->getTheme($app);
        }
        
        self::setCurrentTheme($theme);
        
        if(array_key_exists('preview_theme',$data) && !empty($data['preview_theme'])){
            $theme = $data['preview_theme'];
        }
        
        $theme_path = theme_path($app.'/'.$theme.'/views');
        
        if(!$this->files->exists(theme_path($app.'/'.$theme).'/theme.php')){
            if($app=='admin'){
                $theme = 'default';
                $theme_path = theme_path($app.'/'.$theme.'/views');
            }else{
                exit('Suda Error! '.$app.'/'.$theme.' Theme Not Found');
            }
            
        }
        
        $extension = $this->extension;
        $namespace = 'theme_'.$app;
        
        $view = str_replace('.', '/', $view);
        $view_source_path = str_replace('.', '/', $view_source);
        $theme_view_path = $theme_path.'/'.$view_source_path.'.blade.'.$extension;
        
        $data['sdcore']['theme'] = $theme;
        $data['sdcore']['admin_path'] = config('sudaconf.admin_path','admin');
        $data['sdcore'] = arrayObject($data['sdcore']);
        
        View::share('sdcore',$data['sdcore']);
        View::share('theme',$theme);
        
        //add namespace
        View::addNamespace($namespace, $theme_path);
        View::addNamespace('view_path', $theme_path);
        
        //define view_app
        $view_app = suda_path('resources/views/'.$app);
        View::addNamespace('view_app', $view_app);

        //弥补package定义的不足
        View::addNamespace('view_suda', suda_path('resources/views'));
        
        if($this->files->exists(resource_path('views/'.$app.'/'.$view_source_path.'.blade.'.$extension))){

            $resource_path = resource_path('views/'.$app);
            View::addNamespace('view_path', $resource_path);
            
            if(View::exists($app.'.'.$view_source)){
                return view($app.'.'.$view_source)->with($data);
            }

        }elseif($this->files->exists($theme_view_path)){
            
            //重新定义finder
            $viewFinder = new FileviewFinder($this->files,[$theme_path]);
            View::setFinder($viewFinder);
            
            //增加namespace
            View::addNamespace($namespace, $theme_path);
            View::addNamespace('view_path', $theme_path);

            return view($view_source)->with($data);
            
        }else{
            View::addNamespace('view_path', $view_app);
        }

        $in_extension = false;
        if($app == 'admin')
        {
            //菜单和导航等
            if(isset($data['single_extension_menu']) && $data['single_extension_menu'])
            {
                //读取独立的应用菜单
                $menus = [];
                if(property_exists($data['sdcore'],'extension'))
                {
                    $menus = Extension::getMenu($data['sdcore']->extension,'sidebar',['current_menu'=>isset($data['current_menu'])?$data['current_menu']:[]]);

                    $in_extension = $data['sdcore']->extension;
                }else{
                    exit('extension loading error.');
                }

            }else{
                //读取系统菜单
                $menus = Menu::getMenu(config('sudaconf.default_menu', 'suda'),'sidebar',['current_menu'=>isset($data['current_menu'])?$data['current_menu']:[]]);
            }
            
            $menu_breadcrumbs = [];
            // $menu_breadcrumbs[] = [
            //     'slug'=>'dashboard',
            //     'title'=>'控制面板',
            //     'url'=>'',
            //     'route'=>'',
            // ];
            if(isset($data['current_menu']) && isset($menus['items'])){
                $items = $menus['items'];
                foreach($data['current_menu'] as $cur_key=>$cur_menu)
                {
                    if($cur_key!='dashboard')
                    {
                        if(isset($items[$cur_key]))
                        {
                            if((!isset($items[$cur_key]['breadcrumb']) || $items[$cur_key]['breadcrumb']))
                            {
                                $menu_breadcrumbs[] = [
                                    'slug'=>$cur_key,
                                    'title'=>$items[$cur_key]['title'],
                                    'url'=>(property_exists($data['sdcore'],'extension'))?admin_ext_url($data['sdcore']->extension->slug.'/'.$items[$cur_key]['url']):$items[$cur_key]['url'],
                                    'route'=>isset($items[$cur_key]['route'])?$items[$cur_key]['route']:'',
                                ];
                            }

                            if(isset($items[$cur_key]['children']))
                            {
                                foreach($items[$cur_key]['children'] as $child)
                                {
                                    if($child['slug']==$cur_menu && (!isset($child['breadcrumb']) || $child['breadcrumb']))
                                    {
                                        $menu_breadcrumbs[] = [
                                            'slug'=>$cur_menu,
                                            'title'=>$child['title'],
                                            'url'=>(property_exists($data['sdcore'],'extension'))?admin_ext_url($data['sdcore']->extension->slug.'/'.$child['url']):$child['url'],
                                            'route'=>isset($child['route'])?$child['route']:'',
                                        ];
                                    }
                                }
                                
                            }
                        }

                    }
                    
                }
            }
            
            if($in_extension)
            {
                array_unshift($menu_breadcrumbs,
                    [
                        'slug'=>'extension',
                        'title'=>$in_extension->name,
                        'url'=>$in_extension->default_page_url,
                        'icon'=>'<i class="ion-folder-open-outline"></i>&nbsp;',
                        'route'=>'',
                    ]
                );
            }
            
            array_unshift($menu_breadcrumbs,
                [
                    'slug'=>'dashboard',
                    'title'=>'控制台',
                    'url'=>'',
                    'icon'=>'',
                    'route'=>'',
                ]
            );

            //增加额外的bread
            if(isset($data['extend_breadcrumbs']))
            {
                foreach((array)$data['extend_breadcrumbs'] as $bread)
                {
                    array_push($menu_breadcrumbs,$bread);
                }
            }
            
            $data['sdcore']->menu_breadcrumbs = $menu_breadcrumbs;
            $data['sdcore']->sidemenus = $menus;

            //扩展应用的自定义导航
            $extension_navi = (new ExtensionService)->getExtensionNavi(isset($data['soperate'])?$data['soperate']:false);
            
            foreach($extension_navi as $navi)
            {
                foreach($navi as $item)
                {
                    array_push($data['custom_navi'],$item);
                }
                
            }


            //导航的颜色和配置
            $navbar_style = 'navbar navbar-expand-sm fixed-top navbar-suda ';
            //navbar-light bg-white 

            if(config('sudaconf.sidemenu_style','')=='pro')
            {
                $navbar_style .= ' navbar-pro ';

                if($data['sdcore']->sidemenus['has_children'])
                {
                    $navbar_style .= ' navbar-pro-with-sub-menus ';
                }
            }elseif(isset($data['sidemenu_style']) && $data['sidemenu_style']=='icon')
            {
                $navbar_style .= ' navbar-suda-icon ';
            }

            $navbar_style_layout = ' navbar-light bg-white ';
            
            if(isset($data['soperate']) && isset($data['soperate']->permission['style']))
            {
                if(array_key_exists('dashboard_layout',$data['soperate']->permission['style']))
                {
                    $dashboard_layout = $data['soperate']->permission['style']['dashboard_layout'];
                    if(isset($dashboard_layout['navbar_layout']))
                    {
                        if($dashboard_layout['navbar_layout']=='flat')
                        {

                        }
                        if($dashboard_layout['navbar_layout']=='fluid')
                        {
                            $navbar_style .= ' navbar-suda-fluid ';
                        }
                    }
                    if($theme == 'colorbar')
                    {
                        $navbar_style .= ' navbar-suda-fluid ';
                    }
                    if(isset($dashboard_layout['navbar_color']))
                    {
                        if($dashboard_layout['navbar_color']=='white')
                        {

                        }
                        if($dashboard_layout['navbar_color']=='dark')
                        {
                            $navbar_style_layout = ' navbar-dark bg-dark ';
                        }
                        if($dashboard_layout['navbar_color']=='blue')
                        {
                            $navbar_style_layout = ' navbar-dark bg-blue ';
                        }
                        if($dashboard_layout['navbar_color']=='coffe')
                        {
                            $navbar_style_layout = ' navbar-dark bg-coffe ';
                        }
                    }
                }
                
            }
            
            $navbar_style .= $navbar_style_layout;
            
            $data['navbar_style'] = $navbar_style;
        }

        
        
        return view($view)->with($data);
            
        
    }
    

    //缓存挂件
    public function updateWidgetCache($app,$theme,$widget_area)
    {
        $theme_widgets = ThemeWidget::where('app',$app)->where('theme',$theme)->where('widget_area',$widget_area)->orderBy('order','asc')->get();
        $theme_widgets = $theme_widgets->toArray();

        Cache::store(config('sudaconf.admin_cache','file'))->forever($app.'_theme_'.$theme.'_widget_'.$widget_area, $theme_widgets);

    }

    //读取模板挂件区配置
    public function getWidgetArea($app,$theme)
    {

        $themes = $this->availableThemes($app);

        if(!$themes){
            return false;
        }

        if(!array_key_exists($theme,$themes)){
            return false;
        }

        $theme_config = $themes[$theme];

        //判断是否设置了挂件区域
        if(!array_key_exists('widgets',$theme_config)){
            return false;
        }
        
        return $theme_config['widgets'];
    }

    //获取系统可用挂件
    public function getWidgets()
    {

        $core_widgets = $this->getCoreWidgets();
        $custom_widgets = $this->getCustomWidgets();

        $widgets = $core_widgets;
        if($custom_widgets){
            $widgets = array_merge($custom_widgets, $core_widgets);
        }

        return $widgets;

    }

    //获取系统核心挂件
    public function getCoreWidgets()
    {

        $filesystem = new Filesystem();

        $widgets = [];
        $widget_path = $filesystem->files(base_path('vendor/gtd/suda/resources/widgets'));
        
        if(!empty($widget_path)){

            foreach($widget_path as $path){
                $widget = require_once($path);
                $widgets[$widget['slug']] = $widget;
            }

        }

        return $widgets;
    }

    public function getCustomWidgets()
    {

        $custom_widget_path = config('sudaconf.custom_widget_path','');
        if(!$custom_widget_path){
            return false;
        }
        $filesystem = new Filesystem();

        $widgets = [];
        $widget_path = $filesystem->files(base_path($custom_widget_path));
        
        if(!empty($widget_path)){

            foreach($widget_path as $path){
                $widget = require_once($path);
                $widgets[$widget['slug']] = $widget;
            }

        }

        return $widgets;
    }

    //检查挂件是否可用
    public static function checkWidgetArea($widget_area)
    {
        $app = self::$current_app;
        $theme = self::$current_theme;
        
        $themes = app('theme')->availableThemes($app);

        if(!isset($themes[$theme])){
            return false;
        }

        $theme_config = $themes[$theme];

        //判断是否设置了挂件区域
        if(!array_key_exists('widgets',$theme_config)){
            return false;
        }
        
        //判断挂件区域是否存在
        if(!array_key_exists($widget_area,$theme_config['widgets'])){
            return false;
        }

        $widget_config = $theme_config['widgets'][$widget_area];

        return $theme_config['widgets'][$widget_area];
    }

    //挂件初始化
    public static function widget($widget_area_name,$params=[])
    {

        $app = self::$current_app;
        $theme = self::$current_theme;

        $widget_area = self::checkWidgetArea($widget_area_name);
        if(!$widget_area)
        {
            return false;
        }
        
        //直接读取缓存内的挂件
        $widgets = Cache::store(config('sudaconf.admin_cache','file'))->get($app.'_theme_'.$theme.'_widget_'.$widget_area_name);
        
        //是否异步加载, 此参数无效
        $async = true;
        if(array_key_exists('async',$params)){
            $async = $params['async'];
        }

        //输出挂件内容
        return view('view_suda::widgets.theme.widget_view')->with(['widgets'=>$widgets,'async'=>$async,'params'=>$params]);
        
    }

}
