@extends('view_path::layouts.default')

@section('content')

<div class="container-fluid">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title">
                <i class="ion-newspaper"></i>
                文章
            </h1>
            <div class="btn-groups">
                <a href="{{ admin_url('article/create') }}" class="btn btn-primary btn-sm pull-left">
                    <i class="zly-plus-circle"></i>&nbsp;增加文章
                </a>
            </div>
        </div>

        

        <div class="col-12 suda_page_body">
            <div class="d-flex justify-content-between mb-3">
                @if(!isset($article_only_deleted))
                @include('view_suda::admin.article.filter_bar')
                @endif

                <div class="btn-groups">
                    
                    @if(!isset($article_only_deleted))
                    <a class="btn btn-dark btn-sm" href="{{ admin_url('articles/gallery') }}"><i class="ion-grid"></i></a>
                    <a class="btn btn-dark btn-sm" href="{{ admin_url('articles/list') }}"><i class="ion-reorder-four"></i></a>
                    
                    <button class="btn btn-primary btn-sm more-filter" data-element=".data-list">
                        <i class="ion-search-circle-outline"></i>&nbsp;高级搜索
                    </button>
                    @endif

                    <a class="btn btn-sm @if(isset($article_only_deleted)) btn-warning @else  btn-warning @endif" href="{{ admin_url('articles/deleted') }}">回收站</a>
                </div>

            </div>

            @include('view_path::article.'.$view_type)
            
        </div>
        
        
    </div>
</div>

@include('view_path::article.filter')

@endsection

@push('scripts')




@if($view_type=='list_gallery')

<script src="{{ suda_asset('js/waterfall/bootstrap-waterfall.js') }}"></script>

@endif


<script type="text/javascript">

    $(document).ready(function(){
        
        
        $('select.select-category').selectCategory('single');
        $('select.select-category-search').selectCategory('single');

        $('[data-toggle="datetimepicker"]').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            locale:'zh-cn',
            showClear:false,
            sideBySide:false,
            useCurrent:'day',
        });

        if($('#waterfall-container').length>0)
        {
            $('#waterfall-container').waterfall();
        }
        

        $('.more-filter').zfilter();

        $('.btn-restore').suda_ajax({
            type:'POST',
            title:'确认恢复?',
            confirm:true,
        });

        // $('.btn-preview').suda_ajax({
        //     type:'GET',
        //     confirm:false,
        // });
    });
    
</script>
@endpush


