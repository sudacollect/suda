@extends('view_path::layouts.default')



@section('content')
<div class="container-fluid">
    <div class="row suda-row">
        
        <div class="page-heading">
            <h1 class="page-title">
                <i class="ion-document-text"></i>
                页面
            </h1>
            <div class="btn-groups">
                <a href="{{ admin_url('page/create') }}" class="btn btn-primary btn-sm pull-left">
                    <i class="zly-plus-circle"></i>&nbsp;增加页面
                </a>
                
                
            </div>

            <a href="{{ admin_url('page/list/deleted') }}" class="btn btn-warning btn-xs ml-auto">
                <i class="ion-trash"></i>&nbsp;回收站
            </a>
            
        </div>
        
        <div class="col-12 suda_page_body">
            <div class="d-flex mb-3">
                @include('view_suda::admin.page.filter')
            </div>
            
            @include('view_path::page.list_gallery')
            
        </div>
        
        
    </div>
</div>
@endsection


@push('scripts')



<script src="{{ suda_asset('js/waterfall/bootstrap-waterfall.js') }}"></script>



<script type="text/javascript">

    $(document).ready(function(){
        
        
        
        if($('#waterfall-container').length>0)
        {
            $('#waterfall-container').waterfall();
        }

        $('.btn-preview').suda_ajax({
            type:'GET',
            confirm:false,
        });
        
    });
    
</script>
@endpush