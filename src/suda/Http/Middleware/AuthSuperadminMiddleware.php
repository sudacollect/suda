<?php

namespace Gtd\Suda\Http\Middleware;

use Closure;
use Auth;
use Session;

class AuthSuperadminMiddleware
{
    public $user;
    
    public function handle($request, Closure $next) {
        
        $admin_path = config('sudaconf.admin_path','admin');
        
        if(Auth::guard('operate')->check()){
            
            $user = auth('operate')->user();
            
            if($user->superadmin!=1){
                return redirect(admin_url('index'));
            }
            
            return $next($request);
        }else{
            if(!in_array($request->route()->uri(),[$admin_path.'/passport/login','zh_CN/'.$admin_path.'/passport/login','en/'.$admin_path.'/passport/login'])){
                return redirect(admin_url('passport/login'));
            }else{
                return $next($request);
            }
        }
        
        return $next($request);
        
    }
    
}
