<style>
    .waterfall-item{
        flex-shrink: 0;
        
        padding-right: calc(var(--bs-gutter-x)*0.5);
        padding-left: calc(var(--bs-gutter-x)*0.5);
        margin-top: var(--bs-gutter-y);
    }
</style>

@if($data_list->count() > 0)
<div class="data-list row" id="waterfall-container">
    @include('view_path::article.list_gallery_content')
</div>
<div class="col-12">
    {{ $data_list->appends($filter)->links() }}
</div>
@else


<div class="card mb-3">
    <div class="card-body">
        <x-suda::empty-block :card=false />
    </div>
</div>


@endif




