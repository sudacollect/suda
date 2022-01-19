@foreach ($cates as $cate)
<tr>

  <td @if($child>0) style="border-top:none" @endif>
      
      @if($child>0)
      <span style="margin-left:{{ ($child-1)*20 }}px;color:#999;">&#x2514;</span>
      @endif
      
      {{ $cate->term->name }}
  </td>
  <td @if($child>0) style="border-top:none" @endif >{{ $cate->term->slug }}</td>
  <td @if($child>0) style="border-top:none" @endif >
      
      
      <div class="inline-edit-block">
          <span id="{{ $cate->id }}" class="inedit" edit-id="{{ $cate->id }}" edit-value="{{ $cate->sort }}">{{ $cate->sort }}</span>
          <a href="{{ admin_url('article/category/editsort/'.$cate->id) }}" class="btn btn-sm inline-edit" title="设置排序"><i class="ion-create" style="color:#999;"></i></a>
      </div>
      
  </td>
  <td @if($child>0) style="border-top:none" @endif >{{ $cate->updated_at }}</td>
  <td @if($child>0) style="border-top:none" @endif >
      <a href="{{ admin_url('article/category/update/'.$cate->id) }}" class="pop-modal btn btn-success btn-sm" title="{{ __('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}</a>
      @if($cate->term->slug!='default')
      <button href="{{ admin_url('article/category/delete/'.$cate->id) }}" class="pop-modal-delete btn btn-danger btn-sm"  data_id="{{ $cate->id }}" title="{{ __('suda_lang::press.delete') }}" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i></button>
      @endif
  </td>
</tr>

@if($cate->children)
    
    @include('view_suda::admin.article.category.list_content',['cates'=>$cate->children,'child'=>$child+1])
    
@endif

@endforeach
