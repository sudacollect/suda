@extends('view_path::layouts.default')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <h1 class="page-title"><i class="ion-people-outline"></i>&nbsp;{{ __('suda_lang::press.sys_permission') }}</h1>
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
                            {{ __('suda_lang::press.sys_permission') }}
                        </p>
                        
                        <button class="btn btn-success btn-sm me-2" id="permission-select-all">{{ __('suda_lang::press.btn.select_all') }}</button>
                        <button class="btn btn-light btn-sm" id="permission-deselect-all">{{ __('suda_lang::press.btn.cancel_select') }}</button>

                        <div class="row role-permissions-group" >
                              @if(isset($menus) && count($menus)>0)
                        
                              @foreach($menus as $k=>$extend)

                              <ul class="role-permissions col-sm-3 mt-3">
                                  
                                  <li>

                                    <div class="form-check ">
                                        <input type="checkbox" class="form-check-input permission-group" refer_check="check_{{ $extend['id'] }}">
                                        <label class="form-check-label extend-name font-weight-bold" for="permission-group" refer_check="check_{{ $extend['id'] }}">
                                            <i class="ion-list-outline"></i>&nbsp;<b>{{ __($extend['title']) }}</b>
                                        </label>
                                    </div>
                                      @if(isset($extend['children']) && count($extend['children']) > 0)
                                      {{-- 正常有子菜单 --}}
                                      <ul class="list-group" id="check_{{ $extend['id'] }}">

                                          
                                          @foreach($extend['children'] as $menu_item)
                                          
                                          @php
                                          
                                          $hasPermission = false;
                                          if(isset($role_permissions['sys'][$extend['slug']]) && array_key_exists($menu_item['slug'],(array)$role_permissions['sys'][$extend['slug']])){
                                              $hasPermission = true;
                                          }
                                          
                                          @endphp
                                          
                                          <li class="list-group-item">
                                              
                                              <div class="checkbox">
                                                <div class="form-check">
                                                    <input type="checkbox" @if($hasPermission) checked @endif class="form-check-input single-permission" name="permission[{{ $extend['slug'] }}][{{ $menu_item['slug'] }}]" value="true">
                                                    <label class="form-check-label" >
                                                        {{ __($menu_item['title']) }}
                                                    </label>
                                                </div>
                                                <span class="help-block my-0">{{ $extend['slug'].'.'.$menu_item['slug'] }}</span>
                                                </div>
                                          </li>
                                          @endforeach
                                  
                                      </ul>
                                      @else
                                      {{-- 造一个子菜单 --}}
                                        <ul class="list-group" id="check_{{ $extend['id'] }}">

                                          
                                        
                                        
                                        @php
                                        
                                        $hasPermission = false;
                                        if(isset($role_permissions['sys'][$extend['slug']]) && array_key_exists('index',(array)$role_permissions['sys'][$extend['slug']])){
                                            $hasPermission = true;
                                        }
                                        
                                        @endphp
                                        
                                        <li class="list-group-item">
                                            
                                            <div class="checkbox">
                                              <div class="form-check">
                                                  <input type="checkbox" @if($hasPermission) checked @endif class="form-check-input single-permission" name="permission[{{ $extend['slug'] }}][index]" value="true">
                                                  <label class="form-check-label" >
                                                      {{ __($extend['title']) }}
                                                  </label>
                                              </div>
                                              <span class="help-block my-0">{{ $extend['slug'] }}</span>
                                              </div>
                                        </li>
                                        
                                
                                         </ul>
                                      @endif
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
            {{-- @include('view_path::user.role.role_desc') --}}
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
                    if($('ul[id="'+ $(e).attr('refer_check') +'"]').find("input[type='checkbox']").length > 0)
                    {
                        $('ul[id="'+ $(e).attr('refer_check') +'"]').find("input[type='checkbox']").each(function(){
                            if(!this.checked){
                                allChecked = false;
                            }
                        });
                        $(this).prop('checked', allChecked);
                    }
                    
                });
            }

            parentChecked();

            $('.single-permission').on('change', function(){
                parentChecked(this);
            });
        });
    
    </script>

@endpush
