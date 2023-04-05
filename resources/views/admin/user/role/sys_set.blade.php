@extends('view_path::layouts.default')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <h1 class="page-title"><i class="ion-people-outline"></i>&nbsp;设置系统权限</h1>
    </div>
    <div class="row suda-row">

        <div class="col-sm-9  suda_page_body">
            <div class="card">
                
                <div class="card-body">
                    
                    <form class="form-ajax" role="form" method="POST" action="{{ admin_url('user/roles/savesys') }}">
                        @csrf
                        
                        
                      <input type="hidden" name="id" value="{{ $role->id }}">
                      
                      <div class="mb-3{{ $errors->has('permission') ? ' has-error' : '' }}">
                          
                        <p for="permission">
                            {{ __('suda_lang::press.system_permission') }}
                        </p>
                        
                        <button class="btn btn-success btn-sm me-2" id="permission-select-all">选择所有权限</button>
                        <button class="btn btn-light btn-sm" id="permission-deselect-all">取消全选</button>

                        <div class="row role-permissions-group" >
                              @if(isset($apps) && isset($permissions))
                        
                              @foreach($apps as $k=>$extend)
                              
                              @php
                              
                              $hasExtend = false;
                              if($role_permissions){
                              
                                  if(array_key_exists($extend['name'],(array)$role_permissions['sys'])){
                                      $hasExtend = true;
                                  }
                              
                              }
                              
                              @endphp
                            

                              <ul class="role-permissions col-sm-3 mt-3">
                                  
                                  <li>

                                    <div class="form-check ">
                                        <input type="checkbox" class="form-check-input permission-group" refer_check="check_{{ $extend['name'] }}">
                                        <label class="form-check-label extend-name font-weight-bold" for="permission-group" refer_check="check_{{ $extend['name'] }}">
                                            <i class="ion-reader-outline"></i>&nbsp;{{ $extend['display_name'] }}
                                        </label>
                                    </div>
                                      
                                     
                                      
                                      <ul class="list-group" id="check_{{ $extend['name'] }}">

                                          @if(array_key_exists('policy',$extend))

                                          @foreach($extend['policy'] as $permission)

                                            @php
                                            
                                            $hasPermission = false;
                                            if($hasExtend && array_key_exists($permission['name'],(array)$role_permissions['sys'][$extend['name']])){
                                                $hasPermission = true;
                                            }
                                            
                                            @endphp

                                            <li class="list-group-item" style="overflow: hidden;overflow-wrap: break-word;">
                                                <div class="checkbox">
                                                    <div class="form-check">
                                                        <input type="checkbox" @if($hasPermission) checked @endif class="form-check-input single-permission" name="permission[{{ $extend['name'] }}][{{ $permission['name'] }}]" value="true">
                                                        <label class="form-check-label" >
                                                            {{ $permission['display_name']}}
                                                        </label>
                                                    </div>
                                                    <span class="help-block my-0">{{ $extend['name'].'.'.$permission['name'] }}</span>
                                                </div>
                                            </li>

                                          @endforeach
                                          
                                          @else
                                          
                                          @foreach($permissions as $permission)
                                          
                                          @php
                                          
                                          $hasPermission = false;
                                          if($hasExtend && array_key_exists($permission['name'],(array)$role_permissions['sys'][$extend['name']])){
                                              $hasPermission = true;
                                          }
                                          
                                          @endphp
                                          
                                          <li class="list-group-item">
                                              
                                              <div class="checkbox">
                                                <div class="form-check">
                                                    <input type="checkbox" @if($hasPermission) checked @endif class="form-check-input single-permission" name="permission[{{ $extend['name'] }}][{{ $permission['name'] }}]" value="true">
                                                    <label class="form-check-label" >
                                                        {{ $permission['display_name'].$extend['display_name'] }}
                                                    </label>
                                                </div>
                                                <span class="help-block my-0">{{ $extend['name'].'.'.$permission['name'] }}</span>
                                                </div>
                                          </li>
                                          @endforeach

                                          @endif
                                  
                                          @if(isset($extend['permission']))
                                  
                                              @foreach($extend['permission'] as $permission)
                                      
                                              <li class="list-group-item">
                                                <div class="checkbox">

                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input single-permission list-group-item" name="permission[{{ $extend['name'] }}][{{ $permission['name'] }}]" value="true">
                                                        <label class="form-check-label" >
                                                            {{ $permission['display_name'].$extend['display_name'] }}
                                                        </label>
                                                    </div>
                                                </div>
                                              </li>
                                      
                                              @endforeach
                                  
                                          @endif
                                  
                                      </ul>
                                      
                                  </li>
                                  
                              </ul>
                              
                        
                              @endforeach
                              @endif
                        </div>

                      </div>
                      
                      
                      <div class="mb-3">
                        
                          <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.submit_save') }}</button>
                        
                      </div>
                      
                    </form>
                    
                </div>
            </div>
        </div>

        <div class="col-sm-3  suda_page_body">
            @include('view_path::user.role.role_desc')
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
            
            $('input.permission-group').on('change',function(e){      
                var refer_check = $(this).attr('refer_check');
                $('ul[id="'+refer_check+'"]').find("input[type='checkbox']").prop('checked', this.checked);
            });
            
            function parentChecked(elem){
                var permission=$('input.permission-group');
                if(elem){
                    permission = $(elem).parents('.role-permissions').find('input.permission-group');
                }
                permission.each(function(index,e){
                    var allChecked = true;
                    $('ul[id="'+ $(e).attr('refer_check') +'"]').find("input[type='checkbox']").each(function(){
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
