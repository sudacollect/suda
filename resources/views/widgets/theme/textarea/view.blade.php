<div class="widget widget-textarea">

    @if(isset($content) && isset($content['textarea_title']))
    <h3>{{ $content['textarea_title'] }}</h3>
    @endif

    @if(isset($content) && isset($content['textarea']))
    <p>{{ $content['textarea'] }}</p>
    @endif

</div>