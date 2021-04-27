@push('styles')
<link rel="stylesheet" href="{{ suda_asset('js/owlcarousel/assets/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ suda_asset('js/owlcarousel/assets/owl.theme.default.min.css') }}">
<link rel="stylesheet" href="{{ suda_asset('css/animate.css') }}">
@endpush


@push('scripts')
<script src="{{ suda_asset('js/owlcarousel/owl.carousel.min.js') }}"></script>

<script type="text/javascript">
    (function($){
        $(document).ready(function(){
            
            $('.widget-gallery .owl-carousel').owlCarousel({
                animateOut: 'zoomOut',
                animateIn: 'flipInX',
                margin:6,
                loop:true,
                items:1,
            })

        })
    })(jQuery)
</script>

@endpush