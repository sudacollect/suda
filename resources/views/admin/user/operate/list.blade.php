@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title">
                <i class="zly-user"></i>
                @if(isset($deleted))
                已删除管理员
                @else
                管理员
                @endif
            </h1>
            @if(!isset($deleted))
            <a href="{{ admin_url('manage/operates/add') }}" class="pop-modal btn btn-primary btn-sm pull-left"><i class="zly-plus-circle"></i>&nbsp;{{ trans('suda_lang::press.btn.add') }}</a>

            @if($soperate->superadmin==1 || $soperate->user_role>=6)
            <a href="{{ admin_url('manage/operates/deleted') }}" class="btn btn-warning btn-sm ml-auto"><i class="ion-person-remove-outline text-muted"></i>&nbsp;已删除</a>
            @endif

            @endif
        </div>
        
        @if($operates->count()>0)
        
        <div class="col-sm-12 suda_page_body">
            <div class="card">
                
                <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover">
                          <thead class="bg-light text-muted">
                            <tr>
                              <th>#</th>
                              <th>
                                  {{ trans('suda_lang::press.username') }}
                              </th>
                              <th>
                                  {{ trans('suda_lang::press.email') }}
                              </th>
                              <th>
                                  {{ trans('suda_lang::press.phone') }}
                              </th>
                              <th>
                                  {{ trans('suda_lang::press.organization') }}
                              </th>
                              <th>
                                  {{ trans('suda_lang::press.role') }}
                              </th>
                              <th>
                                  {{ trans('suda_lang::press.updated_at') }}
                              </th>
                              <th>
                                  {{ trans('suda_lang::press.enable') }}
                              </th>
                              <th>
                                  {{ trans('suda_lang::press.operate') }}
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($operates)
                            @foreach ($operates as $operate)
                            <tr>
                              <td>{{ $operate->id }}</td>
                              <td>@if(isset($operate->username)){{ $operate->username }} @endif</td>
                              <td>@if(isset($operate->email)){{ $operate->email }} @endif</td>
                              <td>@if(isset($operate->phone)){{ $operate->phone }} @endif</td>
                              <td>
                                  @if($operate->categories)
                                  @foreach($operate->categories as $cate)
                                  @if($cate->taxonomy && $cate->taxonomy->term)
                                  <span class="badge bg-light text-dark">{{ $cate->taxonomy->term->name }}</span><br>
                                  @endif
                                  @endforeach
                                  @endif
                              </td>
                              <td>
                                  @if($operate->role)
                                  {{ $operate->role->name }} 
                                  @elseif($operate->superadmin==1)
                                  <span class="label label-success">super</span>
                                  @endif
                                </td>
                              
                              
                             
                              <td>{{ $operate->updated_at }}</td>
                              <td>
                                  @if($operate->enable==1)
                                  是
                                  @else
                                  否
                                  @endif
                              </td>
                              <td>
                                @if(isset($deleted))
                                    @if($soperate->superadmin==1 || ($soperate->user_role>=6 && $soperate->user_role >= $operate->user_role))
                                        <button href="{{ admin_url('manage/operates/restore/'.$operate->id) }}" data_id="{{ $operate->id }}" data_title="确认恢复用户?" class="pop-modal-box btn btn-warning btn-xs" title="{{ trans('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top">恢复</button>
                                    @endif

                                    @if($soperate->superadmin==1)
                                    <button href="{{ admin_url('manage/operates/delete/'.$operate->id.'/force') }}" class="pop-modal-delete btn btn-danger btn-xs" data_id="{{ $operate->id }}" title="{{ trans('suda_lang::press.delete') }}" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i>&nbsp;删除</button>
                                    @endif

                                @else
                                  
                                  @if($soperate->id!=$operate->id )
                                  <a href="{{ admin_url('manage/operates/edit/'.$operate->id) }}" class="pop-modal btn btn-light btn-xs" title="{{ trans('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ trans('suda_lang::press.edit') }}</a>
                                  <button href="{{ admin_url('manage/operates/delete/'.$operate->id) }}" class="pop-modal-delete btn btn-light btn-xs" data_id="{{ $operate->id }}" title="{{ trans('suda_lang::press.delete') }}" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i></button>
                                  @endif

                                @endif
                              </td>
                            </tr>
                            @endforeach
                            @endif
                          </tbody>
                      </table>
                      
                      {{ $operates->links() }}
                    </div>
                </div>
                
            </div>
        </div>
        
        @else
        
        @include('view_suda::admin.component.empty',['type'=>'user','empty'=>'Oops... 还没有信息'])
        
        @endif
    </div>
</div>
@endsection
