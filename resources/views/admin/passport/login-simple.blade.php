@extends('view_app::layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ suda_asset('css/dashlogin_simple.css') }}">

@if($simpleName=='simple-orange')
<style>
.container-login .login-bg{
    background-image: linear-gradient(135deg, rgb(255, 137, 61) 0%, rgb(255, 69, 0) 100%);
}
</style>
@elseif($simpleName=='simple-cyan')
<style>
.container-login .login-bg{
    background-image: linear-gradient(135deg, rgb(74, 230, 165) 0%, rgb(23, 213, 173) 100%);
}
</style>
@elseif($simpleName=='simple-black')
<style>
.container-login .login-bg{
    background-image: linear-gradient(135deg, rgb(43, 43, 43) 0%, rgb(0, 0, 0) 100%);
}
</style>
@endif

@endpush

@section('content')
<div class="container container-fluid container-login">
    <div class="row">
        <div class="col-sm-3 offset-sm-1 login-box">
        
            <div class="panel panel-default">
                <div class="panel-heading" style="text-align:left;font-size:1.6rem"><i class="icon ion-person"></i>&nbsp;登录</div>
                <div class="panel-body">
                    <form class="form-horizontal suda-login-form" role="form" method="POST" action="{{ admin_url('passport/login') }}">
                        {{ csrf_field() }}
                        
                        @if($login_name=='email')
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            
                            <div class="col-sm-12">
                                
                                <div class="input-group input-group-md">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <i class="icon ion-person"></i>
                                    </span>
                                    <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="{{trans('suda_lang::auth.emailorphone')}}">
                                </div>
                                
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                            
                        </div>
                        @elseif($login_name=='phone')
                        
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <div class="col-sm-12">
                                <div class="input-group input-group-md">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <i class="icon ion-person"></i>
                                    </span>
                                    <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required autofocus placeholder="{{trans('suda_lang::auth.phone')}}">
                                </div>
                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                        </div>
                        
                        @elseif($login_name=='username')
                        
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            
                            <div class="col-sm-12">
                                <div class="input-group input-group-md">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <i class="icon ion-person"></i>
                                    </span>
                                    <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus placeholder="{{trans('suda_lang::auth.username')}}">
                                </div>
                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                            
                        </div>
                        
                        @endif

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            
                            <div class="col-sm-12">
                                <div class="input-group input-group-md">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <i class="icon ion-key"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control" name="password" required placeholder="{{trans('suda_lang::auth.password')}}">
                                </div>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" value="1"> {{trans('suda_lang::auth.rememberLogin')}}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                
                                <button type="submit" class="btn btn-primary btn-md btn-block">
                                    {{trans('suda_lang::auth.login')}}
                                </button>
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                
                                <a class="btn btn-link ajaxPassword" href="{{ admin_url('passport/password/reset') }}">
                                    {{trans('suda_lang::auth.forgetPassword')}}?
                                </a>
                                
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-sm-8 login-bg">
            @include('view_app::passport.simple')
        </div>
        
    </div>
    
</div>
@endsection