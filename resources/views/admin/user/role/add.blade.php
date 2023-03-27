@extends('view_path::layouts.default')



@section('content')
<div class="container-fluid">
    <div class="row suda-row">
        
        <div class="page-heading">
            <h1 class="page-title"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.add') }}</h1>
        </div>
        
        <div class="col-sm-5 suda_page_body">
            <div class="card">
                
                <div class="card-body">
                    
                    <form class="form-ajax" role="form" method="POST" action="{{ admin_url('user/roles/save') }}">
                        {{ csrf_field() }}
                        
                      <div class="mb-3 row {{ $errors->has('authority') ? ' has-error' : '' }}">
                          
                          <label for="authority" class="col-form-label col-sm-3 text-right">
                              角色级别
                          </label>
                          
                          <div class="col-sm-9">
                          <select name="authority" class="form-control">

                              @foreach($auths as $key=>$auth)
                                <option value="{{ $key }}">{{ $auth }}</option>
                              @endforeach

                          </select>
                        </div>
  
                      </div>

                      <div class="mb-3 row {{ $errors->has('name') ? ' has-error' : '' }}">
                          
                        <label for="name" class="col-form-label col-sm-3 text-right">
                            {{ __('suda_lang::press.role_name') }}
                        </label>
                        
                        <div class="col-sm-9">
                        <input type="text" name="name" class="form-control" id="inputName" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.role_name')]) }}">
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                        </div>

                      </div>

                      

                      <div class="mb-3 row">
                        
                            <label class="col-form-label col-sm-3 text-right">
                                &nbsp;
                            </label>
                            <div class="col-sm-9 checkbox">
                                <input type="checkbox" name="disable" checked> {{ __('suda_lang::press.enable') }}
                                <span class="help-block">
                                    添加角色后可以设置系统和应用权限
                                </span>
                            </div>

                      </div>
                      
                      
                      <div class="mb-3 row">
                            <label class="col-form-label col-sm-3">
                                &nbsp;
                            </label>
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.submit_save') }}</button>
                            </div>
                      
                      </div>
                      
                    </form>
                    
                </div>
            </div>
        </div>


        <div class="col-sm-5 suda_page_body">
            <div class="card">
                
                <div class="card-body">
                    <h5>角色级别说明</h5>
                    <p>
                        <b>普通管理</b><br>
                        <span class="help-block">普通管理员是最低级别的后台用户，根据设置的权限访问相应功能。</span>
                    </p>

                    <p>
                        <b>应用管理</b><br>
                        <span class="help-block">应用管理和普通管理类似。</span>
                        <span class="help-block" style="color:#ff5668;">系统为应用管理员提供了有别于其他管理员的控制台。</span>
                    </p>

                    <p>
                        <b>运营主管</b><br>
                        <span class="help-block">运营主管级别高于普通管理员。</span>
                        <span class="help-block">可以在应用和功能上根据不同级别的管理员给予不同的权限设置。</span>
                    </p>

                    <p>
                        <b>超级管理员</b><br>
                        <span class="help-block">拥有系统所有管理权限。</span>
                    </p>

                </div>

                <div class="card-footer">
                最重要说明: 任一角色级别默认不包括任何功能和菜单权限，均需手动完成设置。
                </div>

            </div>

            
        </div>

    </div>
</div>


@endsection


@push('scripts')

    <script>
    
        jQuery(function(){
            
            $('.role-permissions').find('.extend-name').on('click',function(){
                
                var refer = $(this).attr('refer_check');
                
                $('#'+refer).toggle('fast','swing');
                
            });
            
            $('#permission-select-all').on('click', function(){
                $('ul.role-permissions').find("input[type='checkbox']").prop('checked', true);
                return false;
            });

            $('#permission-deselect-all').on('click', function(){
                $('ul.role-permissions').find("input[type='checkbox']").prop('checked', false);
                return false;
            });
            
            $('input#permission-group').on('change',function(){                
                $(this).siblings('ul').find("input[type='checkbox']").prop('checked', this.checked);
            });
            
            function parentChecked(elem){
                var permission=$('input#permission-group');
                if(elem){
                    permission = $(elem).parents('.role-permissions').find('input#permission-group');
                }
                permission.each(function(){
                    var allChecked = true;
                    $(this).siblings('ul').find("input[type='checkbox']").each(function(){
                        if(!this.checked){
                            allChecked = false;
                        }
                    });
                    $(this).prop('checked', allChecked);
                });
            }

            parentChecked();

            $('.single-permission').on('change', function(){
                parentChecked(this);
            });
        });
    
    </script>

@endpush
