@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        
        <div class="page-heading">
        <h1 class="page-title"><i class="far fa-id-badge"></i>&nbsp;{{ __('suda_lang::press.list') }}</h1>

        @if($soperate->user_role==9)
        <a href="{{ admin_url('user/roles/add') }}" class="btn btn-primary btn-sm"><i class="ion-add-circle"></i>&nbsp;{{ __('suda_lang::press.add') }}</a>
        @endif
        </div>
        
        
        @if($roles->count()>0)
        <div class="col-sm-12 suda_page_body">
            <div class="card">
                
                
                <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover">
                          <thead class="bg-light">
                            <tr>
                              <th>#</th>
                              <th>
                                  {{ __('suda_lang::press.role_name') }}
                              </th>
                              <th>
                                  {{ __('suda_lang::press.enable') }}
                              </th>
                              <th>
                                  {{ __('suda_lang::press.updated_at') }}
                              </th>
                              <th>
                                  {{ __('suda_lang::press.operate') }}
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($roles)
                            @foreach ($roles as $role)
                            <tr>
                              <td>{{ $role->id }}</td>
                              <td>
                                  <span class="badge bg-primary rounded-pill">
                                  {{ $role->authority_name }}
                                  </span>
                                  {{ $role->name }}
                              </td>
                              <td>@if($role->disable==1)否@else是@endif</td>
                              <td>{{ $role->updated_at }}</td>
                              <td>
                                  
                                  @if($soperate->user_role==9)
                                  <a href="{{ admin_url('user/roles/edit/'.$role->id) }}" class="btn btn-light btn-xs" title="{{ __('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}</a>
                                  @endif
                                  @if($role->authority=='extension')
                                  <a href="{{ admin_url('user/roles/showexts/'.$role->id) }}" class="btn btn-light btn-xs" title="应用权限设置" data-toggle="tooltip" data-placement="top"><i class="ion-filter-circle"></i>&nbsp;应用权限</a>
                                  @elseif($soperate->user_role >= 6)
                                  <a href="{{ admin_url('user/roles/showsys/'.$role->id) }}" class="btn btn-light btn-xs" title="系统权限" data-toggle="tooltip" data-placement="top"><i class="ion-list"></i>&nbsp;系统权限</a>
                                  @endif
                                  @if($soperate->user_role==9)
                                  <button href="{{ admin_url('user/roles/delete/'.$role->id) }}" class="pop-modal-delete btn btn-light btn-xs" data_id="{{ $role->id }}" title="{{ __('suda_lang::press.delete') }}" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i></button>
                                  @endif
                              </td>
                            </tr>
                            @endforeach
                            @endif
                          </tbody>
                      </table>
                      
                      {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
        
        @else
        
        <x-suda::empty-block empty="Oops... 还没有角色" type="user" :card=true />
        
        @endif
    </div>
</div>
@endsection
