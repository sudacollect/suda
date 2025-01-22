<?php
/**
 * RoleController.php
 * description
 * date 2017-11-06 10:23:31
 * author suda <dev@panel.cc>
 * @copyright Suda. All Rights Reserved.
 */

namespace Gtd\Suda\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;
use Response;
use Illuminate\Support\Arr;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;

use Gtd\Suda\Auth\Authority;
use Gtd\Suda\Models\User;
use Gtd\Suda\Models\Role;

use Gtd\Suda\Models\Menu;
use Gtd\Suda\Models\Media;
use Gtd\Suda\Models\Setting;

use Gtd\Suda\Requests\RoleRequest;

class RoleController extends DashboardController
{
    public $view_in_suda = true;
    
    function __construct(){
        parent::__construct();
        
        $this->setMenu('setting','setting_operate_role');
        
        $this->middleware(function (Request $request, $next) {
            
            $this->gate('setting.setting_operate_role',app(Setting::class),$request->ajax());
            
            return $next($request);
        });
        
    }
    
    //$view = ['list','gallery']
    public function index(Request $request,$view='list')
    {
        $this->title(__('suda_lang::press.menu_items.setting_operate_role'));
        $page_no = 0;
        if($request->get('page')){
            $page_no = $request->get('page');
        }
        
        if(!\Gtd\Suda\Auth\OperateCan::superadmin($this->user)){
            $auth_slugs = \Gtd\Suda\Auth\OperateCan::getAuthoritesByLevel($this->user->level);
            $roles = Role::whereIn('authority',$auth_slugs)->where('id','<>',$this->user->role_id)->orderBy('id','desc')->paginate(20,['*'],'page',$page_no);
        }else{
            $roles = Role::where([])->orderBy('id','desc')->paginate(20,['*'],'page',$page_no);
        }
        
        
        
        $this->setData('roles',$roles);
        
        $view = 'user.role.list';
        
        return $this->display($view);
        
    }
    
    
    public function add(){
        $this->title('增加角色');
        
        $auths = $this->_authority();
        $this->setData('auths',$auths);
        

        //额外的bread
        $this->setData('extend_breadcrumbs',[
            [
                'slug'=>'role.add',
                'title'=>'增加角色',
                'url'=>'user/roles/add',
                'route'=>'',
            ]
        ]);
        
        return $this->display('user.role.add');
    }
    
    public function edit($id=0){
        $this->title('编辑角色');
        
        $id = intval($id);
        $role = Role::where('id','=',$id)->first();
        
        if(!$role){
            return redirect(admin_url('error'))->withErrors(['errcode'=>404,'errmsg'=>'没找到信息']);
        }

        $this->setData('role',$role);
        
        $auths = $this->_authority();
        $this->setData('auths',$auths);
        
        
        return $this->display('user.role.edit');
    }
    
    
    public function saveRole(RoleRequest $request)
    {
        
        $error = '';
        
        $id = intval($request->id);
        // $role_exist = Role::where('id','<>',$id)->where('name','=',$request->name)->first();
        $roleModel = new Role;
        
        $disable = 1;
        if($request->has('disable') && $request->disable=='on'){
            $disable = 0;
        }
        
        $authority = '';
        if($request->authority){
            $authority = $request->authority;
        }
        
        if($id){
            $role = $roleModel->where('id',$id)->first();
            if(!$role)
            {
                return $this->responseAjax('fail','角色不存在',$url);
            }
            $new_permissions = [];
            if(!empty($role->permissions)){
                $permissions = $role->permissions;
                if($authority=='extension')
                {
                    $new_permissions['exts'] = $permissions['exts'];
                }else{
                    $new_permissions['sys'] = $permissions['sys'];
                }
            }
            
            $roleModel->where('id',$id)->update([
                'name'=>$request->name,
                'disable'=>$disable,
                'authority'=>$authority,
                'permission'=>serialize($new_permissions),
            ]);
        }else{
            
            $role_exist = Role::where('name','=',$request->name)->first();
            
            if($role_exist){
                $url = '';
                return $this->responseAjax('fail','角色名称重复',$url);
            }
            
            $roleModel->name = $request->name;
            $roleModel->disable = $disable;
            $roleModel->authority = $authority;
            $roleModel->permission = serialize(['sys'=>$request->permission]);
            
            $roleModel->save();
            
        }
        
        
        $url = 'user/roles';
        return $this->responseAjax('success',__('suda_lang::press.msg.success'),$url);
        
        // return redirect('user/roles');
    }
    
