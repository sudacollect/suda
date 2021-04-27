@extends('view_path::layouts.default')


@section('content')

<div class="container container-fluid">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title">
                <i class="zly-user-check"></i>&nbsp;用户注册设置
            </h1>
        </div>
        
        <div class="col-12 suda_page_body">
            
            
            <ul class="nav nav-tabs card-tabs">
                <li class="nav-item">
                    <a class="nav-link bg-white active" href="{{ admin_url('user/rule/register') }}">基本设置</a>
                </li>
            </ul>
            
            <div class="card card-with-tab">
                <div class="card-body">
                    
                    <form class="form-ajax" role="form" method="POST" action="{{ admin_url('user/rule/save/register') }}">

                        @csrf

                        <input type="hidden" name="rule_type" value="register">
                        
                        <div class="form-group row">
                        
                          <label for="register" class="col-form-label col-sm-2 text-right">
                                 开启注册
                          </label>
                          
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="register" placeholder="开放注册" value="1" @if((isset($settings->values) && $settings->values==1)) checked @endif>
                                <label class="form-check-label" for="register">开放注册</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="register" placeholder="关闭注册" value="0" @if((isset($settings->values) && $settings->values==0)) checked @endif>
                                <label class="form-check-label" for="register">关闭注册</label>
                            </div>
                          
                        </div>
                        
                        <div class="form-group row">
                          <div class="offset-sm-2 col-sm-6">
                            <button type="submit" class="btn btn-primary btn-md">{{ trans('suda_lang::press.submit_save') }}</button>
                          </div>
                        </div>

                    </form>

                    
                </div>

                <div class="card-footer">

                    <span class="help-block">
                        <i class="ion-help-circle"></i>&nbsp;在模板中使用此设置项的方法<br>

                        这个是系统变量，集成在 <code>setting</code> 参数中

                        <code>

                            $sdcore->settings->user->register == 1 || 0

                        </code>

                    </span>

                </div>

            </div>

            
        </div>
        
    </div>
</div>

@endsection

