<?php
/**
 * AdminController.php
 * description
 * date 2017-11-06 10:23:31
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */

namespace Gtd\Suda\Http\Controllers;

use App;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use View;
use Session;
use Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Route;
use Gtd\Suda\Models\Role;
use Gtd\Suda\Models\Organization;
use Gtd\Suda\Models\Setting;

use Gtd\Suda\Services\SettingService;

class AdminController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public $locale = 'en';
    public $data = [];
    public $title;
    public $description;
    public $objectModel;
    public $admin_path;
    protected $settings;
    protected $user_theme='';
    protected $breadcrumbs=[];
    
    public function __construct() {
        $this->admin_path = config('sudaconf.admin_path','admin');
        $this->getSettings();
        
        $this->middleware(function (Request $request, $next) {
            
            $admin_path = config('sudaconf.admin_path','admin');
            
            if($request->route()->getPrefix() == 'zh_CN/'.$admin_path || $request->route()->getPrefix() == $admin_path){
                app('config')->set('app.name', '速搭');
            }
            if($request->route()->getPrefix() == 'en/'.$admin_path){
                app('config')->set('app.name', 'Suda');
            }

            if (session()->has('locale')) {
                App::setLocale(session()->get('locale'));
            }
            
            $this->locale = App::getLocale();
            
            //$this->middleware('guest:operate', ['except' => $admin_path.'/logout']);

            $except_redirects = [
                config_admin_path().'/passport/login',
                config_admin_path().'/passport/register',
                config_admin_path().'/passport/password/reset',
                'zh/'.config_admin_path().'/passport/login',
                'en/'.config_admin_path().'/passport/login',
                'zh/'.config_admin_path().'/passport/register',
                'en/'.config_admin_path().'/passport/register',
                'zh/'.config_admin_path().'/passport/password/reset',
                'en/'.config_admin_path().'/passport/password/reset',
            ];

            //判断当前链接是不是应该被except
            $is_login_url = $this->isExceptConfig($request,$except_redirects);
            
            if(Auth::guard('operate')->check()){
                
                $this->user = auth('operate')->user();
                
                if(\Gtd\Suda\Auth\OperateCan::guest($this->user))
                {
                    Auth::guard('operate')->logout();
                    $request->session()->invalidate();
                    return redirect(url('404'));
                }

                $this->checkUserPermision();
                
                if(!$is_login_url){
                    return $next($request);
                }
                
                return redirect()->to($admin_path);
                
            }else{

                if(!$is_login_url){
                    return redirect(admin_url('passport/login'));
                }else{
                    return $next($request);
                }
            }
            
        });
    }
    
    public function checkUserPermision(){
        //预留函数
        if(array_key_exists('style',$this->user->permission)){
            if(array_key_exists('dashboard',$this->user->permission['style'])){
                $this->user_theme = $this->user->permission['style']['dashboard'];
            }
        }
    }
    
    public function gate($action,$model,$isAjax=false)
    {
        if(!Gate::allows($action,$model)){
            if($isAjax)
            {
                $arr = array(
                    'response_type' => 'warning',
                    'response_msg' => '没有操作权限',
                    'response_url' => ''
                );

                $resp = response()->json($arr, 422);
                $resp->send();
                exit();
            }else{
                $resp = redirect(admin_url('error'))->withErrors(['errcode'=>403,'errmsg'=>'没有权限执行操作']);
                \Session::driver()->save();
                $resp->send();
                exit();
            }
        }
    }
    
    //自定义错误
    protected function formatValidationErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }
    
    public function title($title='')
    {
        $this->title = $title;
        $this->data['sdcore']['title'] = $title?$title:'';
    }
    
    public function description($description)
    {
        $this->description = $description;
        $this->data['sdcore']['description'] = $description?$description:'';
    }
    
    public function data($key, $default_value=null)
    {
        if(array_key_exists($key,$this->data)){
            return $this->data[$key];
        }
        if(isset($default_value))
        {
            return $default_value;
        }
        return false;
    }
    
    public function setData($key,$value)
    {
        $this->data[$key] = $value;
    }
    
    public function getSettings()
    {
        if(!$this->settings){
            $settings = (new SettingService)->data();
            $this->settings = $settings;
            $this->data['sdcore']['settings'] = $settings;
        }else{
            $this->data['sdcore']['settings'] = $this->settings;
        }
    }

    //自定义面包屑
    public function breadParent($title,$path){
        if(isset($this->breadcrumbs[$title])){
            return false;
        }
        $this->breadcrumbs[$title] = $path;
    }

    public function breadSet($title,$path=""){
        if(isset($this->breadcrumbs[$title])){
            return false;
        }
        $this->breadcrumbs = array_merge($this->breadcrumbs,[$title=>$path]);
    }
    
    //获取自定义扩展导航
    protected function getNavi(){

        // 允许增加自定义菜单
        $custom_navi = $this->data('custom_navi',[]);
        
        $get_navi = config('suda_custom.navi',[]);
        $custom_navi = array_merge($get_navi, $custom_navi);
        
        $this->setData('custom_navi',$custom_navi);
        
    }
    
    public function display($view)
    {
        //$session = app('session');
        //$this->_definition['csrf_token'] = $session->getToken();
        
        $this->_definition['url'] = url('/');
        
        
        $this->setData('_definition',$this->_definition);
        
        if(!array_key_exists('sdcore',$this->data)){
            if(!isset($this->data['sdcore']['title'])){
                //$this->title(config('app.name'));
                $this->title();
            }
            if(!isset($this->data['sdcore']['description'])){
                $this->description(config('app.name'));
            }
        }
        
        $this->getSettings();
        $this->getNavi();
        
        //#TODO 实现自动化的breadcrumbs
        $this->setData('breadcrumbs',$this->breadcrumbs);

        $view_source = $view;
        
        return app('theme')->render('admin',$view,$view_source,$this->data,$this->user_theme);
    }

    protected function isExceptConfig($request,$excepts){

        foreach ($excepts as $except) {
                
            if ($except !== '/') {
                $except = trim($except, '/');
            }
            
            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;

    }

    //是否允许APP定义
    public function availableApp($app)
    {
        $apps = config('sudaconf.apps',['site','admin']);

        if(in_array($app,$apps)){
            return true;
        }

        return false;
    }
}
