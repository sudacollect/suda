<?php

namespace Gtd\Suda\Http\Controllers\Admin\Menu;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Validator;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;
use Gtd\Suda\Models\Operate;
use Gtd\Suda\Models\Setting;
use Gtd\Suda\Models\Menu;
use Gtd\Suda\Models\MenuItem;


class MenuController extends DashboardController
{
    public $table = 'menus';
    public $view_in_suda = true;
    
    public function menus(){
        
        $this->gate('setting.view',app(Setting::class));
        
        $this->title(__('suda_lang::press.setting_menu'));
        
        $menus = Menu::get();
        
        $this->setData('menus',$menus);
        $this->setMenu('tool','tool_menu');
        return $this->display('menu.list');    
    }
    
    
    public function editMenu(Request $request,$id=''){
        
        $this->title(__('suda_lang::press.add_menu'));
        $this->setData('modal_title',__('suda_lang::press.add_menu'));
        $this->setData('modal_icon_class','ion-reader-outline');
        
        $view = 'menu.add';
        if(intval($id)>0){
            $this->title(__('suda_lang::press.edit_menu'));
            
            $this->setData('modal_title',__('suda_lang::press.edit_menu'));
            $this->setData('modal_icon_class','ion-reader-outline');
            
            $menu = Menu::where('id','=',$id)->firstOrFail();
            if(!$menu){
                
                return $this->responseAjax('fail', '菜单不存在', 'menu');
                
            }
            $this->setData('menu',$menu);
            $view = 'menu.edit';
        }
        
        
        
        $this->setMenu('tool','tool_menu');
        return $this->display($view);
    }
    
    public function saveMenu(Request $request){
        
        $validator = $this->menuValidator($request->all());
        
        $response_msg = '';
        if (!$validator->passes()) {
            $msgs = $validator->messages();
            foreach ($msgs->all() as $msg) {
                $response_msg .= $msg . '</br>';
            }
            $response_type = false;
            
            return $this->responseAjax('fail',$response_msg);
        }
        
        $id = '';
        if($request->id){
            $id = $request->id;
        }
        
        if($id){
            Menu::where('id',$id)->update([
                'name'  => $request->name,
            ]);
            Menu::updateCache($id);
        }else{
            $menuModel = new Menu;
            $menuModel->name = $request->name;
            $menuModel->save();
            
            Menu::updateCache($menuModel->id);
        }
        
        return $this->responseAjax('success', '保存成功', 'menu');
        
    }
    
    
    //验证menu数据
    protected function menuValidator(array $data)
    {
        $roles = [
            'name'  => 'required|regex:/^[a-zA-Z][a-zA-Z0-9_]+$/u|unique:'.$this->table.'|min:2|max:64',
        ];
        
        
        if(array_key_exists('id',$data)){
            
            $roles = [
                'name' => [
                    'required',
                    'regex:/^[a-zA-Z][a-zA-Z0-9_]+$/u',
                    Rule::unique($this->table)->ignore($data['id'], 'id'),
                    'min:2',
                    'max:64'
                ],
            ];
            
        }
        
        $messages = [
            'name.required'=>__('suda_lang::validate.input_placeholder',['attribute'=>__('suda_lang::press.name')]),
            'name.unique'=>__('suda_lang::validate.validate_unique',['attribute'=>__('suda_lang::press.name')]),
            'name.regex'=>__('suda_lang::validate.regex_alpha',['attribute'=>__('suda_lang::press.name')]),
        ];
        
        return Validator::make($data, $roles,$messages);
    }
    
    
    public function deleteMenu(Request $request,$id){
        
        $request_id = '';
        if($request->id){
            $request_id = $request->id;
        }
        
        if(!$id || $request_id!=$id || $request->id==1){
            return $this->responseAjax('fail', '删除失败', 'menu');
        }else{
            $menu = Menu::where('id','=',$id)->first();
            Menu::where('id','=',$id)->delete();
            Menu::deleteCache($menu->name);
        }
        return $this->responseAjax('success', '菜单已删除', 'menu');
        
    }
    
    //菜单排序
    public function orderItems(Request $request)
    {
        
        $menuItems = $request->input('order');
        $parent_id = $request->parent_id;
        $this->orderMenu($menuItems, $parent_id);
        
    }
    
    private function orderMenu(array $menuItems, $parentId)
    {
        $menu_id = '';
        foreach ($menuItems as $index => $item_id) {
            $item = MenuItem::findOrFail($item_id);
            
            $menu_id = $item->menu_id;
            
            $item->order = $index + 1;
            $item->parent_id = $parentId;
            $item->save();
        }
        
        if($menu_id){
            Menu::updateCache($menu_id);
        }
    }
    
    public function items(Request $request,$id){
        $this->title(__('suda_lang::press.menu_item'));
        $this->setMenu('tool','tool_menu');
        
        $view = 'menu.items';
        if(intval($id)>0){
            
            $menu = Menu::where('id','=',$id)->firstOrFail();
            
            if(!$menu){
                return $this->dispatchError('404');
            }
            
            $this->setData('menu',$menu);
            
        }else{
            return $this->dispatchError('404');
        }
        
        $menu_items = MenuItem::where('menu_id','=',$id)->get();
        
        $this->setData('menu_items',$menu_items);
        
        return $this->display($view);
    }
    
