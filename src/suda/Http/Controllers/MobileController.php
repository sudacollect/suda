<?php
/**
 * SiteController.php
 * description
 * date 2017-09-05 10:23:31
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */

namespace Gtd\Suda\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Pagination\Paginator;
use Session;
use View;
use Response;

use Gtd\Suda\Models\Setting;

class MobileController extends BaseController
{
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public $data = [];
    
    public $title;
    public $keyword;
    public $description;
    
    public $view_in_suda = false;
    public $extension_view = false;
    
    public function __construct(){
        
        $this->getSettings();
        $this->middleware(function (Request $request, $next) {
            
            if($this->data['sdcore']['settings']['site_close']>0){
                return redirect(url('sdone/status/1001'));
            }
            
            return $next($request);
        
        });
    }
    
    //自定义错误
    protected function formatValidationErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }
    
    public function title($title)
    {
        $this->title = $title;
        $this->data['sdcore']['title'] = $title?$title:'';
    }
    
    public function keywords($keyword,$replace=false)
    {
        $this->keyword = $keyword;
        $this->data['sdcore']['keywords'] = $keyword?$keyword:'';
    }
    
    public function description($description,$replace=false)
    {
        $this->description = $description;
        $this->data['sdcore']['description'] = $description?$description:'';
    }
    
    public function data($key)
    {
        if(array_key_exists($key,$this->data)){
            return $this->data[$key];
        }
        return false;
    }
    
    public function setData($key,$value)
    {
        $this->data[$key] = $value;
    }
    
    public function getSettings(){
        $settings = Setting::getSettings();
        $this->data['sdcore']['settings'] = $settings;
        if(array_key_exists('seo',$this->data['sdcore']['settings']['site'])){
            $this->data['sdcore']['settings']['site']['seo'] = unserialize($this->data['sdcore']['settings']['site']['seo']);
        }else{
            $this->data['sdcore']['settings']['site']['seo'] = ['title'=>'','keywords'=>'','description'=>''];
        }
        $this->data['sdcore']['title'] = $this->data['sdcore']['settings']['site_name'];
    }
    
    public function display($view){
        $session = app('session');
        
        $this->_definition['url'] = url('/');
        //$this->_definition['csrf_token'] = $session->getToken();
        
        $this->setData('_definition',$this->_definition);
        
        if(!isset($this->data['sdcore']['title'])){
            if(array_key_exists('site',$this->data['sdcore']['settings'])){
                $this->title($this->data['sdcore']['settings']['site']['seo']['title']);
            }else{
                $this->data['sdcore']['title'] = '';
            }
            
        }
        if(!isset($this->data['sdcore']['keywords'])){
            if(array_key_exists('site',$this->data['sdcore']['settings'])){
                $this->keywords($this->data['sdcore']['settings']['site']['seo']['keywords']);
            }else{
                $this->data['sdcore']['keywords']='';
            }
            
        }
        if(!isset($this->data['sdcore']['description'])){
            if(array_key_exists('site',$this->data['sdcore']['settings'])){
                $this->description($this->data['sdcore']['settings']['site']['seo']['description']);
            }else{
                $this->data['sdcore']['description']='';
            }
            
        }
        

        //favicon

        if(array_key_exists('favicon',$this->data['sdcore']['settings']['site'])){
            $this->setData('favicon',$this->data['sdcore']['settings']['site']['favicon']);
        }else{
            $this->setData('favicon',suda_asset('images/logo/favicon.png'));
        }

        //logo

        if(array_key_exists('logo',$this->data['sdcore']['settings']['site'])){
            $this->setData('logo',$this->data['sdcore']['settings']['site']['logo']);
        }else{
            $this->setData('logo',suda_asset('images/site/logo/logo_blue.png'));
        }
        
        $view_source = $view;
        
        if($this->view_in_suda){
            $view = 'view_suda::mobile/'.$view;
        }elseif($this->extension_view){
            
            $view = 'view_extension::'.ucfirst($this->extension_view).'/resources/views/mobile/'.$view;
            View::addNamespace('extension', extension_path(ucfirst($this->extension_view).'/resources/views/mobile'));
            
        }else{
            
            if(strpos($view,'view_suda::')===false){
                $view = 'mobile/'.$view;
            }
            
        }
        
        return app('theme')->render('mobile',$view,$view_source,$this->data);
        
        //return View('theme::'.$view)->with($this->data);
    }
    
    
    
    public function errors($msg='相关信息没找到',$code='404'){
        
        $this->title('错误信息');
        
        $this->setData('errcode',$code);
        $this->setData('errmsg',$msg);
        
        return $this->display('view_suda::site.error');
        
    }
    
    
    // ajax表单提交返回方法
    public function responseAjax($type='fail',$msg='',$url='',$data=[])
    {
        // ajax返回请求
        if($url){
            $url = url($url);
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
        if($type=='success'){
            $code=200;
        }
        
        return Response::json($arr, $code);
    }
    
}
