@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row mt-3 mb-3">
        
        <div class="col-sm-8">
            
                <div class="card">
                    {{-- <div class="card-header">统计</div> --}}
                    <div class="card-body">
                        <div style="w-100"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                            <canvas id="canvas" width="2114" height="650" class="chartjs-render-monitor" style="display: block; height: 528px; width: 1057px;"></canvas>
                        </div>
                       
                    </div>
                </div>
            
        </div>


        <div class="col-sm-4">
            
            <div class="card">
                {{-- <div class="card-header">
                    社交媒体
                </div> --}}
                <div class="card-body">
                    
                   <div class="card-title">2月增长</div>
                   <div class="row mb-3">
                    <div class="col-sm-9 align-middle">
                        <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
                       </div>
                   </div>
                   <div class="col-sm-3" style="line-height:0.5">微信</div>
                   </div>

                   <div class="row mb-3">
                    <div class="col-sm-9 align-middle">
                        <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100">55%</div>
                       </div>
                   </div>
                   <div class="col-sm-3" style="line-height:0.5">微博</div>
                   </div>

                   <div class="row mb-3">
                    <div class="col-sm-9 align-middle">
                        <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-dark progress-bar-striped progress-bar-animated" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">85%</div>
                       </div>
                   </div>
                   <div class="col-sm-3" style="line-height:0.5">抖音</div>
                   </div>

                   <div class="row mb-3">
                    <div class="col-sm-9 align-middle">
                        <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">65%</div>
                       </div>
                   </div>
                   <div class="col-sm-3" style="line-height:0.5">B站</div>
                   </div>



                   <div class="card-title">3月增长</div>
                   <div class="row mb-3">
                    <div class="col-sm-9 align-middle">
                        <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
                       </div>
                   </div>
                   <div class="col-sm-3" style="line-height:0.5">微信</div>
                   </div>

                   <div class="row mb-3">
                    <div class="col-sm-9 align-middle">
                        <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100">55%</div>
                       </div>
                   </div>
                   <div class="col-sm-3" style="line-height:0.5">微博</div>
                   </div>

                   <div class="row mb-3">
                    <div class="col-sm-9 align-middle">
                        <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-dark" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">85%</div>
                       </div>
                   </div>
                   <div class="col-sm-3" style="line-height:0.5">抖音</div>
                   </div>

                   <div class="row mb-3">
                    <div class="col-sm-9 align-middle">
                        <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">65%</div>
                       </div>
                   </div>
                   <div class="col-sm-3" style="line-height:0.5">B站</div>
                   </div>


                </div>
            </div>
        
        </div>



        
    </div>

    <div class="row suda-row">
        
        <div class="col-sm-8">
            <div class="card">
                
                <div class="card-body">
                    <div class="card-title">用户统计</div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light text-dark">
                                <th>姓名</th>
                                <th>日期</th>
                                <th>类型</th>
                                <th>状态</th>
                                <th>操作</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Jack Chueng</td>
                                    <td>2020-01-01</td>
                                    <td>
                                        <span class="badge rounded-pill bg-primary">高级用户</span>
                                    </td>
                                    <td>
                                        <font>开启</font>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1" checked>
                                        <label class="custom-control-label" for="customSwitch1"></label>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Emillie Bailey</td>
                                    <td>2020-01-02</td>
                                    <td>
                                        <span class="badge rounded-pill bg-warning">审核用户</span>
                                    </td>
                                    <td>
                                        <font class="text-danger">关闭</font>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1" checked>
                                        <label class="custom-control-label" for="customSwitch1"></label>
                                        </div>
                                    </td>
                                </tr>


                                <tr>
                                    <td>Evan Melia</td>
                                    <td>2020-01-11</td>
                                    <td>
                                        <span class="badge rounded-pill bg-danger">拒绝用户</span>
                                    </td>
                                    <td>
                                        <font class="text-danger">关闭</font>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                        <label class="custom-control-label" for="customSwitch1"></label>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Reon Bass</td>
                                    <td>2020-02-01</td>
                                    <td>
                                        <span class="badge rounded-pill bg-primary">高级用户</span>
                                    </td>
                                    <td>
                                        <font>开启</font>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1" checked>
                                        <label class="custom-control-label" for="customSwitch1"></label>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Sara Bareilles</td>
                                    <td>2020-02-01</td>
                                    <td>
                                        <span class="badge rounded-pill bg-primary">高级用户</span>
                                    </td>
                                    <td>
                                        <font>开启</font>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1" checked>
                                        <label class="custom-control-label" for="customSwitch1"></label>
                                        </div>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            
            <div class="row">
                <div class="col-sm-6">
                    <div class="card bg-primary text-white mb-3" style="background-image: linear-gradient(to right,#f383f5,#9178ff);">
                
                        <div class="card-body">
                            
                           <div class="card-title my-0 py-0">2月销售额</div>
                           
                            <div class="font-weight-bold text-white" style="font-size:2.2rem;">¥3,658</div>
                            <div class="font-weight-bold text-white text-right" style="position:absolute;right:0rem;bottom:0rem;font-size:2.5rem;opacity:0.4">食品</div>
        
                            {{-- <div class="help-block text-light">2020年1月</div> --}}
        
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card bg-primary text-white mb-3" style="background-image: linear-gradient(to right,#c3f668, #fda666);">
                
                        <div class="card-body">
                            
                           <div class="card-title my-0 py-0">3月销售额</div>
                           
                            <div class="font-weight-bold text-white" style="font-size:2.2rem;">¥19,365</div>
                            <div class="font-weight-bold text-white text-right" style="position:absolute;right:0rem;bottom:0rem;font-size:2.5rem;opacity:0.4">服饰</div>
        
                            {{-- <div class="help-block text-light">2020年1月</div> --}}
        
                        </div>
                    </div>
                </div>

            </div>
            

            

            <div class="card bg-primary text-white mb-3" style="background-image: linear-gradient(to right,#3e9cff, #2de9e2);">
                
                <div class="card-body">
                    
                   <div class="card-title my-0 py-0">6月销售额</div>
                   
                    <div class="font-weight-bold text-white" style="font-size:2.2rem;">¥2,068</div>
                    <div class="font-weight-bold text-white text-right" style="position:absolute;right:1rem;top:1rem;font-size:4rem;opacity:0.4">99%</div>

                    {{-- <div class="help-block text-light">2020年1月</div> --}}

                </div>
            </div>
        
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
$(document).ready(function(){
    
    window.chartColors = {
        red: 'rgb(255, 99, 132)',
        orange: 'rgb(255, 159, 64)',
        yellow: 'rgb(255, 205, 86)',
        green: 'rgb(75, 192, 192)',
        blue: 'rgb(54, 162, 235)',
        purple: 'rgb(153, 102, 255)',
        grey: 'rgb(201, 203, 207)'
    };

    (function(global) {
        var MONTHS = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        var COLORS = [
            '#4dc9f6',
            '#f67019',
            '#f53794',
            '#537bc4',
            '#acc236',
            '#166a8f',
            '#00a950',
            '#58595b',
            '#8549ba'
        ];

        var Samples = global.Samples || (global.Samples = {});
        var Color = global.Color;

        Samples.utils = {
            // Adapted from http://indiegamr.com/generate-repeatable-random-numbers-in-js/
            srand: function(seed) {
                this._seed = seed;
            },

            rand: function(min, max) {
                var seed = this._seed;
                min = min === undefined ? 0 : min;
                max = max === undefined ? 1 : max;
                this._seed = (seed * 9301 + 49297) % 233280;
                return min + (this._seed / 233280) * (max - min);
            },

            numbers: function(config) {
                var cfg = config || {};
                var min = cfg.min || 0;
                var max = cfg.max || 1;
                var from = cfg.from || [];
                var count = cfg.count || 8;
                var decimals = cfg.decimals || 8;
                var continuity = cfg.continuity || 1;
                var dfactor = Math.pow(10, decimals) || 0;
                var data = [];
                var i, value;

                for (i = 0; i < count; ++i) {
                    value = (from[i] || 0) + this.rand(min, max);
                    if (this.rand() <= continuity) {
                        data.push(Math.round(dfactor * value) / dfactor);
                    } else {
                        data.push(null);
                    }
                }

                return data;
            },

            labels: function(config) {
                var cfg = config || {};
                var min = cfg.min || 0;
                var max = cfg.max || 100;
                var count = cfg.count || 8;
                var step = (max - min) / count;
                var decimals = cfg.decimals || 8;
                var dfactor = Math.pow(10, decimals) || 0;
                var prefix = cfg.prefix || '';
                var values = [];
                var i;

                for (i = min; i < max; i += step) {
                    values.push(prefix + Math.round(dfactor * i) / dfactor);
                }

                return values;
            },

            months: function(config) {
                var cfg = config || {};
                var count = cfg.count || 12;
                var section = cfg.section;
                var values = [];
                var i, value;

                for (i = 0; i < count; ++i) {
                    value = MONTHS[Math.ceil(i) % 12];
                    values.push(value.substring(0, section));
                }

                return values;
            },

            color: function(index) {
                return COLORS[index % COLORS.length];
            },

            transparentize: function(color, opacity) {
                var alpha = opacity === undefined ? 0.5 : 1 - opacity;
                return Color(color).alpha(alpha).rgbString();
            }
        };

        // DEPRECATED
        window.randomScalingFactor = function() {
            return Math.round(Samples.utils.rand(-100, 100));
        };

        // INITIALIZATION

        Samples.utils.srand(Date.now());


    }(this));



    var config = {
        type: 'line',
        data: {
            labels: ['1月', '2月', '3月', '4月', '5月', '6月', '7月'],
            datasets: [{
                label: '社交数据',
                backgroundColor: window.chartColors.red,
                borderColor: window.chartColors.red,
                data: [10, 30, 39, 20, 25, 34, -10],
                fill: false,
            }, {
                label: '电商数据',
                fill: false,
                backgroundColor: window.chartColors.blue,
                borderColor: window.chartColors.blue,
                data: [18, 33, 22, 19, 11, 39, 30],
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: '年度曲线图'
            },
            scales: {
                yAxes: [{
                    gridLines: {
                        drawBorder: true,
                        // color: ['grey', 'grey', 'grey', 'grey', 'grey', 'grey', 'grey', 'grey']
                    },
                    ticks: {
                        min: 0,
                        max: 50,
                        stepSize: 10
                    }
                }]
            }
        }
    };

    var ctx = document.getElementById('canvas').getContext('2d');
    window.myLine = new Chart(ctx, config);

});


</script>
@endpush