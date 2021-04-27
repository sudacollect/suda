@extends('view_path::layouts.default')



@section('content')
<div class="container-fluid">
    <h1 class="page-title"><i class="ion-create"></i>&nbsp;编辑部门</h1>
    <div class="row suda-row">
        
        <div class="col-sm-6  suda_page_body">
            <div class="card">
                
                
                <div class="card-body">
                    
                    <form class="form-ajax" role="form" method="POST" action="{{ admin_url('user/organization/save') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $org->id }}">
                      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                          
                        <label for="name">
                            {{ trans('suda_lang::press.organization_name') }}
                        </label>
                        
                        <input type="text" name="name" class="form-control" id="inputName" placeholder="{{ trans('suda_lang::press.input_placeholder',['column'=>trans('suda_lang::press.organization_name')]) }}" value="{{ $org->name }}">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        
                      </div>
                      <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" name="disable" placeholder="是" value="0" @if($org->disable==0) checked @endif>
                            <label class="form-check-label" for="disable">{{ trans('suda_lang::press.enable') }}</label>
                        </div>
                    </div>
                      

                      <div class="form-group">
                        <button type="submit" class="btn btn-primary">{{ trans('suda_lang::press.submit_save') }}</button>
                      </div>
                      
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