    public function addItem(Request $request,$id=''){
        
        $this->setMenu('tool','tool_menu');
        $this->title(__('suda_lang::press.add_menu_item'));
        
        $this->setData('modal_title',__('suda_lang::press.add_menu_item'));
        $this->setData('modal_icon_class','zly-plus-circle');
        
        
        $view = 'menu.additem';
        if(intval($id)>0){
            
            $menu = Menu::where('id','=',$id)->firstOrFail();
            if(!$menu){
                
                return $this->responseAjax('fail', '菜单不存在', 'menu');
                
            }
            $this->setData('menu',$menu);
        }
        
        
        return $this->display($view);
    }
    
    public function editItem(Request $request,$id=''){
        
        $this->setMenu('tool','tool_menu');
        $this->title(__('suda_lang::press.add_menu_item'));
        $this->setData('modal_title',__('suda_lang::press.add_menu_item'));
        $this->setData('modal_icon_class','zly-plus-circle');
        
        if(intval($id)>0){
            $this->title(__('suda_lang::press.edit_menu_item'));
            
            $this->setData('modal_title',__('suda_lang::press.edit_menu_item'));
            $this->setData('modal_icon_class','zly-pencil');
            
            $item = MenuItem::where('id','=',$id)->firstOrFail();
            if(!$item){
                
                return $this->responseAjax('fail', '菜单项不存在', 'menu');
                
            }
            $this->setData('item',$item);
            $view = 'menu.edititem';
            return $this->display($view);
        }
        return $this->responseAjax('fail', '菜单项不存在', 'menu');
    }
    
    
    
    public function saveItem(Request $request){
        
        $validator = $this->itemValidator($request->all());
        
        $response_msg = '';
        if (!$validator->passes()) {
            $msgs = $validator->messages();
            foreach ($msgs->all() as $msg) {
                $response_msg .= $msg . '</br>';
            }
            $response_type = false;
            
            return $this->responseAjax('fail',$response_msg);
        }
        
        //检查menu_id的合法性
        if(!$request->menu_id){
            return $this->responseAjax('fail', '菜单不存在', 'menu');
        }
        
        $menu_id = $request->menu_id;
        $menu = Menu::where('id',$menu_id)->firstOrFail();
        
        if(!$menu){
            return $this->responseAjax('fail', '菜单不存在', 'menu');
        }
        
        $route = '';
        if($request->url_type==2){
            $route = $request->url;
            $request->url = '';
        }
        
        $id = '';
        if($request->id){
            $id = $request->id;
        }
        
        if($id){
            MenuItem::where('id',$id)->update([
                'title'      => $request->title,
                'slug'       => $request->slug,
                'url'        => $request->url,
                'route'      => $route,
                'target'     => $request->target,
                'icon_class' => $request->icon_class,
            ]);
            
            Menu::updateCache($request->menu_id);
        }else{
            $itemModel = new MenuItem;
            $itemModel->menu_id = $request->menu_id;
            $itemModel->title = $request->title;
            $itemModel->slug = $request->slug;
            $itemModel->url = $request->url;
            $itemModel->route = $route;
            $itemModel->target = $request->target;
            $itemModel->icon_class = $request->icon_class;
            $itemModel->order = 1;
            $itemModel->save();
            
            Menu::updateCache($request->menu_id);
        }
        
        return $this->responseAjax('success', '保存成功', 'menu/items/'.$menu_id);
        
    }
    
    
    //验证menu数据
    protected function itemValidator(array $data)
    {
        $roles = [
            'title'  => 'required|min:2|max:64',
            'slug'  => 'required|regex:/^[a-zA-Z][a-zA-Z0-9_]+$/u|unique:menu_items|min:2|max:64',
            'url'  => 'required|min:1',
        ];
        
        if(array_key_exists('id',$data)){
            
            $roles = [
                'title' => [
                    'required',
                    'min:2',
                    'max:64'
                ],
                'slug' => [
                    'required',
                    'regex:/^[a-zA-Z][a-zA-Z0-9_]+$/u',
                    Rule::unique('menu_items')->ignore($data['id'], 'id'),
                    'min:2',
                    'max:64'
                ],
                'url' => [
                    'required',
                ],
            ];
            
        }
        
        $messages = [
            'title.required'=>__('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.name')]),
            'slug.required'=>__('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.slug')]),
            'slug.unique'=>__('suda_lang::press.validate_unique',['column'=>__('suda_lang::press.slug')]),
            'slug.regex'=>__('suda_lang::validate.regex_alpha',['attribute'=>__('suda_lang::press.slug')]),
            'url.required'=>__('suda_lang::press.input_placeholder',['column'=>'URL']),
        ];
        
        return Validator::make($data, $roles,$messages);
    }
    
    
    public function deleteItem(Request $request,$menu_id,$id){
        
        $request_id = '';
        if($request->id){
            $request_id = $request->id;
        }
        
        if(!$id || $request_id!=$id || intval($request->id)<1){
            return $this->responseAjax('fail', '删除失败', 'menu/items/'.$menu_id);
        }else{
            $item = MenuItem::where('id','=',$id)->first();
            MenuItem::where('id','=',$id)->delete();
            
            Menu::updateCache($item->menu_id);
        }
        return $this->responseAjax('success', '菜单已删除', 'menu/items/'.$menu_id);
        
    }
}
