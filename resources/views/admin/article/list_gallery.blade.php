



<div class="data-list" id="waterfall-container">

@if($data_list->count() > 0)
    
    @include('view_path::article.list_gallery_content')

@else


    <div class="col-sm-3">

        <div class="card mb-3">
            <div class="card-body">
                @include('view_suda::admin.component.empty',['without_card'=>true])
            </div>
        </div>

    </div>


@endif
</div>


@if($data_list->count() > 0)
<div class="col-12">
    {{ $data_list->appends($filter)->links() }}
</div>
@endif



