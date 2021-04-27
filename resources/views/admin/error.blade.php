@extends('view_path::layouts.default')

@section('content')
<div class="container">
    <div class="row suda-row">
        <div class="col-sm-6 offset-sm-3">
            <div class="card">
                <div class="card-header bg-white">
                    {{ $code }}
                </div>
                <div class="card-body">
                    @if(isset($msg)) {{$msg}} @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
