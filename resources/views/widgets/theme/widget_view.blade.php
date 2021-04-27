@if(count((array)$widgets)>0)

{{-- 输出挂件的统一处理办法,不可更改 --}}

@foreach($widgets as $k=>$widget)

{!! (new $widget['widget_ctl'])->view(['content'=>$widget['contents'],'async'=>$async,'params'=>$params]) !!}

@endforeach

@endif