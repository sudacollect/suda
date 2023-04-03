@extends('view_path::layouts.default')

@section('content')


<div class="col-lg-8 mx-auto pb-md-5 px-3 article_page">

    <div class="container navbar-expand-sm category-nav mb-3">

        @if(isset($categories) && $categories->count()>0)

        <button type="button" class="navbar-toggler collapsed" data-toggle="collapse" data-target="#app-navbar-collapse-category">
            分类 <i class="far fa-caret-square-down"></i>
        </button>

        <div class="collapse navbar-collapse" id="app-navbar-collapse-category">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav nav-pills">
                
                @foreach($categories as $cate)

                @if($cate->children && $cate->children->count()>0)
                <li class="nav-item dropdown">
                    <a href="{{ url('category/'.$cate->term->slug) }}" class="nav-link dropdown-toggle @if(isset($category) && $category->id==$cate->id) active @endif" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                        {{ $cate->term->name }}
                    </a>

                    <div class="dropdown-menu cate-menu" role="menu">
                        <a class="dropdown-item @if(isset($category) && $category->id==$cate->id) active @endif" href="{{ url('category/'.$cate->term->slug) }}" >
                            {{ $cate->term->name }}
                        </a>
                        @foreach($cate->children as $child_cate)
                        <a class="dropdown-item @if(isset($category) && $category->id==$child_cate->id) active @endif" href="{{ url('category/'.$child_cate->term->slug) }}" >
                            {{ $child_cate->term->name }}
                        </a>
                        @endforeach
                    </div>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link @if(isset($category) && $category->id==$cate->id) active @endif" id="menu-cate-{{ $cate->id }}"  href="{{ url('category/'.$cate->term->slug) }}">{{ $cate->term->name }}</a>
                </li>
                @endif
                
                @endforeach
                

            </ul>
        </div>
        @endif

        @if(isset($tag))

        <span style="color:#999;">标签:</span> {{ $tag->term->name }}

        @endif

    </div>

    <div class="container main_box">
        @if(isset($sticks) && $sticks->count()>0)
        <div class="swiper_box">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    
                    @foreach($sticks as $k=>$stick)
                    <div class="swiper-slide" data-href="{{ $stick->real_url }}">

                            <div class="img_box" style="background:url('{{ suda_image(isset($stick->heroimage)?$stick->heroimage->media:'',['url'=>true,'size'=>'large']) }}') no-repeat center center;background-size:cover;">
                                
                            </div>
                            <div class="text_box">
                                <div class="text_main">
                                    <div class="label_box @if($k==0) color_4F69F8 @elseif($k==1) color_DA0F47 @else color_688000 @endif font_Text">
                                        @if($stick->categories)

                                        @foreach($stick->categories as $cate)

                                        @if(isset($cate->taxonomy->term))
                                        {{ $cate->taxonomy->term->name }}
                                        @endif
                                        @endforeach

                                        @endif
                                    </div>
                                    <div class="title_box font_Display">
                                        {{ $stick->title }}
                                    </div>
                                    <div class="message_box font_Text">
                                        {{  Illuminate\Support\Str::limit(clean($stick->content,['HTML.Allowed'=>'']), $limit = 120, $end = '...') }}
                                    </div>
                                </div>
                                <div class="time_box font_Text">{{ $stick->updated_at->format('Y-m-d') }}</div>
                            </div>
                        
                    </div>
                    @endforeach
                    
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        @endif

        <div class="row list_box">
            
            @if($data_list->count()>0)
                @foreach($data_list as $item)
                <div class="col-sm-6">
                <a class="item_box" href="{{ $item->real_url }}" title="{{ $item->title }}">
                    <div class="img_box" style="background:url('{{ suda_image(isset($item->heroimage)?$item->heroimage->media:'',['url'=>true,'size'=>'large']) }}') no-repeat center center;background-size:cover;">

                    </div>
                    <div class="text_box">
                        <div class="text_main">
                            <div class="label_box color_4F69F8 font_Text">
                                    @php
                                        $comma = '';
                                    @endphp

                                    @if($item->categories)

                                    @foreach($item->categories as $cate)

                                    @if(isset($cate->taxonomy->term))
                                    {{ $comma.$cate->taxonomy->term->name }}
                                    @php
                                        $comma = '/ ';
                                    @endphp
                                    @endif
                                    @endforeach

                                    @endif
                            </div>
                            <div class="title_box font_Display">{{ $item->title }}</div>
                            <div class="time_box font_Text">{{ $item->updated_at->format('Y-m-d') }}</div>
                        </div>
                        <div class="message_box font_Text">
                            {{ Illuminate\Support\Str::limit(clean($item->content,['HTML.Allowed'=>'']), $limit = 100, $end = '...') }}
                        </div>
                    </div>
                </a>
                </div>
                @endforeach

            @else

            <p>暂无文章</p>

            @endif

        </div>

        <div class="col-sm-12">
            @if($data_list->count()>0)
            {{ $data_list->appends($filter_arr)->links() }}
            @endif

            @if(isset($filter_str))
                <input type="hidden" id="filter_str" value="{{ $filter_str }}">
            @endif
        </div>
        
    </div>
</div>



@endsection


@push('styles')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
@endpush

@push('styles')
<style>
.is-boxed{
    background: #f2f2f2;
}
.body-wrap{
    background:#f2f2f2;
}
.navbar{
    background:#fff;
}

</style>
<link rel="stylesheet" type="text/css" href="{{ suda_asset('site/article.css') }}">
@endpush

@push('scripts')




<script type="text/javascript">
    $(function(){
        // 浏览器
        // if (/Android|webOS|iPhone|iPad|BlackBerry/i.test(navigator.userAgent)) {
        //     $('html').addClass('wap');
        // } else {
        //     $('html').addClass('pc');
        // }
        var mySwiper = new Swiper ('.main_box .swiper_box .swiper-container', {
            loop: true,
            effect : 'fade',
            autoplay:true,
            pagination: {
                el: '.swiper-pagination',
                clickable:true,
            },
        });
        mySwiper.on('tap', function () {
            var slide = mySwiper.slides[mySwiper.activeIndex];
            if($(slide).length>0){
                var data_href = $(slide).attr('data-href');
                window.location.href=data_href;
            }
        });
    })
</script>
@endpush