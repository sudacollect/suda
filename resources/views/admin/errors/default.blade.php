@extends('view_path::layouts.app_error')


@section('content')
<div class="container mt-3">
    <div class="row suda-errors">
        <div class="col-sm-6 offset-sm-3">
            <div class="card">
                <div class="card-header bg-white">
                    <i class="ion-alert-circle"></i>提示
                </div>
                <div class="card-body">
                    <p>
                        @if(isset($messages))
        
                        @foreach($messages as $message)
                        
                        <p>{{ $message }}</p>
                        
                        @endforeach
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