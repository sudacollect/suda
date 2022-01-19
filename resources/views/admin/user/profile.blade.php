
@extends($extendFile)


@section('content')

<div class="container container-fluid">
  <div class="page-heading">
    <h1 class="page-title"><i class="ion-person"></i>&nbsp;帐户信息</h1>
  </div>
    <div class="row suda-row @if(intval($soperate->user_role)==2) suda-row-noside @endif">
        
        
        <div class="col-sm-6 suda_page_body">
            
            <ul class="nav nav-tabs card-tabs">
              <li class="nav-item">
                <a class="nav-link bg-white active" href="{{ admin_url('profile') }}">基本资料</a>
              </li>
              <!-- <li class="nav-item"><a class="nav-link" href="{{ url('profile/certify') }}">认证</a></li> -->
              <!-- <li class="nav-item"><a class="nav-link" href="{{ admin_url('email') }}">修改邮箱</a></li> -->
              <li class="nav-item">
                <a class="nav-link" href="{{ admin_url('profile/password') }}">修改密码</a>
              </li>
            </ul>
            <div class="card card-with-tab">
                <div class="card-body">
                    
                    <form class="col-sm-6" role="form" method="POST" action="{{ admin_url('profile/save') }}">
                        
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{ $soperate->id }}">
                        
                        <div class="mb-3">
                          <label for="inputEmail3" class="col-form-label">头像</label>
                          <div class="form-control-static">
                            @uploadCroppie('operate',$soperate->avatar)
                          </div>
                        </div>
                        
                        
                        <div class="mb-3{{ $errors->has('username') ? ' has-error' : '' }}">
                          <label for="inputEmail3" class="col-form-label">{{ __('suda_lang::auth.username') }}</label>
                          <input type="text" name="username" class="form-control" value="{{ $soperate->username }}" id="inputName" placeholder="{{ __('suda_lang::auth.username') }}">
                            @if ($errors->has('username'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="mb-3{{ $errors->has('email') ? ' has-error' : '' }}">
                          <label for="inputEmail3" class="col-form-label">{{ __('suda_lang::auth.email') }}</label>
                          <input type="text" name="email" class="form-control" value="{{ $soperate->email }}" id="inputName" placeholder="{{ __('suda_lang::auth.email') }}">
                              @if ($errors->has('email'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('email') }}</strong>
                                  </span>
                              @endif
                        </div>
                        
                        <div class="mb-3{{ $errors->has('phone') ? ' has-error' : '' }}">
                          <label for="inputEmail3" class="col-form-label">{{ __('suda_lang::auth.phone') }}</label>
                          <input type="text" name="phone" class="form-control" value="{{ $soperate->phone }}" id="inputName" placeholder="{{ __('suda_lang::auth.phone') }}">
                              @if ($errors->has('phone'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('phone') }}</strong>
                                  </span>
                              @endif
                              <span class="help-block">邮箱和手机号是登录账号，请谨慎修改</span>
                        </div>
                      
                        @if($soperate->categories && $soperate->categories->count()>0)
                        <div class="mb-3">
                          <label for="inputPassword3" class="col-form-label">部门</label>
                          @foreach($soperate->categories as $cate)
                          @if($cate->taxonomy && $cate->taxonomy->term)
                          <span class="badge bg-light text-dark">{{ $cate->taxonomy->term->name }}</span>
                          @endif
                          @endforeach
                        </div>
                        @endif
                      
                        @if(isset($soperate->role))
                        <div class="mb-3">
                          <label for="inputPassword3" class="col-form-label">角色</label>
                          <span class="badge bg-light text-dark">{{ $soperate->role->name }}</span>
                        </div>
                        @endif
                        
                        <div class="mb-3">
                          <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.submit_save') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>


<div id="modal-upload-avatar" class="modal fade modal-box" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-small" role="document" style="width:420px;">
    <div class="modal-content">
      
      <div class="modal-header">
        <h4 class="modal-title">
          <i class="ion-image"></i>
          设置头像
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        
      </div>
      
      <div class="modal-body">
          <div class="upload-crop-container">

                  <div id="upload-crop">
                      
                  </div>

          </div>
          
      </div>
      
      <div class="modal-footer">
          <button type="button" id="apply-crop" class="btn btn-primary btn-sm pull-right" >使用此头像</button>
          <button type="button" class="btn btn-light btn-sm pull-right" style="margin-right:15px;" data-bs-dismiss="modal" aria-label="Close">取消</button>
      </div>
      
      
      
      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



@endsection

