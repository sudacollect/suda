@extends('view_path::layouts.default_noside')


@section('content')

<div class="container">
    <div class="row suda-row suda-row-noside">
        

        
        {{ Sudacore::widget('\Gtd\Suda\Widgets\DashboardQuickin',['async'=>true]) }}
        
        
        
    </div>
</div>
@endsection