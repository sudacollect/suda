<?php
/**
 * LoginController.php
 * description
 * date 2016-02-06 18:26:12
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */

namespace Gtd\Suda\Http\Controllers\Admin\Passport;

use App;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use Gtd\Suda\Http\Controllers\AdminController;

use Gtd\Suda\Models\Operate;
use Gtd\Suda\Models\Media;

use Illuminate\Session\Store as SessionStore;

use Session;

class LoginController extends AdminController
{
    use AuthenticatesUsers;
    
    protected $maxAttempts = 5; // Default is 5
    protected $decayMinutes = 1; // Default is 1

    protected $redirectTo = 'index';
    protected $username;
    
    public function showLoginForm(SessionStore $sessionStore,Request $request) {
        $this->title(__('suda_lang::press.dashboard_login'));
        $login_name = config('sudaconf.admin_loginname','email');
        
        if($request->has('redirectTo')){
            $sessionStore->put('url.intended',$request->redirectTo);
        }
        
        $this->setData('login_name',$login_name);

        $login_view = 'login';
        if(array_key_exists('dashboard',$this->settings)) {
            if(array_key_exists('loginbox',$this->settings['dashboard'])) {
                if(isset($this->settings['dashboard']['dashboard_login_logo_select'])){
                    $dashboard_login_logo_select = $this->settings['dashboard']['dashboard_login_logo_select'];
                    $this->setData('login_select',$dashboard_login_logo_select);
                    $this->setData('loginbox',$this->settings['dashboard']['loginbox']);
                    if($dashboard_login_logo_select == 'customize' && isset($this->settings['dashboard']['dashboard_login_logo'])){
                        
                        $login_pic = $this->settings['dashboard']['dashboard_login_logo'];
                        $this->setData('login_pic',$login_pic);

                    }
                }
            }
        }
        
        return $this->display('view_suda::admin.passport.'.$login_view);
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
                $this->redirectTo = admin_url($redirect_url);
            }
        }
        
        
        //$this->validateLogin($request);
        
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        
        if ($this->attemptLogin($request)) {
            
            if(!\Gtd\Suda\Auth\OperateCan::guest(auth('operate')->user()) && !\Gtd\Suda\Auth\OperateCan::extension(auth('operate')->user()))
            {
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
        return redirect('/'.config('sudaconf.admin_path','admin').'/passport/login'.$redirectTo);
    }
    
    protected function guard() {
        return Auth::guard('operate');
    }
    
    public function username()
    {
        return config('sudaconf.admin_loginname','email');
    }
}
