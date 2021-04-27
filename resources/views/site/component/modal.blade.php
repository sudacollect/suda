<div class="modal fade modal-layout modal-box" tabindex="-1" role="dialog">
  <div class="modal-dialog @if(isset($modal_size)) modal-dialog-{{ $modal_size }} @endif" role="document">
    <div class="modal-content">
      
      @if(isset($modal_title))
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
            @if(isset($modal_icon_class))
            <i class="{{ $modal_icon_class }}"></i>
            @endif
            {{ $modal_title }}
        </h4>
      </div>
      @endif
      
      @yield('content')
      
      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->