@extends('view_path::layouts.default')

@section('content')
<div class="container">
    <div class="row suda-row">
        
        <div class="page-heading">
            <h1 class="page-title">
                模板管理<span class="help-block">所有{{ $app_name }}模板在这里管理</span>
            </h1>

            @if(isset($apps))
            <div  class="ml-auto mr-2">
                <select id="change-app" class="form-control form-control-sm">
                    @foreach($apps as $zapp)
                    
                    @if($zapp!='admin')
                    <option value="{{ $zapp }}" @if($app_name==$zapp) selected @endif>{{ $zapp }}</option>
                    @endif

                    @endforeach
                </select>
            </div>
            
            @endif

            <form class="form-ajax" action="{{ admin_url('theme/updatecache/'.$app_name) }}">
                @csrf
            <button type="submit" class="btn btn-sm btn-primary">
                更新模板缓存
            </button>
            </form>
        </div>
        
        @if(isset($themes))
        
        <div class="col-sm-12 suda_page_body">
            <div class="card card-theme" style="background: transparent;border: none;box-shadow: none;">
                    
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
                                    <i class="ion-compass text-primary"></i>正在使用
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6 ">
                            <div class="theme-support">
                                <p><strong>主题&nbsp;:&nbsp;&nbsp;</strong>{{ $theme_info['name'] }}</p>
                                <p><strong>描述&nbsp;:&nbsp;&nbsp;</strong>{{ $theme_info['description'] }}</p>
                                <p><strong>版本&nbsp;:&nbsp;&nbsp;</strong>{{ $theme_info['version'] }}</p>
                                <p><strong>作者&nbsp;:&nbsp;&nbsp;</strong><a target="_blank" href="{{ $theme_info['author_url'] }}"><i class="ion-person-outline"></i>&nbsp;{{ $theme_info['author'] }}</a></p>
                                <p><strong>支持&nbsp;:&nbsp;&nbsp;</strong><a target="_blank" href="{{ $theme_info['theme_url'] }}"><i class="ion-share-outline"></i>&nbsp;开发者网站</a></p>
                                <p><a href="{{ admin_url('widget/'.$app_name.'/'.$current_theme) }}" class="btn btn-light btn-sm">设置挂件</a></p>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
                
            </div>

            <div class="card card-theme">

                <div class="card-header">
                    模板列表
                </div>
                <div class="card-body">

                    @if(isset($themes) && !empty($themes))

                            @foreach($themes as $kk=>$theme)
                            
                                @if( $current_theme != $kk )

                                <form class="form-ajax" action="{{ admin_url('theme/settheme') }}">
                                    {{ csrf_field() }}
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
                                            <button type="submit" class="btn btn-light btn-sm">启用</button>
                                            <a href="{{ admin_url('widget/'.$app_name.'/'.$kk) }}" class="btn btn-light btn-sm">设置挂件</a>
                                            <a href="{{ url('/?theme_preview='.$kk) }}" target="_blank" class="btn btn-light btn-sm">预览</a>
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
        
        @endif

        
        
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