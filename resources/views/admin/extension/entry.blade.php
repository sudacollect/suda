@extends('view_path::layouts.default')


@section('content')

<div class="container">
    <div class="row suda-row">
        
        {{-- <h1 class="page-title"><i class="ion-settings"></i>&nbsp;&nbsp;控制面板</h1> --}}

        {{ Sudacore::widget('entry.extension',['menus'=>$extension_menus]) }}

    </div>
</div>
@endsection

