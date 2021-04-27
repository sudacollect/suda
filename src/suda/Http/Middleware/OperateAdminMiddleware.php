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
            
            if(!in_array($request->route()->uri(),[$admin_path.'/passport/login','zh/'.$admin_path.'/passport/login','en/'.$admin_path.'/passport/login'])){
                
                return $next($request);
            }
            
            return $next($request);
        }else{
            $extend_uris = [
                $admin_path.'/passport/login',
                'zh/'.$admin_path.'/passport/login',
                'en/'.$admin_path.'/passport/login',
                $admin_path.'/passport/password/reset',
            ];
            if(!in_array($request->route()->uri(),$extend_uris)){
                
                return redirect(admin_url('passport/login?redirectTo='.urlencode(url()->full())));
            }else{
                return $next($request);
            }
        }
        
        return $next($request);
        
    }
    
}
