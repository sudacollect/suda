<?php

namespace Gtd\Suda\Http\Middleware;

use Closure;
use Auth;
use Session;

class OperateExtensionMiddleware
{
    public $user;
    
    public function handle($request, Closure $next) {
        
        Auth::shouldUse('operate');
        
        $extadmin_path = config('sudaconf.extension_admin_path','appcenter');
        
        if(Auth::guard('operate')->check()){
            
            $this->user = auth('operate')->user();

            // extension manager go out
            if(!\Gtd\Suda\Auth\OperateCan::extension($this->user))
            {
                Auth::guard('operate')->logout();
                $request->session()->invalidate();
                return redirect(extadmin_url('passport/login'));
            }
            
            Session::flash('user_id',$this->user->id);

            $login_uris = [
                $extadmin_path.'/passport/login',
                'zh_CN/'.$extadmin_path.'/passport/login',
                'en/'.$extadmin_path.'/passport/login'
            ];
            
            if(!in_array($request->route()->uri(),$login_uris)){
                
                return $next($request);
            }
            
            return $next($request);
        }else{
            $login_uris = [
                $extadmin_path.'/passport/login',
                'zh_CN/'.$extadmin_path.'/passport/login',
                'en/'.$extadmin_path.'/passport/login',
                $extadmin_path.'/passport/password/reset',
            ];
            if(!in_array($request->route()->uri(),$login_uris)){
                
                return redirect(extadmin_url('passport/login?redirectTo='.urlencode(url()->full())));
                
            }else{
                return $next($request);
            }
        }
        
        return $next($request);
        
    }
    
}
