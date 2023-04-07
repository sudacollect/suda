<?php

namespace Gtd\Suda\Http\Controllers\Admin\Passport;

use Gtd\Suda\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Response;
use App;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    public function showLinkRequestForm(Request $request)
    {
        if($request->route()->uri()=='en/'.config('sudaconf.admin_path','admin').'/passport/password/reset'){
            App::setLocale('en');
        }
        if($request->route()->uri()=='zh/'.config('sudaconf.admin_path','admin').'/passport/password/reset'){
            App::setLocale('zh_CN');
        }
        
        return Response::json([
            'error' => trans('suda_lang::press.forgetPasswordRequest')
        ], 422);
        
        //return view('auth.passwords.email');
    }
}
