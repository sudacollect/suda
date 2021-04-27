<?php

namespace Gtd\Suda\Http\Middleware;

use Closure;
use Auth;
use Session;
use Gtd\Suda\Certificate;

class CertificateMiddleware
{
    private $license;
    
    public function handle($request, Closure $next) {
        
        //$admin_path = config_admin_path();
        
        $except_redirects = [
            config_admin_path().'/*',
        ];

        $except_do = $this->isExceptConfig($request,$except_redirects);

        if($except_do && !Certificate::isLicensed($err)){
            
            if($request->route()->uri()!='sdone/setup/license'){
                return redirect()->to('sdone/setup/license')->with(['err'=>$err]);
                exit('Suda Licensed Failed.');
            }
        }
        
        return $next($request);
        
    }

    protected function isExceptConfig($request,$excepts){

        foreach ($excepts as $except) {
                
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
