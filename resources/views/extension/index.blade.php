@extends('view_suda::extension.layouts.default_noside')


@section('content')

<div class="container">
    <div class="row suda-row suda-row-noside">
        

        
        {{ Sudacore::widget('\Gtd\Suda\Widgets\Entry\Quickin',['async'=>true]) }}
        
        
        
    </div>
</div>
@endsection