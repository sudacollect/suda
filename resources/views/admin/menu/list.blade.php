@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        
        <div class="page-heading">
            <h1 class="page-title text-nowrap">
                <i class="ion-list"></i>
                {{ __('suda_lang::press.setting_menu') }}
            </h1>
            <div class="btn-groups ms-auto">
                <a href="{{ admin_url('menu/add') }}" class="pop-modal btn btn-light btn-sm text-nowrap">
                    <i class="ion-add-circle"></i>&nbsp;{{ __('suda_lang::press.add_menu') }}
                </a>
            </div>
        </div>
        <div class="help-block col-12">
            {{ __('suda_lang::press.setting_menu_desc') }}
        </div>
        
        <div class="col-12 suda_page_body">
            <div class="card">
                   
                <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover">
                          <thead class="bg-light">
                            <tr>
                              <th>#</th>
                              <th>
                                  {{ __('suda_lang::press.name') }}
                              </th>
                              <th>
                                  code
                              </th>
                              <th>
                                  {{ __('suda_lang::press.created_at') }}
                              </th>
                              <th>
                                  {{ __('suda_lang::press.operate') }}
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            @if(isset($menus))
                            @foreach ($menus as $menu)
                            <tr>
                              <td>{{ $menu->id }}</td>
                              
                              <td>@if(isset($menu->name)){{ $menu->name }} @endif</td>
                              <td><code>menu('{{ $menu->name }}')</code></td>
                              <td>{{ $menu->created_at }}</td>
                              
                              <td>
                                  <a href="{{ admin_url('menu/items/'.$menu->id) }}" class="btn btn-light btn-xs" title="{{ __('suda_lang::press.menu_item') }}" data-toggle="tooltip" data-placement="top"><i class="ion-list"></i>&nbsp;{{ __('suda_lang::press.menu_item') }}</a>
                                  @if($menu->id>1)
                                  <a href="{{ admin_url('menu/edit/'.$menu->id) }}" class="pop-modal btn btn-primary btn-xs" title="{{ __('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}</a>
                                  <button href="{{ admin_url('menu/delete/'.$menu->id) }}" class="pop-modal-delete btn btn-light btn-xs" data_id="{{ $menu->id }}" title="{{ __('suda_lang::press.delete') }}" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i>&nbsp;{{ __('suda_lang::press.delete') }}</button>
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
