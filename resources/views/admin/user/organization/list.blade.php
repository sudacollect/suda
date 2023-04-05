@extends('view_path::layouts.default')



@section('content')
<div class="container-fluid">
    <div class="row suda-row">
        <div class="page-heading">
        <h1 class="page-title"><i class="ion-business"></i>&nbsp;{{ __('suda_lang::press.organization') }}</h1>
        @if(\Gtd\Suda\Auth\OperateCan::superadmin($soperate))
        <a href="{{ admin_url('user/organization/add') }}" class="pop-modal btn btn-primary btn-sm"><i class="ion-add-circle"></i>&nbsp;{{ __('suda_lang::press.add') }}</a>
        @endif
        </div>
        
        
        @if($categories && $categories->count()>0)
        <div class="col-sm-12 suda_page_body">
            <div class="card">
                
                <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover">
                          <thead class="bg-light">
                            <tr>
                              <th width="15%">
                                  IMG
                              </th>
                              <th width="20%">
                                  {{ __('suda_lang::press.organization_name') }}
                              </th>
                              <th width="10%">
                                  {{ __('suda_lang::press.enable') }}
                              </th>
                              <th width="15%">
                                  {{ __('suda_lang::press.updated_at') }}
                              </th>

                              @if(\Gtd\Suda\Auth\OperateCan::superadmin($soperate))
                              <th>
                                  {{ __('suda_lang::press.operate') }}
                              </th>
                              @endif
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($categories as $org)
                            <tr>
                              <td width="15%">
                                  {!! suda_image($org->logo,['size'=>'small','imageClass'=>'image_icon']) !!}
                              </td>
                              <td width="20%">{{ $org->term->name }}</td>
                              <td width="10%">@if($org->disable==1)否@else是@endif</td>
                              <td width="15%">{{ $org->updated_at }}</td>
                              @if(\Gtd\Suda\Auth\OperateCan::superadmin($soperate))
                              <td>
                                  <a href="{{ admin_url('user/organization/edit/'.$org->id) }}" class="pop-modal btn btn-light btn-xs" title="{{ __('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}</a>
                                  <button href="{{ admin_url('user/organization/delete/'.$org->id) }}" class="pop-modal-delete btn btn-light btn-xs" data_id="{{ $org->id }}" title="{{ __('suda_lang::press.delete') }}" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i></button>
                              </td>
                              @endif
                            </tr>
                            @endforeach
                            
                          </tbody>
                      </table>
                      
                      {{ $categories->links() }}
                    </div>
                </div>
                
            </div>
        </div>
        
        @else
        
        <x-suda::empty-block empty="Oops... 还没有部门" type='user' :card=true />
        
        
        @endif
    </div>
</div>
@endsection
