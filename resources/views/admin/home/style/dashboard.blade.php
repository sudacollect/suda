@extends('view_path::layouts.default')



@section('content')
<div class="container-fluid">
    
    <div class="row suda-row dashboard dashboard-theme">
        <div class="page-heading">
            <h1 class="page-title">
                {{ __('suda_lang::press.dash.theme') }}
            </h1>

            <div class="btn-toolbar ms-auto" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group" role="group" aria-label="First group">

                    <button type="button" href="{{ admin_url('style/dashboard.layout') }}" class="pop-modal btn btn-sm btn-light me-2">
                        {{ __('suda_lang::press.appearance.set_style') }}
                    </button>

                    <button type="button" href="{{ admin_url('theme/updatecache/admin') }}" class="modal-action btn btn-sm btn-light">
                        {{ __('suda_lang::press.update_cache') }}
                    </button>

                </div>
            </div>

        </div>
        
        
        <div class="col-12 suda_page_body">
            
            @if(isset($themes))

            <div class="card card-theme mb-3 border-0 bg-transparent" style="box-shadow:none;">
                
                <div class="card-body" >
                    

                    @if(isset($theme) && is_array($theme))

                    <div class="row">

                        <div class="col-sm-3 ">
                            <div class="theme-info theme-info-using">
                                <div class="screenshot">
                                    @if(isset($theme['screenshot']))
                                    <img src="{{ asset($theme['screenshot']) }}">
                                    @endif
                                </div>
                                <div class="text-center">
                                    <i class="ion-compass text-primary"></i>
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-sm-6">
                            <div class="theme-support">
                                <p><b><i class="ion-compass-outline"></i>&nbsp;{{ $theme['name'] }} v{{ $theme['version'] }}</b></p>
                                <p><i class="ion-information-circle-outline"></i>&nbsp;{{ $theme['description'] }}</p>
                                <p><i class="ion-at-outline"></i>&nbsp;<a target="_blank" href="{{ $theme['author_url'] }}">{{ $theme['author'] }}</a></p>
                            </div>
                        </div>
                    
                    </div>

                    @endif
                
                </div>
            
            </div>

            @endif

            <div class="card">
                <div class="card-header bg-white">
                    {{ __('suda_lang::press.dash.theme-list') }}
                </div>
                <div class="card-body">

                    @if(isset($themes) && !empty($themes))

                        <div class="row">

                            @foreach($themes as $kk=>$theme)
                        
                                @if( $current_theme != $kk )
                            
                            
                                <form class="col-sm-3 form-ajax" action="{{ admin_url('style/dashboard/save') }}">
                                    @csrf
                                    <input type="hidden" name="app_name" value="admin">

                                    <div class="theme-info">
                                        <div class="screenshot">
                                            <div class="screenshot-cover"></div>
                                            @if(isset($theme['screenshot']))
                                            <img src="{{ asset($theme['screenshot']) }}" class="mw-100">
                                            @endif
                                        </div>
                                        <div class="text-center">
                                            @if(isset($theme['name'])) {{ $theme['name'] }} @else unkown @endif
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-light btn-sm">
                                                {{ __('suda_lang::press.dash.theme-enable') }}
                                            </button>
                                            <a href="{{ admin_url('style/preview/'.$kk) }}" target="_blank" class="btn btn-light btn-sm">
                                                {{ __('suda_lang::press.dash.theme-preview') }}
                                            </a>
                                            <input type="hidden" name="theme_name" value="{{ $kk }}">
                                        </div>
                                    </div>
                                </form>
                            
                            
                                @endif
                    
                            @endforeach
                    
                        </div>
                        
                    
    
                    @else
    
                        暂无风格主题
    
                    @endif

                </div>
            </div>
            
        </div>
        
    </div>
    
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    $('.modal-action').suda_ajax({
        type:'POST',
        confirm:false,
    });
});
</script>

@endpush