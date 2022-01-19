@extends('view_app::layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ suda_asset('css/dashlogin_split.css') }}">

@if(isset($loginbox) && $loginbox=='picture')
<style>
    body{ background:#292929; }
</style>
@endif

@if(isset($login_select))

    @if($login_select=='custom' && isset($login_pic ))
<style>
.container-login .login-bg{
    background: #fff url("{{ $login_pic }}") no-repeat center bottom;
    background-size: cover;
}
</style>
    @else
<style>
.container-login .login-bg{
    background: #fff url("{{ suda_asset('images/login/'.$login_select.'.jpg') }}") no-repeat center bottom;
    background-size: cover;
}
</style>
    @endif

@endif

@endpush

@section('content')
<div class="container container-login">
    <div class="row">
        <div class="col-sm-3 offset-sm-1 login-box">
        
            <div class="panel panel-default">
                <div class="panel-heading" style="text-align:left;font-size:1.6rem"><i class="icon ion-person"></i>&nbsp;登录</div>
                <div class="panel-body">
                    <form class="form-horizontal suda-login-form" role="form" method="POST" action="{{ admin_url('passport/login') }}">
                        {{ csrf_field() }}
                        
                        @if($login_name=='email')
                        <div class="mb-3{{ $errors->has('email') ? ' has-error' : '' }}">
                            
                            <div class="col-sm-12">
                                
                                <div class="input-group input-group-md">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <i class="icon ion-person"></i>
                                    </span>
                                    <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="{{__('suda_lang::auth.emailorphone')}}">
                                </div>
                                
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                            
                        </div>
                        @elseif($login_name=='phone')
                        
                        <div class="mb-3{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <div class="col-sm-12">
                                <div class="input-group input-group-md">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <i class="icon ion-person"></i>
                                    </span>
                                    <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required autofocus placeholder="{{__('suda_lang::auth.phone')}}">
                                </div>
                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                        </div>
                        
                        @elseif($login_name=='username')
                        
                        <div class="mb-3{{ $errors->has('username') ? ' has-error' : '' }}">
                            
                            <div class="col-sm-12">
                                <div class="input-group input-group-md">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <i class="icon ion-person"></i>
                                    </span>
                                    <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus placeholder="{{__('suda_lang::auth.username')}}">
                                </div>
                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                            
                        </div>
                        
                        @endif

                        <div class="mb-3{{ $errors->has('password') ? ' has-error' : '' }}">
                            
                            <div class="col-sm-12">
                                <div class="input-group input-group-md">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <i class="icon ion-key"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control" name="password" required placeholder="{{__('suda_lang::auth.password')}}">
                                </div>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="col-sm-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> {{__('suda_lang::auth.rememberLogin')}}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="col-sm-12">
                                
                                <button type="submit" class="btn btn-primary btn-md ">
                                    {{__('suda_lang::auth.login')}}
                                </button>
                                
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="col-sm-12">
                                
                                <a class="btn btn-link ajaxPassword" href="{{ admin_url('passport/password/reset') }}">
                                    {{__('suda_lang::auth.forgetPassword')}}?
                                </a>
                                
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
            
        </div>

        <div class="col-sm-8 login-bg">

        </div>
        
    </div>
    
</div>
@endsection