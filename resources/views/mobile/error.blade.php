@extends('view_suda::site.layouts.error')

@section('content')
<div class="container mt-3">
    
    <div class="row suda-errors">
        <div class="col-sm-6 offset-sm-3">
            <div class="card">
                <div class="card-header bg-white">
                    <h4><i class="zly-warning"></i>&nbsp;@if ($errors->has('errcode')) {{ $errors->first('errcode') }} @endif</h4>
                </div>
                <div class="card-body">
                    <p>
                        @if ($errors->has('errmsg'))
                            {{ $errors->first('errmsg') }}
                        @else
                            @if(isset($errmsg)) {{$errmsg}} @endif
                        @endif
                    </p>
                </div>
                <div class="card-footer">
                    <a class="backhome" href="{{ url('/') }}">返回首页</a>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection
