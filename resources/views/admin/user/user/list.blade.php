@extends('view_path::layouts.default')



@section('content')
<div class="container container-fluid">
    <div class="page-heading">
        <h1 class="page-title">
            <i class="ion-people"></i>&nbsp;{{ __('suda_lang::press.user_list') }}
        </h1>
        <a href="{{ admin_url('user/add') }}" class="pop-modal btn btn-primary btn-sm"><i class="ion-add-circle"></i>&nbsp;{{ __('suda_lang::press.btn.add') }}</a>
    </div>

    <div class="row suda-row">
        
        
        @if($users->count()>0)
        
        <div class="col-12 suda_page_body">
            <div class="card">
                
                <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover">
                          <thead class="bg-light">
                            <tr>
                              <th>#</th>
                              <th>
                                  {{ __('suda_lang::press.username') }}
                              </th>
                              <th>
                                  {{ __('suda_lang::press.email') }}
                              </th>
                              
                              <th>
                                  {{ __('suda_lang::press.created_at') }}
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
                            @if($users)
                            @foreach ($users as $user)
                            <tr>
                              <td>{{ $user->id }}</td>
                              <td>@if(isset($user->name)){{ $user->name }} @endif</td>
                              <td>@if(isset($user->email)){{ $user->email }} @endif</td>
                              
                               <td>{{ $user->created_at }}</td>
                              <td>{{ $user->updated_at }}</td>
                              
                              <td>
                                  <a href="{{ admin_url('user/edit/'.$user->id) }}" class="pop-modal btn btn-light btn-xs" title="{{ __('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}</a>
                                  <button href="{{ admin_url('user/delete/'.$user->id) }}" class="pop-modal-delete btn btn-light btn-xs" data_id="{{ $user->id }}" title="{{ __('suda_lang::press.delete') }}" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i></button>
                              </td>
                            </tr>
                            @endforeach
                            @endif
                          </tbody>
                      </table>
                      
                      {{ $users->links() }}
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
