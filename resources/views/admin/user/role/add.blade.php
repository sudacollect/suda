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
                        @csrf
                        
                      <div class="mb-3 row {{ $errors->has('authority') ? ' has-error' : '' }}">
                          
                          <label for="authority" class="col-form-label col-sm-3 text-right">
                            {{ __('suda_lang::operate.role.level') }}
                          </label>
                          
                          <div class="col-sm-9">
                          <select name="authority" class="form-control">

                              @foreach($auths as $key=>$auth)
                                <option value="{{ $auth->name }}">{{ __('suda_lang::operate.roles.'.$auth->value) }}</option>
                              @endforeach

                          </select>
                        </div>
  
                      </div>

                      <div class="mb-3 row {{ $errors->has('name') ? ' has-error' : '' }}">
                          
                        <label for="name" class="col-form-label col-sm-3 text-right">
                            {{ __('suda_lang::operate.role.name') }}
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
                                    {{ __('suda_lang::operate.role.edit_desc') }}
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
