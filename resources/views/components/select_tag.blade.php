<select name="{{ $name }}" class="x-suda-select-tag form-control" data-max="{{ $max }}" data-href="{{ $link }}" data-taxonomy="{{ $taxonomy }}" multiple="multiple" placeholder="{{ $placeholder }}">
    @if($tags->count() > 0)
        @foreach($tags as $tag)
            <option value="{{ $tag->name }}" selected>{{ $tag->name }}</option>
        @endforeach
    @endif
</select>

@pushOnce('scripts')
<script type="text/javascript">
    $(function(){
        $('select.x-suda-select-tag').selectTag();
    })
</script>
@endpushOnce