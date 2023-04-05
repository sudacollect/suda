@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        
        <h1 class="display-3 page-title page-tabs-title">{{ __('suda_lang::press.menu_items.tool_compass') }}</h1>

        @include('view_suda::admin.compass.tabs',['active'=>'index'])

        <div class="col-12 suda_page_body">

            
            
                <div class="card">
                        
                    @if($sysinfo)
                    <div class="card-body" >
                        <div class="certified-logo text-center">
                            <img src="{{ suda_asset('images/certified.png') }}" class="w-25" style="width:200px !important;">
                        </div>
                        
                        
                        <h2 class="card-title text-center">{{ $application['name'] }} v{{ $application['version'] }}</h2>

                        <ul class="list-group list-group-flush text-center">
                            <li class="list-group-item">
                                <a href="{{ admin_url('updateinfo') }}" class="badge rounded-pill bg-primary">version</a>
                            </li>
                        </ul>
                        
                        
                        
                    </div>
                    @else
                    <div class="card-body">

                        <div class="certified-logo text-center">
                            <img src="{{ suda_asset('images/uncertified.png') }}" class="w-25">
                        </div>
                        
                        <h2 style="text-align:center;">未授权 {{ $application['name'] }} {{ $application['version'] }}</h2>
                        <h4>说明</h4>
                        <p>未授权禁止用于任何商业用途。</p>
                        
                        <p>
                            通过 <a href="{{ url('https://suda.gtd.xyz') }}" title="GTD.SUDA" style="text-decoration:underline">Suda</a> 申请授权
                        </p>
                    
                        <p>
                            <a href="{{ admin_url('updateinfo') }}" title="GTD.SUDA" style="text-decoration:underline">查看更新</a>
                        </p>
                    </div>
                    @endif
                    
                </div>
            
        </div>
        

        
        
    </div>
</div>
@endsection
