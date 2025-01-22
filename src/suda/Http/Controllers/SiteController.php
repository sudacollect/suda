<?php
/**
 * SiteController.php
 * description
 * date 2017-09-05 10:23:31
 * author suda <dev@panel.cc>
 * @copyright Suda. All Rights Reserved.
 */

namespace Gtd\Suda\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;

use Illuminate\Pagination\Paginator;
use Session;
use View;
use Response;

use Gtd\Suda\Models\Setting;
use Gtd\Suda\Services\SettingService;

class SiteController extends BaseController
{
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public $data = [];
    public $title;
    public $description;
    
    //是否使用框架内的view
    public $view_in_suda = false;
    
    public $extension_view = false;

    protected $breadcrumbs=[];
    
    public function __construct(){
        
        $this->getSettings();
        
        $this->middleware(function (Request $request, $next) {
            
            if(!isset($this->data['sdcore']['settings']['site']['site_status']) || !$this->data['sdcore']['settings']['site']['site_status']){
                return redirect(url('sudarun/status/1001'));
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
    
    public function description($description)
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
    
    public function getSettings()
    {
        $settings = (new SettingService)->data();
        $this->data['sdcore']['settings'] = $settings;
        
        if(array_key_exists('site',$this->data['sdcore']['settings'])){
            if(!array_key_exists('seo',$this->data['sdcore']['settings']['site'])){
                $this->data['sdcore']['settings']['site']['seo'] = ['title'=>'','keywords'=>'','description'=>''];
            }
        }
        
        $this->data['sdcore']['title'] = $this->data['sdcore']['settings']['site_name'];
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


    public function display($view)
    {
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

        if(array_key_exists('logo',$this->data['sdcore']['settings']['site']) && !empty($this->data['sdcore']['settings']['site']['logo'])){
            $this->setData('logo',$this->data['sdcore']['settings']['site']['logo']);
        }else{
            $this->setData('logo',suda_asset('images/site/logo/logo_blue.png'));
        }
        
        $view_source = $view;
        //view_in_suda 优先级高于 extension_view
        if($this->view_in_suda){
            $view = 'view_suda::site/'.$view;
        }elseif($this->extension_view){
            
            $view = 'view_extension::'.ucfirst($this->extension_view).'/resources/views/site/'.$view;
            View::addNamespace('extension', extension_path(ucfirst($this->extension_view).'/resources/views/site'));
            
        }else{
            if(strpos($view,'::')===false){
                $view = 'site/'.$view;
            }
        }
        
        $this->setData('breadcrumbs',$this->breadcrumbs);
        
        return app('theme')->render('site',$view,$view_source,$this->data);
        
        //return View('theme::'.$view)->with($this->data);
    }
    
    
    
    public function errors($msg='Page or data not found.',$code='404')
    {
        $this->title('错误信息');
        
        $this->setData('errcode',$code);
        $this->setData('errmsg',$msg);
        
        return $this->display('error');
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
    
    public function sendFailedResponse($key,$values)
    {
        throw ValidationException::withMessages([
            $key => [$values],
        ]);
    }
    
}
