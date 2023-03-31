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
        $exclude = [];
    @endphp
    @endif
    
@foreach ($cates as $cate)

@if(!in_array($cate->id,$exclude) && !in_array($cate->parent,$exclude))
    <option value="{{ $cate->id }}" data-child="{{$child}}" @if(in_array($cate->id,$select)) selected @endif>
    {{ $cate->term->name }}
    </option>
@endif

@if($cate->children && !in_array($cate->id,$exclude))

    @include('view_suda::taxonomy.category.options',['cates'=>$cate->children,'child'=>$child+1,'select'=>$select,'exclude'=>$exclude])
    
@endif

@endforeach
@endif
