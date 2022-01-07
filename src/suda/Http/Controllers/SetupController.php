<?php

namespace Gtd\Suda\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Gtd\Suda\Http\Controllers\SiteController;
use Gtd\Suda\Models\Setting;
use Gtd\Suda\Certificate;

class SetupController extends SiteController{
    
    public $view_in_suda = true;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(Request $request)
    {
        
        $err = '';
        if(Certificate::isLicensed($err) && !$request->session()->get('success'))
        {
            
            return redirect()->to(url('/'));
            
        }
        
        //判断是否新增自定义首页
        $this->title('Welcome');
        return $this->display('setup.license');
    }
    
    
    public function setLicense(Request $request)
    {
        
        //$this->sendFailedResponse('serialnumber','提交错误');
        
        if(!$request->has('serialnumber')){
            $this->sendFailedResponse('serialnumber','请输入授权码');
        }
        
        $msg = '';
        $result = Certificate::checkLicense($request->serialnumber,$msg);
        
        if(!$result){
            $this->sendFailedResponse('serialnumber',$msg);
        }else{
            
            return redirect()->to(url('sdone/setup/license'))->with(['success'=>'success']);
            
        }
        
        
    }
}