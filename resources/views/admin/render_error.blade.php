@extends('view_suda::admin.layouts.app_error')

@section('content')
<div class="container mt-5">
    <div class="row suda-row">
        <div class="col-sm-6 offset-sm-3">
            <div class="card">
                <div class="card-header bg-white">
                    <h4>{{ $code }}</h4>
                </div>
                <div class="card-body">
                    @if(isset($msg)) {{$msg}} @endif
                </div>
                <div class="card-footer">
                    <a href="{{ admin_url('/') }}">&lt;&nbsp;返回控制面板</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