    public function delete(Request $request){
        
        if($request->id && !empty($request->id)){
            
            $screen = Role::where('id',$request->id)->first();
            
            if($screen){
                
                Role::where('id',$request->id)->delete();
                $url = '';
                return $this->responseAjax('success','删除成功',$url);
                
            }else{
                $url = 'user/roles';
                return $this->responseAjax('error','数据不存在,请重试',$url);
            }
            
            
        }else{
            $url = 'user/roles';
            return $this->responseAjax('error','数据不存在,请重试',$url);
        }
        
    }
    
    
    private function getDefaultMenu(){
        $data = Menu::getMenuByName(config('sudaconf.default_menu', 'suda'));
        return isset($data['parent_items'])?$data['parent_items']:[];
    }
    
    private function getPermissionActions(){
        
        return config('sudaconf.permissions',[
            'view'  =>[
                'name'=>'view',
                'display_name'=>'查看',
            ],
            'create'=>[
                'name'=>'create',
                'display_name'=>'新增',
            ],
            'update'=>[
                'name'=>'update',
                'display_name'=>'编辑',
            ],
            'delete'=>[
                'name'=>'delete',
                'display_name'=>'删除',
            ],
        ]);
        
    }
    
    public function loadPermissions(){
        $menus = $this->getDefaultMenu();
        $this->setData('menus',$menus);
        
        // $permissions = $this->getPermissionActions();
        // $this->setData('permissions',$permissions);
    }
    
    //所有权限列表
    private function _authority(){
        return Authority::cases();
    }


    // system permission
    public function showSys(Request $request,$id=0)
    {

        if(\Gtd\Suda\Auth\OperateCan::general($this->user))
        {
            return redirect(admin_url('error'));
        }

        $this->title(__('suda_lang::press.sys_permission'));
        
        $id = intval($id);
        $role = Role::where('id','=',$id)->first();
        
        if(!$role){
            return redirect(admin_url('error'))->withErrors(['errcode'=>404,'errmsg'=>'没找到信息']);
        }

        $permissions = $role->permissions;
        
        if($role->disable==1){
            $permissions = unserialize($role->permission);
            if(!$role->permission){
                $permissions = [
                    'sys'=>[],
                    'exts'=>[],
                ];
            }

            if(!array_key_exists('sys',$permissions) || !is_array($permissions['sys'])){
                $permissions['sys'] = [];
            }

            if(!array_key_exists('exts',$permissions)){
                $permissions['exts'] = [];
            }
        }
        
        
        
        $this->setData('role_permissions',$permissions);
        
        $this->setData('role',$role);
        
        $this->loadPermissions();
        
        return $this->display('user.role.sys_set');
    }

    //保存系统权限
    public function saveSys(Request $request){
        
        $error = '';
        $id = intval($request->id);

        $roleModel = new Role;
        
        if($id){
            $role = $roleModel->where('id',$id)->first();
            if(!$role)
            {
                return $this->responseAjax('fail','角色不存在',$url);
            }
            $new_permissions = [];
            if(!empty($role->permissions)){
                $permissions = $role->permissions;
                if(array_key_exists('exts',$permissions)){
                    $new_permissions['exts'] = $permissions['exts'];
                }
            }
            
            $new_permissions['sys'] = $request->permission;
            
            $roleModel->where('id',$id)->update([
                'permission'=>serialize($new_permissions)
            ]);
        }else{
            
            return $this->responseAjax('fail','请先选择角色',$url);
            
        }
        
        
        $url = 'user/roles';
        return $this->responseAjax('success',__('suda_lang::press.msg.success'),$url);
        
        // return redirect('user/roles');
    }

    //第一步，选择应用
    public function showExts(Request $request,$role_id)
    {
        $this->title('选择应用');

        //应用基础信息
        $id = intval($role_id);
        $role = Role::where('id','=',$id)->first();
        
        if(!$role){
            return redirect(admin_url('error'))->withErrors(['errcode'=>404,'errmsg'=>'没找到信息']);
        }
        
        $this->setData('role',$role);

        //获取应用
        $available_exts = app('suda_extension')->availableRoleExtensions();
        
        
        $this->setData('ext_list',$available_exts);
        $this->setData('ext_count',count($available_exts));
        
        
        return $this->display('user.role.exts');

    }

