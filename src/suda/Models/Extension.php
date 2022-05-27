<?php

namespace Gtd\Suda\Models;

use Illuminate\Database\Eloquent\Model;
use Gtd\Suda\Traits\HasTaxonomies;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Extension extends Model
{
    
    
    public function heroimage()
    {
        return $this->hasOne('Gtd\Suda\Models\Media','id','hero_image')->where('typetable_type','Gtd\Suda\Models\Extension');
    }
    
    public function medias()
    {
        return $this->morphMany('Gtd\Suda\Models\Media', 'typetable');
    }
    
    public static function getExtMenuBySlug($slug){
        if(Cache::store(config('sudaconf.admin_cache','file'))->has('extension.'.strtolower($slug).'.menu_file')){
            return Cache::store(config('sudaconf.admin_cache','file'))->get('extension.'.strtolower($slug).'.menu_file');
        }
        if( file_exists(extension_path(ucfirst($slug)).'/menu.php') ){
            return require extension_path( ucfirst($slug) ).'/menu.php';
        }
        return [];
    }

    public static function getExtAuthBySlug($slug){
        if( file_exists(extension_path(ucfirst($slug)).'/auth_setting.php') ){
            return require extension_path( ucfirst($slug) ).'/auth_setting.php';
        }
        return false;
    }

    public static function getMenu($extension,$type,$options)
    {
        $menus = [];

        $menus = self::getExtMenuBySlug($extension->slug);

        if(array_key_exists('suda',$menus)){
            unset($menus['suda']);
        }
        if(array_key_exists('suda',$menus)){
            unset($menus['suda']);
        }
        
        // $dash_menus = [
        //     'suda_extension_dash'=>[
                
        //         'single'    => true,
        //         'title'     => '控制台',
        //         'slug'      => 'suda_extension_dash',
        //         'url'       => 'entry/extension/'.$extension->slug,
        //         'icon_class'=> 'ion-grid',
        //         'target'     => '_self',
        //         'order'     => 0,
        
        //         'children' => [],
                
        //     ]
        // ];
        // if(!property_exists($extension,'setting') || !property_exists($extension->setting,'menu_style'))
        // {
        //     $dash_menus = [];
        // }
        
        $dash_menus = [];

        //$menus = array_merge($return_menus,$menus);
        
        $type = 'view_suda::admin.menu.display.'.$type;
        
        $options['extension_slug'] = $extension->slug;
        
        $options['menu_style'] = 'normal';
        
        // if(property_exists($extension,'setting')){
        //     if(is_array($extension->setting) && array_key_exists('menu_style',$extension->setting))
        //     {
        //         $options['menu_style'] = $extension->setting['menu_style'];
        //     }elseif(!is_array($extension->setting) && property_exists($extension->setting,'menu_style'))
        //     {
        //         $options['menu_style'] = $extension->setting->menu_style;
        //     }
        // }

        $options = self::getOptions($options);
        
        $has_children = false;
        if(property_exists($options,'current_menu'))
        {
            $current_key = array_key_first((array)$options->current_menu);
            if(isset($menus[$current_key]) && isset($menus[$current_key]['children']) && count($menus[$current_key]['children'])>0)
            {
                $has_children = true;
            }
        }

        $data = ['items' => $menus,'menu_name'=>$extension->slug,'return_menus'=>$dash_menus,'has_children'=>$has_children, 'view'=>$type, 'options' => $options];

        return $data;

    }
    
    public static function menuDisplay($extension,$type,$options)
    {
        
        $data = self::getMenu($extension,$type,$options);

        return new \Illuminate\Support\HtmlString(
            \Illuminate\Support\Facades\View::make($data['view'], $data)->render()
        );
        
    }
    
    
    //return object
    protected static function getOptions($options){
        
        $options = (object) $options;
        
        //获取当前语言
        if (!isset($options->locale)) {
            $options->locale = app()->getLocale();
        }
        
        //获取侧栏样式设置
        $sidemenu = Cache::store(config('sudaconf.admin_cache','file'))->get('sidemenu#'.Auth::guard('operate')->user()->id);
        
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
}
