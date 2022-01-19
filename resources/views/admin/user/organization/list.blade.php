@extends('view_path::layouts.default')



@section('content')
<div class="container-fluid">
    <div class="row suda-row">
        <div class="page-heading">
        <h1 class="page-title"><i class="zly-building"></i>&nbsp;{{ __('suda_lang::press.organization') }}</h1>
        @if($soperate->user_role==9)
        <a href="{{ admin_url('user/organization/add') }}" class="btn btn-primary btn-sm"><i class="ion-add-circle"></i>&nbsp;{{ __('suda_lang::press.add') }}</a>
        @endif
        </div>
        
        
        @if($orgs->count()>0)
        <div class="col-sm-12 suda_page_body">
            <div class="card">
                
                <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover">
                          <thead class="bg-light">
                            <tr>
                              <th>#</th>
                              <th>
                                  {{ __('suda_lang::press.organization_name') }}
                              </th>
                              <th>
                                  {{ __('suda_lang::press.enable') }}
                              </th>
                              <th>
                                  {{ __('suda_lang::press.updated_at') }}
                              </th>

                              @if($soperate->user_role==9)
                              <th>
                                  {{ __('suda_lang::press.operate') }}
                              </th>
                              @endif
                            </tr>
                          </thead>
                          <tbody>
                            @if($orgs)
                            @foreach ($orgs as $org)
                            <tr>
                              <td>{{ $org->id }}</td>
                              <td>{{ $org->name }}</td>
                              <td>@if($org->disable==1)否@else是@endif</td>
                              <td>{{ $org->updated_at }}</td>
                              @if($soperate->user_role==9)
                              <td>
                                  <a href="{{ admin_url('user/organization/edit/'.$org->id) }}" class="btn btn-light btn-xs" title="{{ __('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}</a>
                                  <button href="{{ admin_url('user/organization/delete/'.$org->id) }}" class="pop-modal-delete btn btn-light btn-xs" data_id="{{ $org->id }}" title="{{ __('suda_lang::press.delete') }}" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i></button>
                              </td>
                              @endif
                            </tr>
                            @endforeach
                            @endif
                          </tbody>
                      </table>
                      
                      {{ $orgs->links() }}
                    </div>
                </div>
                
            </div>
        </div>
        
        @else
        
        @include('view_suda::admin.component.empty',['type'=>'user','empty'=>'Oops... 还没有部门'])
        
        @endif
    </div>
</div>
@endsection
