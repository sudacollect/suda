@extends('view_suda::errors.master')

@section('content')
<div class="container mt-3">
    <div class="row suda-errors">
        <div class="col-sm-6 offset-sm-3">
            <div class="card">
                <div class="card-header bg-white">
                    <h4><i class="zly-warning"></i>&nbsp;@if (isset($status)) {{ $status }} @endif</h4>
                </div>
                <div class="card-body">
                    <p>
                        @if (isset($status_msg))
                            {{ $status_msg }}
                        @else
                            系统异常
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
