@extends('view_suda::errors.master')

@section('content')
<div class="container mt-3">
    <div class="row suda-errors">
        <div class="col-sm-6 offset-sm-3">
            <div class="card border-light">
                <div class="card-body">
                    <h5>@if (isset($status)) {{ $status }} @endif</h5>
                    <p>
                        @if (isset($status_msg))
                            {{ $status_msg }}
                        @else
                            系统异常
                        @endif
                    </p>
                    <p><a class="backhome" href="{{ url('/') }}">返回首页</a></p>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
