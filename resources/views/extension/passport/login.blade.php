@extends('view_app::layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ suda_asset('css/dashlogin.css') }}">
@endpush

@section('content')
<div class="container-fluid container-login">
    <div class="row">
        
        <div class="col-12 px-0 py-0 login-box">
            <div class="card border-0">
                <div class="card-body px-0 py-0" style="position:relative;">
                    <div class="row no-gutters min-vh-100">
                        
                        <div class="col-sm-3 offset-sm-1 dash-login-box px-3">
                            <h4 class="card-title">
                                {{ __('suda_lang::press.passport.extension') }}
                            </h4>
                            <form class="suda-login-form" role="form" method="POST" action="{{ extadmin_url('passport/login') }}">
                                @csrf
                                
                                @if($login_name=='email')
                                
                                <div class="mb-3">

                                    <div class="input-group ">
                                        <div class="input-group-text"><i class="icon ion-person"></i></div>
                                        

                                        <input id="email" type="text" class="form-control font-weight-bold {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="{{__('suda_lang::press.emailorphone')}}">
                                        @if ($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                        @endif
                                     </div>
                                </div>
                                @elseif($login_name=='phone')
                                
                                <div class="mb-3">
                                    
                                    <div class="input-group ">
                                        <div class="input-group-text"><i class="icon ion-person"></i></div>
                                        <input id="phone" type="text" class="form-control font-weight-bold {{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}" required autofocus placeholder="{{__('suda_lang::press.phone')}}">
                                        @if ($errors->has('phone'))
                                        <div class="invalid-feedback">
                                                {{ $errors->first('phone') }}
                                        </div>
                                        @endif
                                    </div>
                                
                                </div>
                                
                                @elseif($login_name=='username')
                                
                                <div class="mb-3">
                                    <div class="input-group ">
                                        
                                        <div class="input-group-text"><i class="icon ion-person"></i></div>
                                        
                                        <input id="username" type="text" class="form-control font-weight-bold {{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus placeholder="{{__('suda_lang::press.username')}}">
                                        @if ($errors->has('username'))
                                        <div class="invalid-feedback">
                                                {{ $errors->first('username') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                @endif
        
                                <div class="mb-3">
                                    
                                    
                                        <div class="input-group ">
                                            <div class="input-group-text"><i class="icon ion-key"></i></div>
                                            <input id="password" type="password" class="form-control font-weight-bold {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="{{__('suda_lang::press.password')}}">
                                            @if ($errors->has('password'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('password') }}
                                            </div>
                                            @endif
                                        </div>
                                        
                                        
                                    
                                </div>
        
                                <div class="mb-3">
                                    
                                    <div class="form-check text-right">
                                        <input class="form-check-input" type="checkbox" value="1" name="remember" id="remember">
                                        <label class="form-check-label" for="remember">
                                            {{ __('suda_lang::press.remember_login') }}
                                        </label>
                                    </div>
                                    
                                </div>
        
                                <div class="mb-3 d-flex flex-row justify-content-between">
                                    
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('suda_lang::press.login') }}
                                        </button>
                                        <div>
                                    
                                        
                                            <a class="btn btn-link ajaxPassword" href="{{ extadmin_url('passport/password/reset') }}">
                                                {{__('suda_lang::press.forget_password')}}?
                                            </a>
                                            
                                        
                                        </div>
                                </div>
                                
                                
                            </form>
                        </div>

                        <div class="col-sm-7 offset-sm-1 dash-login-tips">
                            
                            <div class="box" @if(isset($sdcore->settings->dashboard->login_color) && !empty($sdcore->settings->dashboard->login_color)) style="background-color:{{ $sdcore->settings->dashboard->login_color }}" @endif>
                                @if(isset($sdcore->settings->dashboard->dashboard_logo) && !empty($sdcore->settings->dashboard->dashboard_logo))
                                <img src="{{ $sdcore->settings->dashboard->dashboard_logo }}">
                                @endif
                            </div>
                        
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection