@extends('view_app::layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ suda_asset('css/dashlogin.css') }}">

@if(isset($loginbox) && $loginbox=='picture')
<style>
    body{ background:#061425; }
    .login-box{ color:#888 }

    .login-box .card-title{ color:#f9fbff }
    .login-box .card{
        background:#061425;
    }
    .login-box .card-body{
        background-color:rgba(0,0,0,0.4);
    }
    .login-box .input-group-text{
        background: #17335a;
        border-color: #09182b;
        color: #dae9ff;
    }
    .login-box input.form-control{
        background: #17335a;
        border-color: #09182b;
        color:#f9fbff;
    }
    .form-control:focus{
        box-shadow:none;
    }
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus,
    textarea:-webkit-autofill,
    textarea:-webkit-autofill:hover,
    textarea:-webkit-autofill:focus,
    select:-webkit-autofill,
    select:-webkit-autofill:hover,
    select:-webkit-autofill:focus {
    border: 1px solid #09182b;
    -webkit-text-fill-color: #f9fbff;
    -webkit-box-shadow: 0 0 0px 1000px #17335a inset;
    }
    .login-box .btn-primary{
        background: #17335a;
        border: 1px solid #09182b;
        color: #f9fbff;
        box-shadow:none;
        font-weight:bold;
    }
    .login-box .btn-primary:active{
        background-color: #22518c !important;
        border-color:#09182b !important;
        color: #8cbdfb !important;
        box-shadow:none !important;
    }
    .login-box .btn-primary:hover{
        background: #22518c;
        color: #8cbdfb !important;
        box-shadow: 0px 0px 10px 0px rgb(42, 80, 128);
    }
    .login-box a.btn-link{ color:#888; }
    
</style>
@endif

@if(isset($login_select))

    @if($login_select=='custom' && isset($login_pic ))
    <style>
        .dash-login-tips .box{
            background: #fff url("{{ $login_pic }}") no-repeat center bottom;
            background-size: cover;
        }
        .dash-login-tips .box img{display:none;}
    </style>
    @else
    <style>
        
        .dash-login-tips .box{
            background: #fff url("{{ suda_asset('images/login/'.$login_select.'.jpg') }}") no-repeat center bottom;
            background-size: cover;
        }
        .dash-login-tips .box img{display:none;}
    </style>
    @endif

@endif

@endpush

@section('content')
<div class="container-fluid container-login">
    <div class="row">
        
        <div class="col-12 px-0 py-0 login-box">
            <div class="card border-0">
                <div class="card-body px-0 py-0" style="position:relative;">
                    <div class="row no-gutters min-vh-100">
                    
                        <div class="col-sm-5 dash-login-tips">
                            
                            <div class="box" @if(isset($sdcore->settings->dashboard->login_color) && !empty($sdcore->settings->dashboard->login_color)) style="background-color:{{ $sdcore->settings->dashboard->login_color }}" @endif>
                                @if(isset($sdcore->settings->dashboard->dashboard_logo) && !empty($sdcore->settings->dashboard->dashboard_logo))
                                <img src="{{ $sdcore->settings->dashboard->dashboard_logo }}">
                                @endif
                            </div>
                        
                        </div>

                        <div class="col-sm-3 offset-sm-2 dash-login-box px-3">
                            <h4 class="card-title">
                                {{ __('suda_lang::auth.DashboardLogin') }}
                            </h4>
                            <form class="suda-login-form" role="form" method="POST" action="{{ admin_url('passport/login') }}">
                                @csrf
                                
                                @if($login_name=='email')
                                
                                <div class="mb-3">

                                    <div class="input-group ">
                                        <div class="input-group-text"><i class="icon ion-person"></i></div>
                                        

                                        <input id="email" type="text" class="form-control font-weight-bold {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="{{__('suda_lang::auth.emailorphone')}}">
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
                                        <input id="phone" type="text" class="form-control font-weight-bold {{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}" required autofocus placeholder="{{__('suda_lang::auth.phone')}}">
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
                                        
                                        <input id="username" type="text" class="form-control font-weight-bold {{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus placeholder="{{__('suda_lang::auth.username')}}">
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
                                            <input id="password" type="password" class="form-control font-weight-bold {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="{{__('suda_lang::auth.password')}}">
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
                                            {{ __('suda_lang::auth.rememberLogin') }}
                                        </label>
                                    </div>
                                    
                                </div>
        
                                <div class="mb-3 d-flex flex-row justify-content-between">
                                    
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            {{ __('suda_lang::auth.login') }}
                                        </button>
                                        <div>
                                    
                                        
                                            <a class="btn btn-link ajaxPassword" href="{{ admin_url('passport/password/reset') }}">
                                                {{__('suda_lang::auth.forgetPassword')}}?
                                            </a>
                                            
                                        
                                        </div>
                                </div>
                                
                                
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection