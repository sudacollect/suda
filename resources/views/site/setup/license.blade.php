@extends('view_app::layouts.setup')



@section('content')
<div class="container-fluid mt-lg-5">
    <div class="row">
        <div class="col-sm-4 offset-sm-4 login-box">
            
            <div class="card">
                <div class="card-header">授权</div>
                
                <div class="card-body">
                    
                    @if(session()->get('success'))
                    
                    <h4 style="text-align:center;">授权验证成功</h4>
                    
                    <p style="text-align:center;"><a href="{{ url('/') }}">返回首页</a></p>
                    
                    @else
                    <form role="form" method="POST" action="{{ url('sdone/setup/license') }}">
                        @csrf

                        <div class="mb-3{{ $errors->has('serialnumber') ? ' has-error' : '' }}">
                            
                            <input id="serialnumber" type="text" class="form-control" name="serialnumber" value="" required autofocus placeholder="请输入授权码">
                                
                                @if ($errors->has('serialnumber'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('serialnumber') }}</strong>
                                    </span>
                                @endif
                            
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                授权验证
                            </button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
    
</div>
@endsection