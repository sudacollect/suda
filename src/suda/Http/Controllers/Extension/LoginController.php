<?php
namespace Gtd\Suda\Http\Controllers\Extension;

use App;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use Gtd\Suda\Http\Controllers\Extension\AdminController;

use Gtd\Suda\Models\Operate;
use Gtd\Suda\Models\Media;

use Illuminate\Session\Store as SessionStore;

use Session;

class LoginController extends AdminController
{
    use AuthenticatesUsers;
    
    protected $redirectTo = 'index';
    protected $username;
    
    public function showLoginForm(SessionStore $sessionStore,Request $request) {
        $this->title(__('suda_lang::auth.UserLogin'));
        $login_name = config('sudaconf.extension_loginname','email');
        
        if($request->has('redirectTo')){
            $sessionStore->put('url.intended',$request->redirectTo);
        }
        
        $this->setData('login_name',$login_name);

        $login_view = 'login';
        
        
        return $this->display('view_suda::extension.passport.'.$login_view);
    }
    
    public function login(SessionStore $sessionStore,Request $request)
    {
        
        $redirect_url = 'index';
        
        //跳转记忆
        $url_intended = $sessionStore->pull('url.intended');
        
        if(!empty($url_intended) && $url_intended!=NULL){
            $this->redirectTo = $url_intended;
        }

        //强制设置的跳转
        if(isset($this->settings['dashboard']['login_page'])){
            $redirect_url = $this->settings['dashboard']['login_page'];
            if(!empty($redirect_url)){
                $this->redirectTo = extadmin_url($redirect_url);
            }
        }
        
        
        //$this->validateLogin($request);
        
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        
        if ($this->attemptLogin($request)) {
            
            if(\Gtd\Suda\Auth\OperateCan::extension($this->user))
            {
                //设置跳转入口
                $this->redirectTo = extadmin_url('entry/extensions');
                return $this->sendLoginResponse($request);
            }else{
                $this->guard()->logout();
                $request->session()->invalidate();

                return $this->sendFailedRoleResponse($request);
            }
            
        }
        
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendFailedRoleResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => ['用户已被禁止登录'],
        ]);
    }
    
    
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }
    
    protected function credentials(Request $request)
    {
        if($this->username()=='phone' && is_numeric($request->get('email'))){
            return ['phone'=>$request->get('email'),'password'=>$request->get('password'),'enable'=>1];
        }
        if($this->username()=='useranme'){
            return ['username'=>$request->get('email'),'password'=>$request->get('password'),'enable'=>1];
        }
        $result = $request->only($this->username(), 'password');
        if($result)
        {
            $result['enable']=1;
        }
        return $result;
    }
    
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        
        // $request->session()->flush();
//         $request->session()->regenerate();
        
        $redirectTo = '';
        if(url()->previous()){
            $redirectTo = '?redirectTo='.urlencode(url()->previous());
        }
        return redirect('/'.config('sudaconf.extension_admin_path','sudaapp').'/passport/login'.$redirectTo);
    }
    
    protected function guard() {
        return Auth::guard('operate');
    }
    
    public function username()
    {
        return config('sudaconf.admin_loginname','email');
    }
}
