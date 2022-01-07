<?php
/**
 * OperateController.php
 * description
 * date 2017-11-06 10:23:31
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */
 


namespace Gtd\Suda\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;
use Response;
use Illuminate\Support\Str;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;

use Gtd\Suda\Models\Operate;
use Gtd\Suda\Models\Setting;
use Gtd\Suda\Models\Role;
use Gtd\Suda\Models\Organization;
use Gtd\Suda\Models\Taxonomy;



class OperateController extends DashboardController
{
    
    public $view_in_suda = true;
    
    //$view = ['deleted']
    public function index(Request $request,$view='')
    {
        $this->gate('role.setting_operate',app(Setting::class));
        
        $this->title('用户管理');
        $page_no = 0;
        if($request->get('page')){
            $page_no = $request->get('page');
        }

        $operateObj = new Operate;
        if($view=='deleted'){
            if($this->user->user_role < 6)
            {
                return redirect(admin_url('error'))->withErrors(['errcode'=>404,'errmsg'=>'无操作权限']);
            }
            $operateObj = $operateObj->onlyTrashed();

            $this->setData('deleted',1);
        }
        if($this->user->superadmin==0 && $this->user->user_role<9){
            $auth_slugs = (new Role)->getAuthoritesByLevel($this->user->user_role);
            $operates = $operateObj->where(['superadmin'=>0])->orderBy('id','desc')->whereHas('role',function($query) use ($auth_slugs){
                $query->whereIn('authority',$auth_slugs);
            })->with('categories')->paginate(20,['*'],'page',$page_no);
        }else{
            $operates = $operateObj->where('id','<>',1)->orderBy('id','desc')->with('role')->with('categories')->paginate(20,['*'],'page',$page_no);
        }
        
        
        
        
        $this->setData('operates',$operates);
        
        $display_view = 'user.operate.list';
        
        $this->setMenu('setting','setting_operate');
        return $this->display($display_view);
        
    }
    
    
    public function add(){
        $this->title(__('suda_lang::press.btn.add'));
        $this->getRolesAndOrgs();
        
        $this->gate('role.setting_operate',app(Setting::class));
        
        $this->setData('modal_title',__('suda_lang::press.btn.add'));
        $this->setData('modal_icon_class','ion-person');
        
        $taxonomyObj = new Taxonomy;
        $catgories = $taxonomyObj->where('parent',0)->where('taxonomy','org_category')->get();
        $this->setData('categories',$catgories);
        
        $this->setMenu('setting','setting_operate');
        return $this->display('user.operate.add');
    }
    
