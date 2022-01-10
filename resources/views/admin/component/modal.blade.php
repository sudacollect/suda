<div class="modal fade modal-layout modal-box" tabindex="-1" role="dialog">
  <div class="modal-dialog @if(isset($modal_size)) modal-{{ $modal_size }} @endif" role="document">
    <div class="modal-content">
      
      @if(isset($modal_title))
      <div class="modal-header">
        <h4 class="modal-title">
            @if(isset($modal_icon_class))
            <i class="{{ $modal_icon_class }}"></i>
            @endif
            {{ $modal_title }}
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      @endif
      
      @yield('content')
      
      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->