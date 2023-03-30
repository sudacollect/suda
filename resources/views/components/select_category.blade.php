<select class="select-category form-control" name="category[]" multiple="multiple" placeholder="{{ $placeholder }}">
    @if($cates)

        @if(isset($selected))
            @php
                if(!is_array($selected)){
                    $selected = (array)$selected;
                }
            
            @endphp
        @else
        @php
            $selected = [];
        @endphp
        @endif
        
        @if(!isset($exclude))
        @php
            $exclude = -1;
        @endphp
        @endif
        
    @foreach ($cates as $cate)

    @if($exclude!=$cate->id && $exclude!=$cate->parent)
    <option value="{{ $cate->id }}" @if(in_array($cate->id,$selected)) selected @endif>
    {{ $cate->term->name }}
    </option>
    @endif

    @if($cate->children && $exclude!=$cate->id)

        @include('view_suda::taxonomy.category.options',['cates'=>$cate->children,'child'=>$child+1,'select'=>$selected,'exclude'=>$exclude])
        
    @endif

    @endforeach
    @endif

</select>

@push('scripts')
<script type="text/javascript">
    
    $(function(){
        $('select.select-category').selectCategory("{{ $multi }}",'',"{{ $placeholder }}");
    });
    
</script>
@endpush