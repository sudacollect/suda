<?php
/**
 * UserController.php
 * description
 * date 2017-11-06 10:23:31
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */
 


namespace Gtd\Suda\Http\Controllers\Admin\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;
use Response;
use Illuminate\Support\Str;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;

use Gtd\Suda\Models\User;
use Gtd\Suda\Models\Setting;
use Gtd\Suda\Traits\SettingTrait;


class UserController extends DashboardController
{
    use SettingTrait;
    public $view_in_suda = true;
    
    public function index(Request $request,$view='list')
    {
        $this->gate('user.user_list',app(Setting::class));
        
        $this->title(__('suda_lang::press.menu_items.user'));
        
        $page_size = 20;
        $page_no = 0;
        if($request->get('page')){
            $page_no = $request->get('page');
        }    
        
        $users = User::where([])->orderBy('id','desc')->paginate($page_size,['*'],'page',$page_no);
        
        $this->setData('users',$users);
        
        $display_view = 'user.user.list';
        
        $this->setMenu('user','user_list');
        return $this->display($display_view);
        
    }
    
    
    //#=== CURD start ===
    
    public function add()
    {
        $this->title(__('suda_lang::press.btn.add'));
        
        $this->gate('user.user_list',app(Setting::class));
        
        $this->setData('modal_title',__('suda_lang::press.btn.add'));
        $this->setData('modal_icon_class','ion-person-outline');
        
        
        $this->setMenu('user','user_list');
        return $this->display('user.user.add');
    }
    
    public function edit($id=0)
    {
        $this->title(__('suda_lang::press.btn.edit'));
        
        $this->gate('user.user_list',app(Setting::class));
        
        $id = intval($id);
        $user = User::where('id','=',$id)->first();
        
        if(!$user){
            return $this->responseAjax('fail','没有用户信息');
        }
        
        
        $this->setData('modal_title',__('suda_lang::press.btn.edit'));
        $this->setData('modal_icon_class','ion-person-outline');
        
        $this->setData('user',$user);
        
        
        $this->setMenu('user','user_list');
        return $this->display('user.user.edit');
    }
    
    
    public function saveUser(Request $request)
    {
        
        $error = '';
        $validate_result = $this->userValidator($request->all(),$error);
        
        if(!$validate_result){
            $url = '';
            return $this->responseAjax('fail',$error,$url);
        }
        
        $id = intval($request->id);
        $user_exist = User::where('id','<>',$id)->where('name','=',$request->name)->first();
        
        $userModel = new User;
        
        $password_link = config('sudaconf.password_link','zp');

        if($id){
            if($user_exist){
                $url = '';
                return $this->responseAjax('fail','用户名称重复',$url);
                
            }else{
                
                $user_exist = User::where(['id'=>$id])->first();
                
                $update_data = [
                    'name'=>$request->name,
                    'email'=>$request->email,
                ];
                if($request->password){
                    $update_data['password'] = Hash::make($request->password.$password_link);
                    $update_data['remember_token'] = Str::random(60);
                }
                
                $userModel->where('id',$id)->update($update_data);
                
            }
        }else{
            
            $user_exist = User::where('name','=',$request->name)->first();
            
            if($user_exist){
                $url = '';
                return $this->responseAjax('fail','用户名称重复',$url);
            }
            
            $userModel->name = $request->name;
            $userModel->email = $request->email;

            
            $userModel->password = Hash::make($request->password.$password_link);
            $userModel->remember_token = Str::random(60);
            
            $userModel->save();
            
        }
        
        
        $url = 'user/list';
        return $this->responseAjax('success',__('suda_lang::press.msg.success'),$url);
    }
    
    protected function userValidator(array $data,&$error='')
    {
        $roles_add = [
            'name' => 'required|unique:users|min:6|max:64',
            'password'=>'required|min:6',
            'email'=>'required|email|unique:users',
        ];
        
        
        if(array_key_exists('id',$data)){
            $roles = [
                'name' => 'required|min:6|max:64|unique:users,id,'.$data['id'],
                'email'=>'required|email|unique:users,id,'.$data['id'],
            ];
        }else{
            $roles = $roles_add;
        }
        
        $messages = [
            'name.required'         =>'请填写用户名称',
            'name.unique'           =>'用户名称重复',
            'email.required'            =>'请填写邮箱',
            'email.unique'              =>'邮箱重复',
            'email.email'               =>'邮箱格式错误',
            'password.required'         =>'请填写密码'
        ];
        
        $ajax_result = $this->ajaxValidator($data, $roles,$messages,$response_msg);
        
        
        if(!$ajax_result){
            $error = $response_msg;
            return false;
        }
        return true;
    }
    
    public function deleteUser(Request $request)
    {
        
        if($request->id && !empty($request->id)){
            
            $screen = User::where('id',$request->id)->first();
            
            if($screen){
                
                User::where('id',$request->id)->delete();
                $url = '';
                return $this->responseAjax('success','删除成功',$url);
                
            }else{
                $url = 'user/list';
                return $this->responseAjax('error','数据不存在,请重试',$url);
            }
            
            
        }else{
            $url = 'user/list';
            return $this->responseAjax('error','数据不存在,请重试',$url);
        }
        
    }
    
    //#=== CURD end ===
    
    //注册规则
    public function ruleRegister(){
        $this->gate('user.user_list',app(Setting::class));
        
        $this->title('用户注册设置');
        
        
        $settings = $this->getSettingByKey('user_rule','user');
        
        $this->setData('settings',$settings);
        
        
        //菜单的设置项
        $this->setMenu('user','user_register_rule');
        
        return $this->display('user.user.rule_register');
    }
    
    // 保存提交用户规则
    public function ruleSave(Request $request,$rule_type)
    {
        $rules = [];
        if($request->register){
            $rules['register'] = $request->register;
        }
        $this->saveSettingByKey('user_rule','user',$register,'serialize');
        return $this->responseAjax('success',__('suda_lang::press.msg.success'),'self.refresh');
    }
    
    public function isPhone($string){
        return !!preg_match('/^1[3|4|5|7|8]\d{9}$/', $string);
    }
}
