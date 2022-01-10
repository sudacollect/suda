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
                    <a href="javascript:void(0);" class="nav-link bg-white active" affect-id="media-box">媒体</a>
                </li>
                <li class="nav-item" >
                    <a href="javascript:void(0);" class="nav-link" affect-id="media-upload">上传</a>
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
        </div>
        
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary" id="btn-save">确定</button>
      </div>
      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
