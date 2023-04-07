<?php
namespace Gtd\Suda\Http\Controllers\Extension;

use Gtd\Suda\Http\Controllers\Extension\ExtAdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Validator;
use Response;

use Session;
use Redirect;
use Gtd\Suda\Models\Role;
use Gtd\Suda\Models\Organization;

class DashboardController extends ExtAdminController
{
    
    public $user;
    public $_definition = [];
    public $response_msg = '';
    
    //常规自定义参数
    
    public $s_tag = 'post_tag';

    //设置当前view所在目录
    public $view_in_suda = false;
    
    //设置当前菜单组
    public $menu_group = 'suda';
    
    //应用可以设置独立的菜单
    public $single_extension_menu = false;
    
    public function getOperate(){
        $this->user = auth('operate')->user();
        return true;
    }
    
    public function getUser(){
        $this->user = Auth::user();
    }
    
    public function applicationInfo(){
        $sysinfo = \Gtd\Suda\Sudacore::sysInfo();
        $this->setData('application',['name'=>$sysinfo['name'],'version'=>$sysinfo['version']]);
    }
    
    
    
    public function errorAjax(Request $request){
        return view('view_app::admin.errorajax');
    }
    
    public function validator($data,$roles,$messages)
    {
        
        if($data && $roles && $messages && is_array($roles)){
            return Validator::make($data, $roles,$messages);
        }
    }
    
    public function ajaxValidator($data,$roles,$messages=array(),&$response_msg=''){
        
        $default_messages = [];
        
        $messages = array_merge($default_messages,$messages);
        
        $validator = $this->validator($data,$roles,$messages);
        
        if (!$validator->passes()) {
            $msgs = $validator->messages();
            foreach ($msgs->all() as $msg) {
                $response_msg .= $msg . '</br>';
            }
            $response_type = false;
        }else{
            $response_type = true;
        }
        return $response_type;
    }
    
    // ajax表单提交返回方法
    public function responseAjax($type='fail',$msg='',$url='',$data=[]){
        // ajax返回请求
        $type=='failed'?$type='fail':'';
        
        if($url){
            if(substr($url,0,4)!='http'){
                $url = in_array($url,['ajax.close','self.refresh'])?$url:extadmin_url($url);
            }
        }else{
            $url = '';
        }
        $arr = array(
            'response_type' => $type,
            'response_msg' => $msg,
            'response_url' => $url
        );
        
        if($data){
            $arr = array_merge($arr,$data);
        }
        
        $code=422;
        if($type=='success' || $type=='info' || $type=='warning' || $type=='danger'){
            $code=200;
        }
        
        return Response::json($arr, $code);
    }
    
    public function ajaxEnd($type='fail',$msg='',$url='',$data=[])
    {
        return $this->responseAjax($type,$msg,$url,$data);
    }
    
    public function errorPage(Request $request)
    {
        $this->title('错误');
        
        $code = '404';
        $msg = '';
        if($request->session()->has('errors')){

            $errors = $request->session()->get('errors');
            $code = $errors->first('errcode');
            $msg = $errors->first('errmsg');
        }

        if($request->get('code')){
            $code = $request->get('code');
            $msg = $request->get('msg');
        }

        $code = $code?$code:'404';
        $msg = $msg?$msg:'相关信息没找到';

        $this->setData('code',$code);
        $this->setData('msg',$msg);

        return $this->display('error');
    }

    //禁止页面
    public function forbidden(Request $request)
    {
        exit('禁止登录');
    }
    
    //跳转错误页面
    public function redirect($errcode='404',$errmsg='没找到信息')
    {
        $resp = redirect(extadmin_url('error'))->withErrors(['errcode'=>$errcode,'errmsg'=>$errmsg]);
        \Session::driver()->save();
        $resp->send();
        exit();
    }

    //dispatch错误信息
    public function dispatchError($code,$msg='')
    {
        $req = \Request::create('admin/error', 'GET',['code'=>$code,'msg'=>$msg]);
        //return \Route::dispatch($req);
        \Request::replace($req->input());
        return \Route::dispatch($req)->getContent();
    }
    
    public function setMenu($menu_item,$menu_link=''){
        
        
        // $current_menu[$this->menu_group] = [
        //     $menu_item=>$menu_link
        // ];
        
        //重新定义当前菜单,忽略菜单组
        if(!$menu_link)
        {
            $items = explode('.',$menu_item);
            $current_menu[$items[0]] = isset($items[1])?$items[1]:'';
        }else{
            $current_menu = [];
            $current_menu[$menu_item] = $menu_link;
        }
        
        
        $this->setData('current_menu',$current_menu);
    }
    
    public function currentMenu($menu = []){
        $this->setData('current_menu',$menu);
    }
    
    public function display($view){
        $this->applicationInfo();
        
        $this->setData('soperate',$this->user);
        
        $sidemenu = Cache::store(config('sudaconf.admin_cache','file'))->get('sidemenu#'.$this->user->id);
        
        if($sidemenu && array_key_exists('style',$sidemenu)){
            $sidemenu_style = $sidemenu['style'];
        }else{
            //默认展开菜单
            $sidemenu_style = 'flat';
        }
        
        $this->setData('sidemenu_style',$sidemenu_style);
        
        if(strpos($view,'::')!==false){
            //
        }else{
            if($this->view_in_suda){
                $view = 'view_suda::admin/'.$view;
            }else{
                $view = 'admin/'.$view;
            }
        }
        
        
        if(!$this->data('current_menu')){
            $this->setData('current_menu',[]);
        }
        
        if(!$this->data('editor_height')){
            $this->setData('editor_height',500);
        }
        
        
        return parent::display($view);
    }
    
}
