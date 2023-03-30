@extends($extendFile)



@section('content')
<div class="container">
    <div class="row suda-row @if(intval($soperate->user_role)==2) suda-row-noside @endif">
        <div class="page-heading">
            <h1 class="page-title">帐户信息</h1>
        </div>
        
        <div class="col-sm-6 suda_page_body">
            <ul class="nav nav-tabs card-tabs">
                <li class="nav-item">
                  <a class="nav-link" href="{{ extadmin_url('profile') }}">基本资料</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link bg-white active" href="{{ extadmin_url('profile/password') }}">修改密码</a>
                </li>
              </ul>
            <div class="card card-with-tab">
                <div class="card-body">
                    
                    <form class="col-sm-6 form-ajax" role="form" method="POST" action="{{ extadmin_url('profile/changepassword') }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $soperate->id }}">
                        
                        <div class="mb-3">
                            <label for="inputName" class="col-form-label">当前用户</label>
                            
                            <input type="text" readonly class="form-control-plaintext" id="inputName" value="{{ $soperate->username }}">

                        </div>
                    
                        <div class="mb-3{{ $errors->has('old_password') ? ' has-error' : '' }}">
                            <label for="inputName" class="col-form-label">当前密码</label>
                            <input type="password" name="old_password" class="form-control" id="inputName" placeholder="请输入当前密码">
                                @if ($errors->has('old_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('old_password') }}</strong>
                                    </span>
                                @endif
                        </div>
                    
                        <div class="mb-3{{ $errors->has('new_password') ? ' has-error' : '' }}">
                            <label for="inputName" class="col-form-label">新密码</label>
                            <input type="password" name="new_password" class="form-control" id="inputName" placeholder="请输入当前密码">
                                @if ($errors->has('new_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
                                @endif
                        </div>
                    
                        <div class="mb-3{{ $errors->has('new_password_confirm') ? ' has-error' : '' }}">
                            <label for="inputName" class="col-form-label">确认新密码</label>
                            <input type="password" name="new_password_confirm" class="form-control" id="inputName" placeholder="请输入当前密码">
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
@endsection
