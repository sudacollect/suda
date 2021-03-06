<?php

namespace Gtd\Suda\Models;

use View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;

//use Gtd\Suda\Events\MenuDisplay;

use Gtd\Suda\Cache as SudaCache;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;


class Menu extends Model
{
    protected $table = 'menus';

    protected $guarded = [];
    

    public function items()
    {
        return $this->hasMany(\Gtd\Suda\Models\MenuItem::class);
    }

    public function parent_items()
    {
        $items = $this->hasMany(\Gtd\Suda\Models\MenuItem::class)->where(function($query){
            $query->whereNull('parent_id')->orWhere('parent_id','0');
        })->orderBy('order');
        return $items;
    }
    
    
    public static function deleteCache($menu_name){
        
        SudaCache::init()->forget('menu.'.$menu_name,$menu->toArray());
        
    }
    public static function updateCache($id){
        
        $menu = static::where('id', '=', $id)
            ->with(['parent_items.children' => function ($q) {
                $q->orderBy('order');
            }])->first();
        
        SudaCache::init()->forever('menu.'.$menu->name,$menu->toArray());
    }
    
    public static function getMenuByName($menu_name,$update = false,$return_object=false){
        
        if(!SudaCache::init()->get('menu.'.$menu_name) || $update || $return_object){
            
            $menu = static::where('name', '=', $menu_name)
                ->with(['parent_items.children' => function ($q) {
                    $q->orderBy('order');
                }])->first();
            
            if($return_object){
                return $menu;
            }
            
            SudaCache::init()->forever('menu.'.$menu_name,$menu->toArray());
        }
        
        return SudaCache::init()->get('menu.'.$menu_name);
    }

    public static function getMenu($menu_name, $type = null, array $options = [])
    {
        $menu_views = ['manage','sidebar','topbar'];
        
        $menu_view = $type;
        //??????????????????????????????
        if (in_array($type, $menu_views)) {
            $type = 'view_suda::admin.menu.display.'.$type;
        } else {
            exit('menu'.__('suda_lang::press.error.miss_blade'));
        }
        
        if(array_key_exists('no_cache',$options) && $options['no_cache']==true){
            
            $menu = self::getMenuByName($menu_name,true,true);
            
            $items = $menu->parent_items;

            // $items = $menu->parent_items->sortBy('order');
        }else{
            $menu = self::getMenuByName($menu_name);
            $items = $menu['parent_items'];
            $items = collect($items)->keyBy('slug')->toArray();

            //??????????????????
            //$extension_menus = app('suda_extension')->getExtensionMenu();
            $extension_menus = [];
            
            $need_keys = ['title','slug','url','icon_class','target'];
            
            foreach((array)$extension_menus as $ext_slug=>$ext_menus){
                
                if(!array_key_exists('suda',(array)$ext_menus) || !array_key_exists('suda',(array)$ext_menus)){
                    continue;
                }
                
                foreach((array)$ext_menus['suda'] as $ext_menu_key=>$ext_menu){
                    
                    if(array_key_exists($ext_menu_key,$items)){
                        //???????????????
                        if(array_key_exists('children',$ext_menu)){
                            foreach((array)$ext_menu['children'] as $e_menu){
                                $e_keys = array_keys($e_menu);
                                $diffs = array_diff($need_keys,$e_keys);
                                if(count($diffs)<1){
                                    //?????????????????????
                                    $e_menu['extension_slug'] = $ext_slug;
                                    array_push($items[$ext_menu_key]['children'],$e_menu);
                                }
                            }
                        }
                        
                        
                    }else{
                        //???????????????
                        $e_keys = array_keys((array)$ext_menu);
                        $diffs = array_diff($need_keys,$e_keys);
                        
                        if(count($diffs)<1){
                            if(array_key_exists('extend',$ext_menu) && $ext_menu['extend']){
                                //???????????????
                                if(array_key_exists('children',$ext_menu)){
                                    foreach((array)$ext_menu['children'] as $ek=>$e_menu){
                                        $e_keys = array_keys($e_menu);
                                        $diffs = array_diff($need_keys,$e_keys);
                                        if(count($diffs)>0){
                                            unset($ext_menu['children'][$ek]);
                                        }
                                    }
                                }
                                
                                $ext_menu['extension_slug'] = $ext_slug;
                                $items[$ext_menu_key] = $ext_menu;
                            }
                            
                        }
                        
                    }
                    
                }
                
            }
            
        }
        
        if (!isset($menu)) {
            return false;
        }
        $options = self::getOptions($options);
        
        $has_children = false;
        if(property_exists($options,'current_menu'))
        {
            $current_key = array_key_first((array)$options->current_menu);
            if(isset($items[$current_key]) && isset($items[$current_key]['children']) && count($items[$current_key]['children'])>0)
            {
                $has_children = true;
            }
        }
        
        //#TODO ????????????HACK??????
        //event(new MenuDisplay($menu));

        return ['items' => $items,'menu_name'=>$menu_name,'menu'=>$menu, 'options' => $options,'has_children'=>$has_children, 'view'=>$type];

    }
    
    public static function display($menu_name, $type = null, array $options = [])
    {

        $data = self::getMenu($menu_name,$type,$options);
        if(!$data)
        {
            return false;
        }
        return new \Illuminate\Support\HtmlString(
            \Illuminate\Support\Facades\View::make($data['view'], $data)->render()
        );
    }
    
    //return object
    protected static function getOptions($options){
        
        $options = (object) $options;
        
        //??????????????????
        if (!isset($options->locale)) {
            $options->locale = app()->getLocale();
        }
        
        //????????????????????????
        $sidemenu = false;
        if(Auth::guard('operate')->user())
        {
            $sidemenu = Cache::store(config('sudaconf.admin_cache','file'))->get('sidemenu#'.Auth::guard('operate')->user()->id);
        }
        
        
        if($sidemenu && array_key_exists('style',$sidemenu)){
            $sidemenu_style = $sidemenu['style'];
        }else{
            //??????????????????
            $sidemenu_style = 'flat';
        }
        $options->sidemenu_style = $sidemenu_style;
        
        $operate = Auth::guard('operate')->user();
        $options->operate = $operate;
        
        return $options;
    }
}