    public function edit($id=0){
        $this->title(__('suda_lang::press.btn.edit'));
        $this->getRolesAndOrgs();
        
        $this->gate('role.setting_operate',app(Setting::class));
        
        $id = intval($id);
        $operate = Operate::where('id','=',$id)->first();
        
        if(!$operate){
            return $this->responseAjax('fail','没有用户信息');
        }
        
        $this->setData('modal_title',__('suda_lang::press.btn.edit'));
        $this->setData('modal_icon_class','zly-user-o');

        $this->setData('operate',$operate);
        

        $taxonomyObj = new Taxonomy;
        $catgories = $taxonomyObj->where('parent',0)->where('taxonomy','org_category')->get();
        $this->setData('categories',$catgories);

        //选中的分类
        $orgs = $operate->getTerms('org_category');
        $select_orgs = [];
        if($orgs){
            foreach($orgs as $cate){
                $select_orgs[] = $cate->taxonomies[0]->id;
            }
        }
        $this->setData('orgs',$select_orgs);
        
        $this->setMenu('setting','setting_operate');
        return $this->display('user.operate.edit');
    }
    
    
    public function saveOperate(Request $request){
        
        $error = '';
        $validate_result = $this->operateValidator($request->all(),$error);
        
        if(!$validate_result){
            $url = '';
            return $this->responseAjax('fail',$error,$url);
        }
        
        $phone = $request->phone;
        
        if($phone && !$this->isPhone($phone)){
            $url = '';
            return $this->responseAjax('fail','手机号格式错误',$url);
        }
        
        $id = intval($request->id);
        
        
        $operateModel = new Operate;
        
        $enable = 0;
        
        //只有超级管理员可以设置
        if($this->user->superadmin==1 && $request->has('enable') && $request->enable=='1'){
            $enable = 1;
        }
        
        $authority = '';
        if($request->authority){
            $authority = $request->authority;
        }
        
        if($id){
            $operate_exist = Operate::where('id','<>',$id)->where('username','=',$request->username)->first();

            if($operate_exist){
                $url = '';
                return $this->responseAjax('fail','用户名称重复',$url);
                
            }else{
                
                $operate_data = Operate::where(['id'=>$id])->first();
                if($operate_data->id == $this->user->id){
                    $url = '';
                    return $this->responseAjax('fail','不能编辑自身账号信息',$url);
                }

                $org_id = $request->organization_id;
                $role_id = $request->role_id;

                if($request->has('superadmin') && $request->superadmin==1){
                    $operateModel->superadmin = $request->superadmin;
                    $org_id = 0;
                    $role_id = 0;
                }else{
                    $operateModel->superadmin = 0;
                }
                
                $update_data = [
                    'username'=>$request->username,
                    'phone'=>$request->phone,
                    'email'=>$request->email,
                    'superadmin'=>$operateModel->superadmin,
                    'organization_id'=>$org_id,
                    'role_id'=>$role_id,
                    'roletable'=>"Gtd\\Suda\\Models\\Role",
                    'enable'=>$enable,
                ];
                if($request->password){
                    $update_data['salt'] = Str::random(6);
                    $update_data['password'] = bcrypt($request->password.'zp'.$update_data['salt']);
                    $update_data['remember_token'] = Str::random(60);
                }
                
                $operateModel->where('id',$id)->update($update_data);
                
            }
        }else{
            
            $operate_exist = Operate::where('username','=',$request->username)->first();
            
            if($operate_exist){
                $url = '';
                return $this->responseAjax('fail','用户名称重复',$url);
            }

            $org_id = $request->organization_id;
            $role_id = $request->role_id;

            if($request->has('superadmin') && $request->superadmin==1){
                $operateModel->superadmin = $request->superadmin;
                $org_id = 0;
                $role_id = 0;
            }
            
            $operateModel->username = $request->username;
            $operateModel->phone = $request->phone;
            $operateModel->email = $request->email;
            $operateModel->organization_id = $org_id;
            $operateModel->role_id = $role_id;
            $operateModel->roletable = "Gtd\\Suda\\Models\\Role";
            $operateModel->remember_token = Str::random(60);
            $operateModel->salt = Str::random(6);
            $operateModel->password = bcrypt($request->password.'zp'.$operateModel->salt);
            $operateModel->is_company = '0';
            $operateModel->enable = $enable;
            
            $operateModel->save();
            
            $id = $operateModel->id;
        }

        $operate = Operate::where(['id'=>$id])->first();

        //保存文章分类
        $operate->removeAllTerms('org_category');
        if($request->category && !empty($request->category)){
            $cate_ids = $request->category;
            //处理文章分类，确保不新增分类
            foreach($cate_ids as $cate_id){
                $cate = Taxonomy::where('id',$cate_id)->with('term')->first();
                if($cate){
                    $operate->addTerm($cate->term->name,'org_category',$cate->parent,$cate->sort);
                }
            }
        }
        
        
        $url = 'manage/operates';
        return $this->responseAjax('success','保存成功','self.refresh');
    }
    
