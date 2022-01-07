@foreach ($cates as $cate)
<tr data_child = "{{ $child }}" @if(isset($toggle) && $toggle==1) style="display:none" @endif>

  <td @if($child>0) style="border-top:none" @endif>
      @if($child>0)
      <span style="color:#999;">&#x251C;{!! str_repeat('&#x2500;',$child-1) !!}</span>
      @endif
      {{ $cate->term->name }}
      @if($child<=0)
      <button class="btn btn-xs btn-light category-toggle @if($cate->toggle==1) toggle-close @else toggle-open @endif" data-id="{{ $cate->id }}">
        @if($cate->toggle==1)
        <i class="ion-chevron-down"></i>
        @else
        <i class="ion-chevron-up"></i>
        @endif
      </button>
      <a href="{{ admin_url($buttons['create'].'/'.$cate->id) }}" class="pop-modal btn btn-light btn-xs" data-modal-id="add-taxonomy-category" title="{{ __('suda_lang::press.btn.add') }}"><i class="ion-add"></i></a>
      @endif
      
  </td>
  <td @if($child>0) style="border-top:none" @endif >{{ $cate->term->slug }}</td>
  <td @if($child>0) style="border-top:none" @endif >
      
      
      <div class="inline-edit-block">
          <span id="{{ $cate->id }}" class="inedit" edit-id="{{ $cate->id }}" edit-value="{{ $cate->sort }}">{{ $cate->sort }}</span>
          <a href="{{ admin_url($buttons['sort'].'/'.$cate->id) }}" class="btn btn-xs inline-edit" title="设置排序"><i class="ion-create" style="color:#999;"></i></a>
      </div>
      
  </td>
  <td @if($child>0) style="border-top:none" @endif >{{ $cate->updated_at }}</td>
  <td @if($child>0) style="border-top:none" @endif >
      <a href="{{ admin_url($buttons['update'].'/'.$cate->id) }}" class="pop-modal btn btn-light btn-xs" title="{{ __('suda_lang::press.edit') }}"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}</a>
      @if($cate->term->slug!='default')
        <button href="{{ admin_url($buttons['delete'].'/'.$cate->id) }}" class="pop-modal-delete btn btn-light btn-xs" data_id="{{ $cate->id }}" title="{{ __('suda_lang::press.delete') }}">
            <i class="ion-trash"></i>
        </button>
      @endif
  </td>
</tr>

@if($cate->children)
    
    @include('view_suda::taxonomy.category.list_content',['cates'=>$cate->children,'child'=>$child+1,'toggle'=>((isset($toggle) && $toggle==1) || $cate->toggle==1)?1:0])
    
@endif

@endforeach
