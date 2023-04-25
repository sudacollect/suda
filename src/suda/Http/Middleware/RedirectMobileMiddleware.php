<?php

namespace Gtd\Suda\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectMobileMiddleware
{

    protected $except = [];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        //忽略移动端的自动判断
        //return $next($request);

        $excepts = [
            config_admin_path().'/*',
            'sudarun/*',
            'api/*',
            'arrilot/*'
        ];
        
        $config_excepts = config('sudaconf.except_mobile',[]);
        $this->excepts = array_merge($excepts,$config_excepts);
        
        //check except
        $is_except = $this->isExcept($request);
        
        if(config('sudaconf.auto_mobile',false) && !$is_except){
            $action = $request->route()->getAction();
            
            //GET 方式下进行路由判断，假定POST无法判断
            //#risk. 如果是ajax get，可能导致误判.
            //#TODO 给 ajax 强制增加请求参数，在这里可以过滤
            
            $mobile_profix = config('sudaconf.mobile_profix','mobile');
            
            if($request->method()=="GET" && ($namespace = $action['namespace']) && !is_object($action['uses'])){
            
                $route_type = strtolower(basename(str_replace('\\','/',$namespace)));
                $route_basename = basename(str_replace('\\','/',$action['uses']));
                $parameters = $request->route()->parameters();
            
                //$uri = $request->route()->uri();
            
                $request_uri = $request->getRequestUri();
                
                if(isMobile() && $route_type!=$mobile_profix){
                    if(strstr($request_uri,'/')){
                        return redirect()->to($mobile_profix.$request_uri);
                    }else{
                        return redirect()->to($mobile_profix.'/'.$request_uri);
                    }
                
                }
            
                if(!isMobile() && $route_type == $mobile_profix){
                    //return redirect()->action("Site\\".$route_basename,$parameters);
                    if(strstr($request_uri,'/')){
                        $request_uri = str_replace('/'.$mobile_profix,'',$request_uri);
                    }else{
                        $request_uri = str_replace($mobile_profix,'',$request_uri);
                    }
                
                
                    return redirect()->to($request_uri);
                }
            }
        }

        return $next($request);
    }

    protected function isExcept($request){

        foreach ($this->excepts as $except) {
                
            if ($except !== '/') {
                $except = trim($except, '/');
            }
            
            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;

    }
}
