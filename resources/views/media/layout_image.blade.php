<div class="modal fade modal-layout modal-image-upload" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-full" role="document">
    <div class="modal-content">
      <div class="modal-header pb-0">
        
        {{-- <h4 class="modal-title">
            媒体
        </h4> --}}
        <div class="modal-title-tabs">
            <ul class="nav nav-tabs border-bottom-0">
                <li class="nav-item" >
                    <a href="javascript:void(0);" class="nav-link bg-white active" affect-id="media-box">{{ __('suda_lang::press.menu_items.media') }}</a>
                </li>
                <li class="nav-item" >
                    <a href="javascript:void(0);" class="nav-link" affect-id="media-upload">{{ __('suda_lang::press.upload') }}</a>
                </li>
            </ul>
        </div>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body" id="media-box">
          <!-- ajax load content -->
          <!-- media list -->
      </div>
      
      <div class="modal-body" id="media-upload" style="display:none">
        
        <div class="suda-upload-modal" media_name="{{ $media_name }}" media_max="{{ $media_max }}" media_type="{{ $media_type }}" media_crop="{{ $media_crop }}">
            <div class="filelists">
        	<ol class="filelist complete">
        	</ol>
        	<ol class="filelist queue"></ol>
            </div>
            <span id="upload_label" style="display:none">
              <h4>{{ __('suda_lang::press.medias.upload_label') }}</h4><p>or</p><p><button class="btn btn-primary">{{ __('suda_lang::press.medias.upload_label_btn') }}</button></p>
            </span>
        </div>
        
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('suda_lang::press.btn.cancel') }}</button>
        <button type="button" class="btn btn-primary" id="btn-save">{{ __('suda_lang::press.btn.save') }}</button>
      </div>
      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
