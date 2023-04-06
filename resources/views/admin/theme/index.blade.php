@extends('view_path::layouts.default')

@section('content')
<div class="container">
    <div class="row suda-row">
        
        <div class="page-heading">
            <h1 class="page-title">
                {{ __('suda_lang::press.menu_items.appearance_theme') }}
            </h1>

            <form class="form-ajax" action="{{ admin_url('theme/updatecache/'.$app_name) }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-primary">
                    {{ __('suda_lang::press.update_cache') }}
                </button>
            </form>
        </div>
        
        
        
        <div class="col-sm-12 suda_page_body">
            <ul class="nav nav-tabs card-tabs">
                @if(isset($apps))
                
                @foreach($apps as $zapp)

                @if($zapp!='admin')
                <li class="nav-item">
                    <a class="nav-link  @if($app_name==$zapp) bg-white active @endif " href="{{ admin_url('theme/'.$zapp) }}">{{ $zapp }}</a>
                </li>
                @endif
                

                @endforeach

                @endif
                
            </ul>

            <div class="card card-theme card-with-tab">
                    
                <div class="card-body">
                    
                    @if(isset($theme_info))
                    <div class="row">
                        <div class="col-sm-3 ">
                            <div class="theme-info theme-info-using">
                                <div class="screenshot">
                                    @if(isset($theme_info['screenshot']))
                                    <img src="{{ asset($theme_info['screenshot']) }}">
                                    @endif
                                </div>
                                <div class="text-center">
                                    <i class="ion-compass text-primary"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6 ">
                            <div class="theme-support">
                                <p><i class="ion-compass-outline me-2"></i><b class="me-2">{{ $theme_info['name'] }}</b>v{{ $theme_info['version'] }}</p>
                                <p><i class="ion-information-circle-outline me-2"></i>{{ $theme_info['description'] }}</p>
                                <p><i class="ion-at-outline me-2"></i><a target="_blank" href="{{ $theme_info['author_url'] }}">{{ $theme_info['author'] }}</a></p>
                                <p><a href="{{ admin_url('widget/'.$app_name.'/'.$current_theme) }}" class="btn btn-light btn-sm">{{ __('suda_lang::press.appearance.set_widget') }}</a></p>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
                
            </div>

            <div class="card card-theme mt-3">

                <div class="card-body">
                    
                    @if(isset($themes) && !empty($themes))

                        @foreach($themes as $kk=>$theme)
                        
                            @if( $current_theme != $kk )

                            <form class="form-ajax" action="{{ admin_url('theme/settheme') }}">
                                @csrf
                                <input type="hidden" name="app_name" value="{{ $app_name }}">
                                <div class="theme-info col-sm-3">
                                    <div class="screenshot">
                                        @if(isset($theme['screenshot']))
                                        <img src="{{ asset($theme['screenshot']) }}">
                                        @endif
                                    </div>
                                    <div class="text-center text-dark font-weight-bold">
                                        {{ $theme['name'] }}
                                    </div>
                                    <div class="mt-3 text-center text-dark">
                                        <button type="submit" class="btn btn-light btn-sm">{{ __('suda_lang::press.enable') }}</button>
                                        <a href="{{ admin_url('widget/'.$app_name.'/'.$kk) }}" class="btn btn-light btn-sm">{{ __('suda_lang::press.appearance.set_widget') }}</a>
                                        <a href="{{ url('/?theme_preview='.$kk) }}" target="_blank" class="btn btn-light btn-sm">{{ __('suda_lang::press.preview') }}</a>
                                        <input type="hidden" name="theme_name" value="{{ $kk }}">
                                    </div>
                                </div>
                            </form>
                            
                            
                            @endif
                    
                        @endforeach
        
                    @else
        
                        当前应用下无任何主题风格 <br>

                        <strong>请上传主题至 public/theme/{{ $app_name }}</strong>
        
                    @endif

                </div>

            </div>

        </div>
        
        

        
        
    </div>
</div>
@endsection


@push('scripts')

<script type="text/javascript">
    
    $(document).ready(function(){
        $('#change-app').on('change',function(e){

            var zapp = $( "select#change-app option:selected" ).attr('value');
            window.location.href = suda.link(suda.data('adminPath')+'/theme/'+zapp);

        });
    });
</script>

@endpush