<?php

namespace Gtd\Suda\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

use Gtd\Suda\Models\Setting;
    
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected $user;
    //自定义错误
    protected function formatValidationErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }
    
    public function errorAjax(Request $request){
        return view('view_suda::errors.ajax')->render();
    }
    
    public function themeError(){
        return view('view_suda::errors.theme');
    }
    
    public function status(Request $request,$status='404'){
        
        $status_msg = '';
        $status = intval($status);
        switch($status){
            
            case '404':
                $status_msg = '信息没找到';
            break;
            
            case '4043':
                $status_msg = '没有访问权限';
            break;
            
            case '402':
                $status_msg = '没有访问权限';
            break;
            
            case '500':
                $status_msg = '服务器暂停维护中..';
            break;
            
            case '504':
                $status_msg = '服务器暂停维护中..';
            break;
            
            case '1001':
                $status_msg = '系统正在维护, 请稍后回来';
            break;
            
            case '1002':
                $status_msg = '系统暂时关闭, 请稍后回来';
            break;
            
            default:
                $status_msg = '没有访问权限';
            break;
            
        }
        
        
        
        //正常的http的反馈 403,404
        //自定义的反馈
        //1001 系统维护
        
        return view('view_suda::errors.status')->with(['status'=>$status,'status_msg'=>$status_msg]);;
    }
    
    public function getUser($guard=''){
        if (Auth::guard($guard)->check()) {
            $user = Auth::guard($guard)->user();
            return $user;
        }
        return false;
    }
    
}
