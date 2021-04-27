<div class="modal fade modal-layout modal-image-upload" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-full" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-tabs">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <!-- <h4 class="modal-title">
            {{ trans('suda_lang::press.image_set') }}
        </h4> -->
        <div class="modal-title-tabs">
            <ul class="nav nav-tabs">
                <li role="presentation" class="active">
                    <a href="javascript:void(0);" affect-id="media-box">媒体</a>
                </li>
                {{-- <li role="presentation">
                    <a href="javascript:void(0);" affect-id="media-upload">上传</a>
                </li> --}}
            </ul>
        </div>
      </div>
      
      <div class="modal-body" id="media-box">
          <!-- ajax load content -->
          <!-- media list -->
      </div>
      
      <div class="modal-body" id="media-upload" style="display:none">
        
        <div class="suda-upload-modal" media_name="{{ $media_name }}" media_max="{{ $media_max }}" media_type="{{ $media_type }}">
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
