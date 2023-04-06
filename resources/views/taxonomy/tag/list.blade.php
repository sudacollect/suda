@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title"><i class="icon ion-pricetags"></i>&nbsp;{{ __('suda_lang::press.tags.tag') }}</h1>
            <a href="{{ $buttons['create'] }}" class="pop-modal btn btn-primary btn-sm pull-left"><i class="ion-add-circle"></i>&nbsp;{{ __('suda_lang::press.add') }}</a>
        </div>
        <div class="col-12 suda_page_body">

        
            <div class="card">
                
                <div class="card-body">
                    <div class="table-responsive">
                      <table class="table">
                          <thead class="bg-light">
                            <tr>
                              <th width="20%">{{ __('suda_lang::press.name') }}</th>
                              <th width="10%">{{ __('suda_lang::press.slug') }}</th>
                              <th width="15%">{{ __('suda_lang::press.sort') }}</th>
                              <th width="20%">{{ __('suda_lang::press.updated_at') }}</th>
                              <th>{{ __('suda_lang::press.operation') }}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($tags)
                                
                                @foreach ($tags as $tag)
                                <tr>

                                  <td>
                                      {{ $tag->term->name }}
                                  </td>
                                  <td>{{ $tag->term->slug }}</td>
                                  <td>
      
      
                                      <div class="inline-edit-block">
                                          <span id="{{ $tag->id }}" class="inedit" edit-id="{{ $tag->id }}" edit-value="{{ $tag->sort }}">{{ $tag->sort }}</span>
                                          <a href="{{ admin_url('article/tag/editsort/'.$tag->id) }}" class="btn btn-sm inline-edit" title="{{ __('suda_lang::press.set_sort') }}"><i class="ion-create" style="color:#999;"></i></a>
                                      </div>
      
                                  </td>
                                  <td>{{ $tag->updated_at }}</td>
                                  <td>
                                    
                                    @if($tag->deleted_at)
                                      <a href="{{ $buttons['restore'].'/'.$tag->id }}" class="pop-modal btn btn-light btn-xs" title="恢复使用" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.recycle') }}</a>
                                      <button href="{{ $buttons['forcedelete'].'/'.$tag->id }}" class="pop-modal-delete btn btn-light btn-xs" data_id="{{ $tag->id }}" title="{{ __('suda_lang::press.delete') }}" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i>&nbsp;{{ __('suda_lang::press.btn.delete') }}</button>
                                    @else
                                      <a href="{{ $buttons['update'].'/'.$tag->id }}" class="pop-modal btn btn-light btn-xs" title="{{ __('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}</a>
                                      <button href="{{ $buttons['delete'].'/'.$tag->id }}" class="pop-modal-delete btn btn-light btn-xs" data_id="{{ $tag->id }}" title="{{ __('suda_lang::press.delete') }}" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i></button>
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
