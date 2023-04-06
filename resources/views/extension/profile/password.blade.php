@extends($extendFile)



@section('content')
<div class="container  container-fluid">
    <div class="row suda-row ">
        <div class="col-sm-6 offset-sm-3">
            <div class="page-heading">
                <h1 class="page-title"><i class="ion-person"></i>&nbsp;{{ __('suda_lang::auth.profile') }}</h1>
            </div>
            
            <div class="col-12 suda_page_body">
                <ul class="nav nav-tabs card-tabs">
                    <li class="nav-item">
                    <a class="nav-link" href="{{ extadmin_url('profile') }}">{{ __('suda_lang::auth.profile') }}</a>
                    </li>
                    <!-- <li class="nav-item"><a class="nav-link" href="{{ url('profile/certify') }}">认证</a></li> -->
                    <!-- <li class="nav-item"><a class="nav-link" href="{{ extadmin_url('email') }}">修改邮箱</a></li> -->
                    <li class="nav-item">
                    <a class="nav-link bg-white active" href="{{ extadmin_url('profile/password') }}">{{ __('suda_lang::press.password') }}</a>
                    </li>
                </ul>
                <div class="card card-with-tab">
                    <div class="card-body">
                        
                        <form class="col-sm-6 form-ajax" role="form" method="POST" action="{{ extadmin_url('profile/changepassword') }}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $soperate->id }}">
                            
                            <div class="mb-3">
                                
                                <input type="text" readonly class="form-control-plaintext" id="inputName" value="{{ $soperate->username }}">

                            </div>
                        
                            <div class="mb-3{{ $errors->has('old_password') ? ' has-error' : '' }}">
                                <label for="inputName" class="col-form-label">{{ __('suda_lang::press.password') }}</label>
                                <input type="password" name="old_password" class="form-control" id="inputName" placeholder="{{ __('suda_lang::press.password') }}">
                                    @if ($errors->has('old_password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('old_password') }}</strong>
                                        </span>
                                    @endif
                            </div>
                        
                            <div class="mb-3{{ $errors->has('new_password') ? ' has-error' : '' }}">
                                <label for="inputName" class="col-form-label">{{ __('suda_lang::press.new_password') }}</label>
                                <input type="password" name="new_password" class="form-control" id="inputName" placeholder="{{ __('suda_lang::press.new_password') }}">
                                    @if ($errors->has('new_password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('new_password') }}</strong>
                                        </span>
                                    @endif
                            </div>
                        
                            <div class="mb-3{{ $errors->has('new_password_confirm') ? ' has-error' : '' }}">
                                <label for="inputName" class="col-form-label">{{ __('suda_lang::press.new_password_confirm') }}</label>
                                <input type="password" name="new_password_confirm" class="form-control" id="inputName" placeholder="{{ __('suda_lang::press.new_password_confirm') }}">
                                    @if ($errors->has('new_password_confirm'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('new_password_confirm') }}</strong>
                                        </span>
                                    @endif
                        
                            </div>
                        
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.submit_save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>
@endsection
