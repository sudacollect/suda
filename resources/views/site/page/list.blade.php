@extends('view_path::layouts.default')

@section('content')

<div class="col-lg-8 mx-auto pb-md-5 px-3">
    
    
    <h4 class="my-3">所有页面</h4>
    

    
    @if($data_list && $data_list->count()>0)
        <div class="row">

            @foreach ($data_list as $item)
            <div class="col-sm-3 mb-3">
                <div class="card">
                    <a href="{{ $item->real_url }}" title="{{ $item->title }}"><img src="{{ suda_image(isset($item->heroimage->media)?$item->heroimage->media:'',['size'=>'medium','imageClass'=>'image_icon',"url"=>true]) }}" class="card-img-top" alt="{{ $item->title }}"></a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ $item->real_url }}" title="{{ $item->title }}">{{ $item->title }}</a>
                        </h5>
                        <p class="card-text"><small class="text-muted">{{ $item->updated_at->format('Y-m-d') }}</small></p>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    
    @else
    
    @include('view_suda::admin.component.empty')
    
    @endif
    
</div>

@endsection