@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title">
                <i class="ion-person"></i>
                @if(isset($deleted))
                {{ __('suda_lang::press.recycle') }}
                @else
                {{ __('suda_lang::press.menu_items.setting_operate') }}
                @endif
            </h1>
            @if(!isset($deleted))
            <a href="{{ admin_url('manage/operates/add') }}" class="pop-modal btn btn-primary btn-sm pull-left"><i class="ion-add-circle"></i>&nbsp;{{ __('suda_lang::press.btn.add') }}</a>

            @if(\Gtd\Suda\Auth\OperateCan::operation($soperate))
            <a href="{{ admin_url('manage/operates/deleted') }}" class="btn btn-warning btn-sm ms-auto"><i class="ion-person-remove-outline text-muted"></i>&nbsp;{{ __('suda_lang::press.recycle') }}</a>
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
                                  {{ __('suda_lang::press.username') }}
                              </th>
                              <th>
                                  {{ __('suda_lang::press.email') }}
                              </th>
                              <th>
                                  {{ __('suda_lang::press.phone') }}
                              </th>
                              <th>
                                  {{ __('suda_lang::press.organization') }}
                              </th>
                              <th>
                                  {{ __('suda_lang::press.role') }}
                              </th>
                              <th>
                                  {{ __('suda_lang::press.updated_at') }}
                              </th>
                              <th>
                                  {{ __('suda_lang::press.enable') }}
                              </th>
                              <th>
                                  {{ __('suda_lang::press.operation') }}
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
                                  {{ __('suda_lang::press.yes') }}
                                  @else
                                  {{ __('suda_lang::press.no') }}
                                  @endif
                              </td>
                              <td>
                                @if(isset($deleted))
                                    @if(\Gtd\Suda\Auth\OperateCan::superadmin($soperate))
                                        <button href="{{ admin_url('manage/operates/restore/'.$operate->id) }}" action_id="{{ $operate->id }}" action_title="Confirmed?" class="x-suda-pop-action btn btn-warning btn-xs" title="{{ __('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top">恢复</button>
                                    @endif

                                    @if(\Gtd\Suda\Auth\OperateCan::superadmin($soperate))
                                        <button href="{{ admin_url('manage/operates/delete/'.$operate->id.'/force') }}" class="pop-modal-delete btn btn-danger btn-xs" data_id="{{ $operate->id }}" title="{{ __('suda_lang::press.delete') }}" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i>&nbsp;删除</button>
                                    @endif

                                @else
                                  
                                  @if($soperate->id!=$operate->id )
                                  <a href="{{ admin_url('manage/operates/edit/'.$operate->id) }}" class="pop-modal btn btn-light btn-xs" title="{{ __('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}</a>
                                  <button href="{{ admin_url('manage/operates/delete/'.$operate->id) }}" class="pop-modal-delete btn btn-light btn-xs" data_id="{{ $operate->id }}" title="{{ __('suda_lang::press.delete') }}" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i></button>
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
        
        <x-suda::empty-block empty="Oops... 还没有信息" type="user" :card=true />
        
        @endif
    </div>
</div>
@endsection
