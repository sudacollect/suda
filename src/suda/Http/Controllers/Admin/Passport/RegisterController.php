<?php

namespace Gtd\Suda\Http\Controllers\Admin\Passport;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

use Gtd\Suda\Models\Operate;
use Gtd\Suda\Http\Controllers\Controller;

class RegisterController extends Controller
{
    protected $redirectTo = '/operate';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:operate');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'bail|required|alpha_dash|min:4|max:64',
            'phone' => 'regex:/^1[34578][0-9]{9}$/',
            'email' => 'bail|required|email|max:255|unique:operates',
            'password' => 'bail|required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return Operate::create([
            'username'      => $data['username'],
            'email'         => $data['email'],
            'phone'         => $data['phone'],
            'password'      => bcrypt($data['password']),
            'is_company'    => false,
        ]);
    }
    
    public function showRegistrationForm()
    {
        return view('view_suda::admin.passport.register');
    }
    
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($operators = $this->create($request->all())));

        $this->guard()->login($operators);

        return $this->registered($request, $operators) ?: redirect($this->redirectPath());
    }
    
    protected function guard()
    {
        return Auth::guard('operate');
    }
    
    public function redirectPath()
    {
        return property_exists($this, 'redirectTo') ? '/'.config('sudaconf.admin_path','admin') : '/home';
    }
    
    protected function registered(Request $request,$operators){
        //
    }
}
