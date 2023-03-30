
<div id="new_image" style="height:120px;width:120px;position:relative;">
  <img id="preview" src="@if(isset($data)){{ suda_image($data->media,['size'=>'medium','url'=>true],false) }}@else{{ suda_asset('images/avatar.png') }}@endif" style="max-height:120px;max-width:100%;width:auto;border-radius:50%">
<div class="upload-crop-box">
<input type="file" id="upload">
<input type="hidden" id="uploadBase64" name="avatar">
<button class="upload-crop-camera">
  <span style="position:relative;">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3.2"></circle><path stroke="#fff" stroke-width="1.1" d="M9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
  </span>
</button>
</div>
</div>


@push('styles')
<link rel="stylesheet" href="{{ suda_asset('js/croppie/croppie.css') }}">
@endpush


@push('scripts')

<script src="{{ suda_asset('js/croppie/exif.min.js') }}"></script>
<script src="{{ suda_asset('js/croppie/croppie.js') }}"></script>

<script>
    
    $(document).ready(function(){
        
		var uploadCropEl = $('#upload-crop');

		if(uploadCropEl.length>0){

			var uploadCropOptions = {
                enableExif: true,
				viewport: {
					width: 200,
					height: 200,
					type: 'square'
				}
			};
            
            var uploadCrop = $('#upload-crop').croppie(uploadCropOptions);
            
			function readFile(input) {
	 			if (input.files && input.files[0]) {
		            var reader = new FileReader();

		            reader.onload = function (e) {
                        
		            	uploadCrop.croppie('bind',{
						    url: e.target.result,
						    orientation: 4
						}).then(function(){
							uploadCrop.croppie('setZoom',0);
						});
		            }

		            reader.readAsDataURL(input.files[0]);
		        }
		        else {
			        alert("你的浏览器版本过低,不支持FileReader");
			    }
			}
            
			$('#upload').on('change',function () {
                var upload = this;
                $('#modal-upload-avatar').modal('show');
                
                $('#modal-upload-avatar').on('shown.bs.modal', function(){ 
                    readFile(upload);
                });
            });
            
            
            $('#modal-upload-avatar').on('hidden.bs.modal', function (e) {
                $('#upload').value = '';
            });
            
			$('#modal-upload-avatar').on('click', '#apply-crop',function (e) {
                
                uploadCrop.croppie('result',{type:'base64',size:'original',format:'png',quality:1}).then(function(base64) {
                    
                    //console.log(window.URL.createObjectURL(new Blob([blob])));
                    
                    $('#modal-upload-avatar').modal('hide');
                    
					$('#preview').attr('src',base64);
					
                    $('#uploadBase64').val(base64);
				});
                
				// uploadCrop.result({type:'base64',size:'original',format:'png',quality:1}).then(function(base64) {
//
//                     //console.log(window.URL.createObjectURL(new Blob([blob])));
//
//                     $('#modal-upload-avatar').modal('hide');
//
//                     $('#preview').attr('src',base64);
//
//                     $('#uploadBase64').val(base64);
//                 });
			});
		}
        
    })
    
</script>

@endpush