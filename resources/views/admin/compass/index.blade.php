@extends('view_path::layouts.default')



@section('content')
<div class="container-fluid">
    <div class="row suda-row">
        
        <h1 class="display-3 page-title page-tabs-title">{{ __('suda_lang::press.menu_items.tool_compass') }}</h1>
        
        
        @include('view_suda::admin.compass.tabs',['active'=>'index'])
        

        
        <div class="col-sm-12 mb-3 suda_page_body press-compass-addition">
            
                <div class="card">
                    
                    <div class="card-body">
                       <div class="table-responsive">
                           <table class="table">
                               
                               <thead class="bg-light">
                                   <tr>
                                       <th>组件名称</th>
                                       
                                       <th>说明</th>
                                   </tr>
                               </thead>
                               
                               <tbody style="font-weight:bold">
                                   <tr>
                                       <td><img src="{{ suda_asset('images/addition/icon_default.png') }}" class="icon">Build with</td>
                                       
                                       <td>
                                           <a href="{{ url('http://jquery.com/') }}" target="_blank" class="btn btn-light btn-sm">jQuery</a>
                                           <a href="{{ url('http://getbootstrap.com/') }}" target="_blank" class="btn btn-light btn-sm">Bootstrap</a>
                                           <a href="{{ url('http://formstone.it/') }}" target="_blank" class="btn btn-light btn-sm">Formstone</a>
                                           <a href="{{ url('https://blueimp.github.io/jQuery-File-Upload/') }}" target="_blank" class="btn btn-light btn-sm">jQueryFileUpload</a>
                                           <a href="{{ url('https://select2.org') }}" target="_blank" class="btn btn-light btn-sm">Select2</a>

                                           <a href="{{ url('https://github.com/nefe/number-precision') }}" target="_blank" class="btn btn-light btn-sm">number-precision</a>
                                           <a href="{{ url('https://momentjs.com') }}" target="_blank" class="btn btn-light btn-sm">Moment.js</a>
                                           <a href="{{ url('https://fengyuanchen.github.io/distpicker/') }}" target="_blank" class="btn btn-light btn-sm">Distpicker</a>
                                           <a href="{{ url('https://www.npmjs.com/package/pc-bootstrap4-datetimepicker') }}" target="_blank" class="btn btn-light btn-sm">Datetimepicker</a>
                                           <a href="{{ url('https://simonwep.github.io/pickr/') }}" target="_blank" class="btn btn-light btn-sm">Color Pickr</a>
                                           
                                       </td>
                                   </tr>
                                  
                                  


                                  <tr>
                                    <td><img src="{{ suda_asset('images/addition/icon_default.png') }}" class="icon">Moment</td>
                                    
                                    <td>
                                        {{-- <a href="{{ url('https://momentjs.com') }}" target="_blank" class="btn btn-light btn-sm">Moment.js</a> --}}
                                        <code>{{ "moment().format('MMMM Do YYYY, h:mm:ss a');" }}</code>
                                        <code>// December 2nd 2019, 10:55:44 pm</code>
                                    </td>
                                  </tr>

                                  <tr>
                                    <td><img src="{{ suda_asset('images/addition/icon_default.png') }}" class="icon">Distpicker</td>
                                    
                                    <td>
                                        
                                        <div class="input-group" data-toggle="distpickerrr">
                                            <select name="province" data-province="选择省" class="form-control form-control-sm me-2" ></select>
                                            <select name="city" data-city="选择市" class="form-control form-control-sm me-2" ></select>
                                            <select name="district" data-district="选择区" class="form-control form-control-sm" ></select>
                                        </div>
                                        <span class="help-block">
                                            config/suda_districts.php
                                        </span>
                                        
                                    </td>
                                  </tr>

                                  <tr>
                                    <td><img src="{{ suda_asset('images/addition/icon_default.png') }}" class="icon">Datetimepicker</td>
                                    
                                    <td style="position:relative;">
                                        <div class="">
                                            <input type="text" class="form-control form-control-sm" data-toggle="datetimepicker" placeholder="选择时间">
                                        </div>
                                        
                                    </td>
                                    </tr>

                                  <tr>
                                    <td><img src="{{ suda_asset('images/addition/icon_pickr.png') }}" class="icon">Color Pickr</td>
                                    
                                    <td>
                                        <div class="color-picker"></div>
                                    </td>
                                  </tr>

                                  <tr>
                                    <td><img src="{{ suda_asset('images/addition/icon_default.png') }}" class="icon">iconfont</td>
                                    
                                    <td>
                                        <a href="{{ url('https://www.fontawesome.com') }}" target="_blank" class="btn btn-light btn-sm">FontAwesome</a>

                                        <a href="{{ url('https://ionicons.com') }}" target="_blank" class="btn btn-light btn-sm">Ionicons</a>

                                    </td>
                                </tr>

                               </tbody>
                               
                           </table>
                        </div>
                        
                    </div>
                </div>
            
        </div>
        
        <div class="col-sm-12 suda_page_body press-compass-icons">
            
                <div class="card">
                    <h5 class="card-header">
                        ICON
                    </h5>
                    <div class="card-body">
                        <p>1. For example <code>ion-person</code> <i class="ion-person"></i></p>
                        <p>
                            2. Blade icons <a href="https://driesvints.com/blog/blade-heroicons/" style="text-decoration:underline">Blade Heroicons</a>
                        </p>
                    </div>
                    </div>
                </div>
            
        </div>
        

        
        
    </div>
</div>
@endsection


@push('scripts')

<script>
    
    $(document).ready(function(){

        $('[data-toggle="datetimepicker"]').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            showClear:true,
            sideBySide:true,
            useCurrent:'day',
            locale:'zh-CN',
            debug:false,
        });
        
        
        
        $("[data-toggle=\"distpickerrr\"]").suda_distpicker();

    });

    

const pickr = Pickr.create({
    el: '.color-picker',
    theme: 'nano', // or 'monolith', or 'nano'

    swatches: [
        'rgba(244, 67, 54, 1)',
        'rgba(233, 30, 99, 0.95)',
        'rgba(156, 39, 176, 0.9)',
        'rgba(103, 58, 183, 0.85)',
        'rgba(63, 81, 181, 0.8)',
        'rgba(33, 150, 243, 0.75)',
        'rgba(3, 169, 244, 0.7)',
        'rgba(0, 188, 212, 0.7)',
        'rgba(0, 150, 136, 0.75)',
        'rgba(76, 175, 80, 0.8)',
        'rgba(139, 195, 74, 0.85)',
        'rgba(205, 220, 57, 0.9)',
        'rgba(255, 235, 59, 0.95)',
        'rgba(255, 193, 7, 1)'
    ],

    components: {

        // Main components
        preview: true,
        opacity: true,
        hue: true,

        // Input / output Options
        interaction: {
            hex: true,
            rgba: true,
            hsla: false,
            hsva: false,
            cmyk: false,
            input: true,
            clear: false,
            save: true
        }
    }
});

</script>

@endpush