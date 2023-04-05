@extends('view_path::component.modal')

@section('content')
<form class="handle-ajaxform" role="form" method="POST" action="{{ admin_url('manage/operates/save') }}">
    
    @csrf
    <input type="hidden" name="id" value="{{ $operate->id }}">
    <input type="hidden" name="organization_id" value="1">
    
    <div class="modal-body">

        <div class="container-fluid">

        
            <div id="organization_group"  class="mb-3{{ $errors->has('organization_id') ? ' has-error' : '' }}" @if($operate->superadmin==1) style="display:none;" @endif>
                
                <label for="organization_id" >
                    {{ __('suda_lang::press.organization') }}
                </label>
                <x-suda::select-category type="multiple" taxonomy="org_category" placeholder="请选择部门" :selected="$orgs" />
            
            </div>
            
            <div id="role_group" class="mb-3{{ $errors->has('role_id') ? ' has-error' : '' }}" @if($operate->superadmin==1) style="display:none;" @endif>
                
                <label for="role_id" >
                    {{ __('suda_lang::press.role') }}
                </label>
                
                <select id="role_id" name="role_id" class="form-control" placeholder="选择角色">
                    <option value="">请选择角色</option>
                    @foreach ($roles as $k=>$role)
                    <option value="{{ $role->id }}" @if($operate->role_id==$role->id) selected @endif >{{ $role->name }}</option>
                    @endforeach
                </select>
            
            </div>
        
        
            <div class="mb-3{{ $errors->has('username') ? ' has-error' : '' }}">
                
            <label for="username" >
                {{ __('suda_lang::press.username') }}
            </label>
            
            <input type="text" name="username" class="form-control" id="inputName" placeholder="英文数字下划线" value="{{ $operate->username }}">
                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
            
            </div>
            
            <div class="mb-3{{ $errors->has('password') ? ' has-error' : '' }}">
                
            <label for="password" >
                {{ __('suda_lang::auth.password') }}
            </label>
            
            <input type="password" name="password" class="form-control" id="inputName" placeholder="请输入密码">
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            
            </div>
            
            <div class="mb-3{{ $errors->has('phone') ? ' has-error' : '' }}">
                
            <label for="phone" >
                {{ __('suda_lang::press.telephone') }}
            </label>
            
            <input type="text" name="phone" class="form-control" id="inputName" placeholder="请输入手机（选填）" value="{{ $operate->phone }}">
                @if ($errors->has('phone'))
                    <span class="help-block">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                @endif
            
            </div>
            
            <div class="mb-3{{ $errors->has('email') ? ' has-error' : '' }}">
                
            <label for="email" >
                {{ __('suda_lang::press.email') }}
            </label>
            
            <input type="text" name="email" class="form-control" id="inputName" placeholder="请输入邮箱（选填）" value="{{ $operate->email }}">
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            
            </div>
            
            @if($soperate->superadmin==1)
            <div class="mb-3">
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="superadmin" placeholder="是" value="1" @if($operate->superadmin==1) checked @endif>
                    <label class="form-check-label" for="superadmin">启用超级管理员</label>
                </div>
            </div>
            @else
            {{-- 不能添加编辑管理员 --}}
            @endif


            <div class="mb-3">
                <div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input" name="enable" placeholder="是" value="1" @if($operate->enable==1) checked @endif>
                    <label class="form-check-label" for="enable">{{ __('suda_lang::press.enable') }}</label>
                </div>
            </div>
        

        </div>

    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.submit_save') }}</button>
    </div>

</form>

@stack('scripts')
<script>
    
    jQuery(function(){
        
        $('.handle-ajaxform').ajaxform();
        
        $('input[name="superadmin"]').on('change',function(e){
            e.preventDefault();
            if($(this).prop('checked')==true){
                $('#organization_group').hide();
                $('#role_group').hide();
            }else{
                $('#organization_group').show();
                $('#role_group').show();
            }
        });
        
    });
    
</script>

@endsection