    //保存应用权限
    public function saveExts(Request $request)
    {
        if(!$request->has('select_exts') || count($request->select_exts)<1){

            //return $this->responseAjax('fail','请至少选择1个应用');

        }
        
        //过滤提交的权限设置
        $select_exts = [];
        if($request->select_exts)
        {
            $select_exts = $request->select_exts;
        }
        $select_permission = [];
        $auth_permission = [];
        if(count($select_exts)>0){
            foreach((array)$request->select_permission as $ext_slug=>$permission){
                if(array_key_exists($ext_slug,$select_exts)){
                    parse_str($permission,$output);
                    
                    foreach($output['permission'] as $pk=>&$pv)
                    {
                        if($pk=='#auth')
                        {
                            continue;
                        }
                        foreach($pv as $ppk=>$ppv)
                        {
                            if($ppk!='_all_')
                            {
                                if(!isset($ppv['__on__']) || $ppv['__on__']!='true')
                                {
                                    Arr::forget($output['permission'][$pk],$ppk);
                                }
                            }
                        }

                        if(!$output['permission'][$pk] || count($output['permission'][$pk])<1)
                        {
                            Arr::forget($output['permission'],$pk);
                        }
                    }
                    
                    $select_permission[$ext_slug] = $output['permission'];
                    
                    $menu_group_name = array_key_first($output['permission']);
                    $menu_group = Arr::first($output['permission']);
                    $menu_first_slug = Arr::first(array_keys((array)$menu_group) ,function($value,$key){
                        return $value!='_all_';
                    });
                    
                    $extService = app('suda_extension')->use($ext_slug);
                    $ext = $extService->extension;
                    $menus = $extService->getMenu();
                    
                    $childs = [];
                    isset($menus[$menu_group_name]['children'])?$childs = $menus[$menu_group_name]['children']:'';
                    
                    $entrance_menu = Arr::first($childs,function($value,$key) use ($menu_first_slug){
                        return isset($value['slug']) && $value['slug']==$menu_first_slug;
                    });
                    if($entrance_menu){
                        $select_permission[$ext_slug]['ext_entrance_menu'] = $entrance_menu;
                    }
                    
                }
            }
        }
        
        $role_id = intval($request->role_id);
        $role = Role::where('id','=',$role_id)->first();
        
        if(!$role){
            return $this->responseAjax('fail','角色不存在');
        }
        
        $new_permissions = [];
        $new_permissions['exts'] = $select_permission;
        $new_permissions['sys'] = $role->permissions['sys'];

        Role::where('id','=',$role_id)->update([
            'permission'=>serialize($new_permissions),
        ]);

        return $this->responseAjax('success','权限设置完毕','user/roles');

    }

    //第二步设置应用权限
    public function setExts(Request $request,$role_id)
    {
        return false;
        $this->title('应用权限设置');

        //应用基础信息
        $id = intval($role_id);
        $role = Role::where('id','=',$id)->first();
        
        if(!$role){
            return redirect(admin_url('error'))->withErrors(['errcode'=>404,'errmsg'=>'没找到信息']);
        }
        if(count($role->permissions['exts'])<1){
            return redirect(admin_url('user/roles/showexts/'.$id));
        }

        $this->setData('role',$role);


        //获取应用
        $available_exts = app('suda_extension')->installedExtensions();
        
        $this->setData('exts_all',$available_exts);
        $this->setData('ext_count',count($available_exts));
        
        return $this->display('user.role.exts_set');

    }


    public function getExtDetail(Request $request,$role_id,$ext_slug)
    {
        $this->setData('modal_title','设置权限');
        $this->setData('modal_icon_class','icon ion-ios-switch');
        $this->setData('modal_size','medium');

        $id = intval($role_id);
        $role = Role::where('id','=',$id)->first();

        if(!$role){
            return $this->responseAjax('fail','角色不存在');
        }

        $this->setData('role',$role);
        
        $extService = app('suda_extension')->use($ext_slug);
        $ext = $extService->extension;

        if(!$ext){
            return $this->responseAjax('error','应用异常,请检查应用安装');
        }
        
        $this->setData('ext',$ext);
        
        $permission = [];
        if(array_key_exists($ext_slug,$role->permissions['exts'])){
            $permission = $role->permissions['exts'][$ext_slug];
        }

        $auth_select_values = [];
        if(isset($permission['#auth'])){
            $auth_select_values = $permission['#auth'];
        }
        
        $this->setData('permission',$permission);
        $this->setData('auth_select_values',(array)$auth_select_values);

        $menus = $extService->getMenu();
        $auth_setting = $extService->getAuth();

        if(count($menus)<1 && !$auth_setting){
            return $this->responseAjax('error','此应用无可设置项');
        }

        if($auth_setting){

            foreach($auth_setting as $k=>&$auth){
                
                if(strpos($auth['setting'],'@') === false){

                    if(class_exists($auth['setting'])){
                        $auth_object = new $auth['setting'];
                        $auth['values'] = $auth_object->where([])->get()->toArray();
                    }

                }else{
                    $auths = explode('@',$auth['setting']);
                    if(class_exists($auths[0])){
                        $auth_object = new $auths[0];
                        $auth_method = $auths[1];
                        $auth['values'] = $auth_object->$auth_method();
                    }

                }
                
            }

        }

        //扩展菜单单独设置
        $extend_suda_menus = [];
        if(array_key_exists('suda',$menus)){
            $extend_suda_menus = $menus['suda'];
            unset($menus['suda']);
        }
        //2020-1-10 移除扩展菜单项
        $extend_suda_menus = [];

        $this->setData('menus',$menus);
        $this->setData('auth_setting',$auth_setting);
        $this->setData('extend_suda_menus',$extend_suda_menus);

        return $this->display('user.role.exts_detail');
    }
    
}
