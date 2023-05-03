@extends('view_path::layouts.default')

@section('content')
<style>
    .page-pages .card-title a:hover{
        color:#0088ff!important;
    }
    .waterfall-item{
        flex-shrink: 0;
        padding-right: calc(var(--bs-gutter-x)*0.5);
        padding-left: calc(var(--bs-gutter-x)*0.5);
        margin-top: var(--bs-gutter-y);
    }
</style>

<div class="container-fluid page-pages pb-md-5">
    <h4 class="my-3">所有页面</h4>
    @if($data_list && $data_list->count()>0)
        <div class="row" id="waterfall-container">

            @foreach ($data_list as $item)
            <div class="col-sm-3 mb-3 waterfall-item">
                <div class="card border-light shadow-sm">
                    <a href="{{ $item->real_url }}" title="{{ $item->title }}">
                        <img src="{{ suda_image(isset($item->heroimage->media)?$item->heroimage->media:'',['size'=>'medium',"url"=>true]) }}" class="card-img-top w-100" alt="{{ $item->title }}">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ $item->real_url }}" title="{{ $item->title }}" class="link-dark">{{ $item->title }}</a>
                        </h5>
                        <p class="card-text"><small class="text-muted">{{ $item->updated_at->format('Y-m-d') }}</small></p>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="col-12">
                {{ $data_list->links() }}
            </div>
        </div>
    
    @else
    
    <x-suda::empty-block />
    
    @endif
    
</div>

@endsection

@push('scripts')
<script src="{{ suda_asset('js/waterfall/bootstrap-waterfall.js') }}"></script>
<script type="text/javascript">
    $(function(){
        if($('#waterfall-container').length>0)
        {
            $('#waterfall-container').waterfall();
        }
    });
</script>
@endpush