
@extends($extendFile)


@section('content')

<div class="container container-fluid">
  <div class="page-heading">
    <h1 class="page-title"><i class="ion-person"></i>&nbsp;{{ __('suda_lang::press.profile.profile') }}</h1>
  </div>
    
        
        
  <div class="col-sm-6 suda_page_body">
      
      <ul class="nav nav-tabs card-tabs">
        <li class="nav-item">
          <a class="nav-link bg-white active" href="{{ admin_url('profile') }}">{{ __('suda_lang::press.profile.profile') }}</a>
        </li>
        <!-- <li class="nav-item"><a class="nav-link" href="{{ url('profile/certify') }}">认证</a></li> -->
        <!-- <li class="nav-item"><a class="nav-link" href="{{ admin_url('email') }}">修改邮箱</a></li> -->
        <li class="nav-item">
          <a class="nav-link" href="{{ admin_url('profile/password') }}">{{ __('suda_lang::press.password') }}</a>
        </li>
      </ul>
      <div class="card card-with-tab">
          <div class="card-body">
              
              <form class="col-sm-6" role="form" method="POST" action="{{ admin_url('profile/save') }}">
                  
                  @csrf
                  <input type="hidden" name="user_id" value="{{ $soperate->id }}">
                  
                  <div class="mb-3">
                    <label for="inputEmail3" class="col-form-label">{{ __('suda_lang::press.avatar') }}</label>
                    <div class="form-control-static">
                      @uploadCroppie('operate',$soperate->avatar)
                    </div>
                  </div>
                  
                  
                  <div class="mb-3{{ $errors->has('username') ? ' has-error' : '' }}">
                    <label for="inputEmail3" class="col-form-label">{{ __('suda_lang::press.username') }}</label>
                    <input type="text" name="username" class="form-control" value="{{ $soperate->username }}" id="inputName" placeholder="{{ __('suda_lang::press.username') }}">
                      @if ($errors->has('username'))
                          <span class="help-block">
                              <strong>{{ $errors->first('username') }}</strong>
                          </span>
                      @endif
                  </div>
                  
                  <div class="mb-3{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="inputEmail3" class="col-form-label">{{ __('suda_lang::press.email') }}</label>
                    <input type="text" name="email" class="form-control" value="{{ $soperate->email }}" id="inputName" placeholder="{{ __('suda_lang::press.email') }}">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                  </div>
                  
                  <div class="mb-3{{ $errors->has('phone') ? ' has-error' : '' }}">
                    <label for="inputEmail3" class="col-form-label">{{ __('suda_lang::press.phone') }}</label>
                    <input type="text" name="phone" class="form-control" value="{{ $soperate->phone }}" id="inputName" placeholder="{{ __('suda_lang::press.phone') }}">
                        @if ($errors->has('phone'))
                            <span class="help-block">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                        @endif
                        <span class="help-block">{{ __('suda_lang::press.account_change_tips') }}</span>
                  </div>
                
                  @if($soperate->categories && $soperate->categories->count()>0)
                  <div class="mb-3">
                    <label for="inputPassword3" class="col-form-label">{{ __('suda_lang::press.organization') }}</label>
                    @foreach($soperate->categories as $cate)
                    @if($cate->taxonomy && $cate->taxonomy->term)
                    <span class="badge bg-light text-dark">{{ $cate->taxonomy->term->name }}</span>
                    @endif
                    @endforeach
                  </div>
                  @endif
                
                  @if(isset($soperate->role))
                  <div class="mb-3">
                    <label for="inputPassword3" class="col-form-label">{{ __('suda_lang::press.role') }}</label>
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





@endsection