    protected function operateValidator(array $data,&$error='')
    {

        $roles_add = [
            'organization_id'=>'required',
            'role_id'=>'required',
            'username' => 'required|unique:operates|min:6|max:64',
            'password'=>'required|min:6',
            'email'=>'required|email|unique:operates',
            'phone'=>'required|unique:operates',
        ];

        if(array_key_exists('superadmin',$data) && $data['superadmin']==1){
            unset($roles_add['organization_id']);
            unset($roles_add['role_id']);
        }
        
        
        if(array_key_exists('id',$data)){
            $roles = [
                'organization_id'=>'required',
                'role_id'=>'required',
                'username' => 'required|min:6|max:64|unique:operates,id,'.$data['id'],
                'email'=>'required|email|unique:operates,id,'.$data['id'],
                'phone'=>'required|unique:operates,id,'.$data['id'],
            ];
            if(array_key_exists('superadmin',$data) && $data['superadmin']==1){
                unset($roles['organization_id']);
                unset($roles['role_id']);
            }
        }else{
            $roles = $roles_add;
        }
        
        $messages = [
            'organization_id.required'  =>'请选择部门',
            'role_id.required'          =>'请选择角色',
            'username.required'         =>'请输入用户名称',
            'username.unique'           =>'用户名称重复',
            'email.required'            =>'请输入邮箱',
            'email.unique'              =>'邮箱重复，请重新输入',
            'email.email'               =>'邮箱格式错误',
            'phone.required'            =>'请输入手机号',
            'phone.unique'              =>'手机号必须唯一',
            'password.required'         =>'请输入密码'
        ];

        if(array_key_exists('superadmin',$data) && $data['superadmin']==1){
            unset($messages['organization_id.required']);
            unset($messages['role_id.required']);
        }
        
        $ajax_result = $this->ajaxValidator($data, $roles,$messages,$response_msg);
        
        
        if(!$ajax_result){
            $error = $response_msg;
            return false;
        }
        return true;
    }

    public function restore(Request $request){
        if($request->id && !empty($request->id)){

            if($this->user->id==$request->id){
                return $this->responseAjax('fail','无法对自身账号操作');
            }

            if($this->user->superadmin < 1){
                return $this->responseAjax('fail','不能操作超级管理账号');
            }
            
            $operate = Operate::withTrashed()->where('id',$request->id)->first();
            
            if($operate){

                Operate::where('id',$request->id)->restore();
                $url = 'manage/operates/deleted';
                return $this->responseAjax('success','恢复成功',$url);
                
            }else{
                $url = 'manage/operates';
                return $this->responseAjax('error','数据不存在,请重试',$url);
            }
            
            
        }else{
            $url = 'manage/operates';
            return $this->responseAjax('error','数据不存在,请重试',$url);
        }
    }
    
    public function deleteOperate(Request $request,$id,$force=false){
        
        if($request->id && !empty($request->id)){

            if($this->user->id==$request->id){
                return $this->responseAjax('fail','无法删除自身账号');
            }
            
            $operate = Operate::where('id',$request->id)->withTrashed()->first();
            
            if($operate){

                if($operate->superadmin==1){
                    if($this->user->superadmin < 1){
                        return $this->responseAjax('fail','不能删除超级管理账号');
                    }
                    if(Operate::where('superadmin',1)->withTrashed()->count()<2){
                        return $this->responseAjax('fail','不能删除超级管理账号');
                    }
                }

                if($force=='force'){
                    //forceDelete
                    Operate::where('id',$request->id)->forceDelete();
                }else{
                    Operate::where('id',$request->id)->delete();
                }
                
                $url = '';
                return $this->responseAjax('success','删除成功',$url);
                
            }else{
                $url = 'manage/operates';
                return $this->responseAjax('error','数据不存在,请重试',$url);
            }
            
            
        }else{
            $url = 'manage/operates';
            return $this->responseAjax('error','数据不存在,请重试',$url);
        }
        
    }
    
    
    public function getRolesAndOrgs(){

        if($this->user->superadmin == 0 && $this->user->user_role < 9){
            $auth_slugs = (new Role)->getAuthoritesByLevel($this->user->user_role);
            $roles = Role::whereIn('authority',$auth_slugs)->where(['disable'=>'0'])->get();
        }else{
            $roles = Role::where(['disable'=>'0'])->get();
        }
        
        $this->setData('roles',$roles);
        $orgs = Organization::where(['disable'=>'0'])->get();
        $this->setData('orgs',$orgs);
    }
    
    public function isPhone($string){
        return !!preg_match('/^1[3|4|5|7|8]\d{9}$/', $string);
    }
}
