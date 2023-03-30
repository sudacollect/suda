@if(isset($tags) && $tags)

<div class="col-sm-12 my-2 px-2">
    <span class="modal-tag-filter btn  @if(!$tag) btn-success @else btn-light @endif btn-sm" tag-id="0">所有</span>
@foreach($tags as $item)
    @if($item->term)
        <span class="modal-tag-filter btn @if($tag && $tag->id==$item->id) btn-success @else btn-light @endif btn-sm" tag-id="{{ $item->id }}">{{ $item->term->name }}</span>
    @endif
@endforeach
</div>
@endif

@if($medias->count()>0)

    <ul class="media-lists">
        @include('view_suda::media.modal.gallery_item',['medias'=>$medias])
    </ul>
    
    {{ $medias->links() }}

@else

<x-suda::emptyBlock :empty="没有媒体文件" :card=false />

@endif