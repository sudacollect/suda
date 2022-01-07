<?php

namespace Gtd\Suda\Http\Middleware;

use Closure;
use Auth;
use Session;

class OperateAdminMiddleware
{
    public $user;
    
    public function handle($request, Closure $next) {
        
        Auth::shouldUse('operate');
        
        $admin_path = config('sudaconf.admin_path','admin');
        
        if(Auth::guard('operate')->check()){
            
            $this->user = auth('operate')->user();
            
            Session::flash('user_id',$this->user->id);

            $login_uris = [
                $admin_path.'/passport/login',
                'zh_CN/'.$admin_path.'/passport/login',
                'en/'.$admin_path.'/passport/login'
            ];
            
            if(!in_array($request->route()->uri(),$login_uris)){
                
                return $next($request);
            }
            
            return $next($request);
        }else{
            $login_uris = [
                $admin_path.'/passport/login',
                'zh_CN/'.$admin_path.'/passport/login',
                'en/'.$admin_path.'/passport/login',
                $admin_path.'/passport/password/reset',
            ];
            if(!in_array($request->route()->uri(),$login_uris)){
                
                return redirect(admin_url('passport/login?redirectTo='.urlencode(url()->full())));
                
            }else{
                return $next($request);
            }
        }
        
        return $next($request);
        
    }
    
}
