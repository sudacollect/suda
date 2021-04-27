@if($cates)

    @if(isset($select))
        @php
            if(!is_array($select)){
                $select = (array)$select;
            }
        
        @endphp
    @else
    @php
        $select = [];
    @endphp
    @endif
    
    @if(!isset($exclude))
    @php
        $exclude = -1;
    @endphp
    @endif
    
@foreach ($cates as $cate)

@if($exclude!=$cate->id && $exclude!=$cate->parent)
<option value="{{ $cate->id }}" @if(in_array($cate->id,$select)) selected @endif>
{{ $cate->term->name }}
</option>
@endif

@if($cate->children && $exclude!=$cate->id)

    @include('view_suda::taxonomy.category.options',['cates'=>$cate->children,'child'=>$child+1,'select'=>$select,'exclude'=>$exclude])
    
@endif

@endforeach
@endif
