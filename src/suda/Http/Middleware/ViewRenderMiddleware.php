<?php

namespace Gtd\Suda\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ViewRenderMiddleware
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
        $except_prefix = [
            config_admin_path().'/*',
            'sdone/*',
            'api/*',
            'arrilot/*'
        ];
        
        $config_excepts = config('sudaconf.except_render',[]);
        $this->except = array_merge($except_prefix,$config_excepts);
        
        //判断当前链接是不是应该被except
        $except_do = $this->isExceptConfig($request);
        
        
        return $next($request);
    }
    
    
    protected function isExceptConfig($request){

        foreach ($this->except as $except) {
                
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
