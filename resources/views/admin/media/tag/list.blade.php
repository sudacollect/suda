@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title"><i class="icon ion-pricetags"></i>&nbsp;媒体标签</h1>
            <a href="{{ admin_url($buttons['create']) }}" class="pop-modal btn btn-primary btn-sm pull-left"><i class="ion-add-circle"></i>&nbsp;增加标签</a>
        </div>
        <div class="col-12 suda_page_body">

          @include('view_path::media.header_tab')

            <div class="card card-with-tab">
              
                <div class="card-body">
                    <div class="table-responsive">
                      <table class="table">
                          <thead class="bg-light">
                            <tr>
                              <th width="20%">标签</th>
                              <th width="10%">别名</th>
                              <th width="15%">排序</th>
                              <th width="20%">更新时间</th>
                              <th>操作</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($tags)
                                
                                @foreach ($tags as $tag)
                                <tr>

                                  <td>
                                    <a href="{{ admin_url('media?tag_id='.$tag->id) }}">
                                      {{ $tag->term->name }}
                                      <span class="badge badge-light">{{ $tag->taxables_count }}</span>
                                    </a>
                                  </td>
                                  <td>{{ $tag->term->slug }}</td>
                                  <td>
      
      
                                      <div class="inline-edit-block">
                                          <span id="{{ $tag->id }}" class="inedit" edit-id="{{ $tag->id }}" edit-value="{{ $tag->sort }}">{{ $tag->sort }}</span>
                                          <a href="{{ admin_url('article/tag/editsort/'.$tag->id) }}" class="btn btn-sm inline-edit" title="设置排序"><i class="zly-pencil" style="color:#999;"></i></a>
                                      </div>
      
                                  </td>
                                  <td>{{ $tag->updated_at }}</td>
                                  <td>
                                    
                                    @if($tag->deleted_at)
                                      <a href="{{ admin_url($buttons['restore'].'/'.$tag->id) }}" class="pop-modal btn btn-light btn-xs" title="恢复使用" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;恢复标签</a>
                                      <button href="{{ admin_url($buttons['forcedelete'].'/'.$tag->id) }}" class="pop-modal-delete btn btn-light btn-xs" data_id="{{ $tag->id }}" title="{{ __('suda_lang::press.delete') }}" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i>&nbsp;强制删除</button>
                                    @else
                                      <a href="{{ admin_url($buttons['update'].'/'.$tag->id) }}" class="pop-modal btn btn-light btn-xs" title="{{ __('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}</a>
                                      <button href="{{ admin_url($buttons['delete'].'/'.$tag->id) }}" class="pop-modal-delete btn btn-light btn-xs" data_id="{{ $tag->id }}" title="{{ __('suda_lang::press.delete') }}" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i></button>
                                    @endif

                                  </td>
                                </tr>

                                @endforeach
                                
                            @endif
                          </tbody>
                      </table>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
