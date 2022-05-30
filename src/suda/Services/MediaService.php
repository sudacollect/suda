<?php
// MediaService
namespace Gtd\Suda\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use Response;
use Storage;
use Illuminate\Filesystem\Filesystem;

use Gtd\Suda\Models\Media;
use Gtd\Suda\Models\Mediatable;
use Gtd\Suda\Models\Setting;
use Illuminate\Support\Str;

class MediaService
{
    
    protected $options;
    protected $user;
    protected $_file;
    
    public $save_path;
    public $save_disk;

    private $type_data = [];
    
    
    protected $_unique_types = ['user','operate'];
    
    protected $error_messages = array(
        1 => '[1]超过最大允许上传大小',//upload_max_filesize
        2 => '[2]超过最大允许上传大小',//MAX_FILE_SIZE
        3 => '[3]上传失败，请重试',//partially
        4 => '[4]上传失败，请重试',
        6 => '[6]上传失败，请重试',//tmp folder
        7 => '[7]上传写入失败，请重试',
        8 => '[8]上传失败，请重试',
        9 => '[9]用户信息出错，请重新登录重试',
        'post_max_size' => '超过最大允许上传大小',
        'max_file_size' => '超过最大允许上传大小',
        'min_file_size' => '文件过小',
        'accept_file_types' => '文件类型不被允许',
        'max_number_of_files' => '超过最大上传数量',
        'max_width' => '图片超过最大宽度',
        'min_width' => '图片宽度过小',
        'max_height' => '图片超过最大高度',
        'min_height' => '图片高度过小',
        'abort' => '文件上传被拒绝',
        'image_resize' => '图片设置失败'
    );

    protected $image_objects = array();
    
    
    public $image_types = [
        0=>'UNKNOWN',
        1=>'GIF',
        2=>'JPEG',
        3=>'PNG',
        4=>'SWF',
        5=>'PSD',
        6=>'BMP',
        7=>'TIFF_II',
        8=>'TIFF_MM',
        9=>'JPC',
        10=>'JP2',
        11=>'JPX',
        12=>'JB2',
        13=>'SWC',
        14=>'IFF',
        15=>'WBMP',
        16=>'XBM',
        17=>'ICO',
        18=>'COUNT'  
    ];
    
    public function doUpload(request $request,$user,$options = null, $initialize = true, $error_messages = null){
        $this->response_content = [];
        $this->options = [
            'script_url' => $this->get_full_url().'/'.$this->basename($this->get_server_var('SCRIPT_NAME')),
            'upload_dir' => storage_path(),
            'upload_url' => false,
            'input_stream' => 'php://input',
            'user_dirs' => false,
            'mkdir_mode' => 0755,
            'param_name' => 'files',
            // Set the following option to 'POST', if your server does not support
            // DELETE requests. This is a parameter sent to the client:
            'delete_type' => 'DELETE',
            'access_control_allow_origin' => '*',
            'access_control_allow_credentials' => false,
            'access_control_allow_methods' => array(
                'OPTIONS',
                'HEAD',
                'GET',
                'POST',
                'PUT',
                'PATCH',
                'DELETE'
            ),
            'access_control_allow_headers' => array(
                'Content-Type',
                'Content-Range',
                'Content-Disposition'
            ),
            // By default, allow redirects to the referer protocol+host:
            'redirect_allow_target' => '/^'.preg_quote(
              parse_url($this->get_server_var('HTTP_REFERER'), PHP_URL_SCHEME)
                .'://'
                .parse_url($this->get_server_var('HTTP_REFERER'), PHP_URL_HOST)
                .'/', // Trailing slash to not match subdomains by mistake
              '/' // preg_quote delimiter param
            ).'/',
            // Enable to provide file downloads via GET requests to the PHP script:
            //     1. Set to 1 to download files via readfile method through PHP
            //     2. Set to 2 to send a X-Sendfile header for lighttpd/Apache
            //     3. Set to 3 to send a X-Accel-Redirect header for nginx
            // If set to 2 or 3, adjust the upload_url option to the base path of
            // the redirect parameter, e.g. '/files/'.
            'download_via_php' => false,
            // Read files in chunks to avoid memory limits when download_via_php
            // is enabled, set to 0 to disable chunked reading of files:
            'readfile_chunk_size' => 20 * 1024 * 1024, // 10 MiB
            // Defines which files can be displayed inline when downloaded:
            'inline_file_types' => '/\.(gif|jpe?g|png)$/i',
            // Defines which files (based on their names) are accepted for upload:
            'accept_file_types' => '/.+$/i',
            // The php.ini settings upload_max_filesize and post_max_size
            // take precedence over the following max_file_size setting:
            'max_file_size' => null,
            'min_file_size' => 1,
            // The maximum number of files for the upload directory:
            'max_number_of_files' => null,
            // Defines which files are handled as image files:
            'image_file_types' => '/\.(gif|jpe?g|png)$/i',
            // Use exif_imagetype on all files to correct file extensions:
            'correct_image_extensions' => false,
            // Image resolution restrictions:
            'max_width' => null,
            'max_height' => null,
            'min_width' => 10,
            'min_height' => 10,
            // Set the following option to false to enable resumable uploads:
            'discard_aborted_uploads' => true,
            // Set to 0 to use the GD library to scale and orient images,
            // set to 1 to use imagick (if installed, falls back to GD),
            // set to 2 to use the ImageMagick convert binary directly:
            'image_library' => 1,
            // Uncomment the following to define an array of resource limits
            // for imagick:
            /*
            'imagick_resource_limits' => array(
                imagick::RESOURCETYPE_MAP => 32,
                imagick::RESOURCETYPE_MEMORY => 32
            ),
            */
            // Command or path for to the ImageMagick convert binary:
            'convert_bin' => 'convert',
            // Uncomment the following to add parameters in front of each
            // ImageMagick convert call (the limit constraints seem only
            // to have an effect if put in front):
            /*
            'convert_params' => '-limit memory 32MiB -limit map 32MiB',
            */
            // Command or path for to the ImageMagick identify binary:
            'identify_bin' => 'identify',
            'image_versions' => array(
                // The empty image version key defines options for the original image.
                // Keep in mind: these image manipulations are inherited by all other image versions from this point onwards. 
                // Also note that the property 'no_cache' is not inherited, since it's not a manipulation.
                '' => array(
                    // Automatically rotate images based on EXIF meta data:
                    'auto_orient' => true
                ),
                // You can add arrays to generate different versions.
                // The name of the key is the name of the version (example: 'medium'). 
                // the array contains the options to apply.
                'medium' => array(
                    'max_width' => 640,
                    'max_height' => 640
                ),

                'thumbnail' => array(
                    // Uncomment the following to use a defined directory for the thumbnails
                    // instead of a subdirectory based on the version identifier.
                    // Make sure that this directory doesn't allow execution of files if you
                    // don't pose any restrictions on the type of uploaded files, e.g. by
                    // copying the .htaccess file from the files directory for Apache:
                    //'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')).'/thumb/',
                    //'upload_url' => $this->get_full_url().'/thumb/',
                    // Uncomment the following to force the max
                    // dimensions and e.g. create square thumbnails:
                    // 'auto_orient' => true,
                    // 'crop' => true,
                    // 'jpeg_quality' => 70,
                    // 'no_cache' => true, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                    // 'strip' => true, (this strips EXIF tags, such as geolocation)
                    'max_width' => 200, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                    'max_height' => 200 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
                )
            ),
            'json_response' => true,
            'user_type'     => 'user',
            'is_crop'       => false,
        ];
        
        if($user){
            $this->user = $user;
        }else{
            return $this->response('fail',$this->get_error_message(9));
        }
        
        if ($options) {
            $this->options = $options + $this->options;
        }
        if ($error_messages) {
            $this->error_messages = $error_messages + $this->error_messages;
        }
        if ($initialize) {
            return $this->initialize($request);
        }
    }
    
    protected function initialize($request=false) {
        switch ($this->get_server_var('REQUEST_METHOD')) {
            case 'OPTIONS':
            case 'HEAD':
                $this->head();
                break;
            case 'GET':
                $this->get($this->options['json_response']);
                break;
            case 'PATCH':
            case 'PUT':
            case 'POST':
                return $this->post($request);
                break;
            case 'DELETE':
                $this->delete($this->options['json_response']);
                break;
            default:
                $this->header('HTTP/1.1 405 Method Not Allowed');
        }
    }

    protected function get_full_url() {
        $https = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0 ||
            !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
                strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0;
        return
            ($https ? 'https://' : 'http://').
            (!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
            (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
            ($https && $_SERVER['SERVER_PORT'] === 443 ||
            $_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
            substr($_SERVER['SCRIPT_NAME'],0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
    }

    protected function get_query_separator($url) {
        return strpos($url, '?') === false ? '?' : '&';
    }

    protected function set_additional_file_properties($file) {
        $file->deleteUrl = $this->options['script_url']
            .$this->get_query_separator($this->options['script_url'])
            .$this->get_singular_param_name()
            .'='.rawurlencode($file->name);
        $file->deleteType = $this->options['delete_type'];
        if ($file->deleteType !== 'DELETE') {
            $file->deleteUrl .= '&_method=DELETE';
        }
        if ($this->options['access_control_allow_credentials']) {
            $file->deleteWithCredentials = true;
        }
    }

    // Fix for overflowing signed 32 bit integers,
    // works for sizes up to 2^32-1 bytes (4 GiB - 1):
    protected function fix_integer_overflow($size) {
        if ($size < 0) {
            $size += 2.0 * (PHP_INT_MAX + 1);
        }
        return $size;
    }

    protected function get_file_size($file_path, $clear_stat_cache = false) {
        if ($clear_stat_cache) {
            if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
                clearstatcache(true, $file_path);
            } else {
                clearstatcache();
            }
        }
        return $this->fix_integer_overflow(filesize($file_path));
    }



    protected function get_error_message($error) {
        return isset($this->error_messages[$error]) ?
            $this->error_messages[$error] : $error;
    }

    public function get_config_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int)$val;
        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $this->fix_integer_overflow($val);
    }
    
    //校验上传的图片信息
    protected function validateFile(array $data){
        
        $this->type_data = Media::getTypes();
        
        $typelist = implode(',',$this->type_data['types']);
        $roles = [
            'files' => [
                'bail',
                'required',
                'mimes:jpeg,bmp,png,gif',
                'image',
                'dimensions:min_width=10,min_height=10',
            ],
            'media_type' => [
                'bail',
                'required',
                'in:'.$typelist
            ],
        ];
        
        $messages = [
            'required' => '请正确选择上传图片',
            'image' => '请选择图片文件',
            'mimes' => '请选择图片类型文件',
            'dimensions' => '图片尺寸不能小于10像素',
            'in'=>'上传类型错误',
            'media_type.required'=>'需指定图片类型',
        ];
        return Validator::make($data, $roles,$messages);
    }
    
    //验证上传
    public function validateUpload($request,&$msg=''){
        $validator = $this->validateFile(['media_type'=>$request->media_type,'files'=>$request->file('files')[0]]);
        if($validator->fails()){
            $msg = $validator->errors();
            
            $msgtxt = '异常上传出错，请稍后重试';
            if(is_string($msg)){
                $msgtxt = $msg;
            }else{
                if($msg->hasAny('media_type')){
                    $msgtxt = $msg->first('media_type');
                }
                if($msg->hasAny('files')){
                    $msgtxt = $msg->first('files');
                }
            }
            $msg = $msgtxt;
            return false;
        }
        //获取资源
        $files = $request->file('files');
        if(is_array($files) && $files){
            $this->_file = $files[0];
            return $this->_file;
        }
        
        $msg = '上传失败';
        return false;
    }


    protected function handle_file_upload($request, $size = null,$content_range = null,&$files=[],$is_crop=false,&$msg='')
    {
        
        $files = $request->file('files');
        $upload = $files[0];
        
        $uploaded_file = isset($upload->fileName) ? $upload->fileName : null;
        $name = isset($upload->originalName) ? $upload->originalName : null;
        $size = $size ? $size : (isset($upload->size) ? $upload->size : $this->get_server_var('CONTENT_LENGTH'));
        $file_type = isset($upload->mimeType) ? $upload->mimeType : $this->get_server_var('CONTENT_TYPE');
        $error = isset($upload->error) ? $upload->error : null;
        $index = null;
        
        $msg = '';
        $validate = $this->validateUpload($request,$msg);
        
        if(!$validate){
            return $this->response('fail',$msg);
        }
        
        //文件类型
        $type = 'media';
        if($request->media_type){
            $type = $request->media_type;
        }
        
        //位置标记符
        $position = 'default';
        if($request->media_position){
            $position = $request->media_position;
        }
        
        $type_id = 0;
        if(in_array($type,$this->_unique_types)){
            $type_id = $this->user->id;
        }
        $medias = $this->saveImage($type,['resize'=>true,'crop'=>$is_crop,'file_type'=>$file_type,'ratio'=>false,'type_id'=>$type_id,'position'=>$position]);
        
        if($medias) {
            
            list($media_id,$media_path) = $medias;
            $media = Media::where('id',$media_id)->first();
            
            if($media){
                return Response::json([
                    'image' => suda_image($media,['size'=>'medium','imageClass'=>'image_show']),
                    'url' => suda_image($media,['size'=>'medium','imageClass'=>'image_show','url'=>true]),
                    'media_id' => $media_id,
                    'name'=> $media->name,
                    'media_path' => $media_path,
                    'media_position'   => $position
                ], 200);
            }
            
        } else {
            
            $msgtxt = '图片上传失败，请稍后重试';
            return Response::json([
                'error' => $msgtxt
            ], 422);
            
        }
        
    }
    
    public function saveImage($saveType='upload',$options=[])
    {
        //验证规则
        //$options = ['resize'=>true, 'crop'=>true,'ratio'=>1, 'quality'=>100, 'disk'=>'public']
        //生成对应目录文件名规则
        $user_type = $this->options['user_type'];
        $type = $saveType;
        $targetImage = $this->generateTargetName(storage_path('app/public/images'), $this->_file->getClientOriginalExtension(), TRUE);
        $basename = pathinfo($targetImage, PATHINFO_BASENAME);
        $file_extension = pathinfo($targetImage, PATHINFO_EXTENSION);
        $subdir = stringBeginsWith(dirname($targetImage), storage_path('app/public/images'), FALSE, TRUE);
        
        $saveDirPath = 'images/'.$type.$subdir;
        
        $savePath           = $saveDirPath.'/p'.$basename;
        
        $saveImage          = $saveDirPath.'/p'.$basename;
        $saveImageMedium    = $saveDirPath.'/m'.$basename;
        $saveImageSmall     = $saveDirPath.'/s'.$basename;
        
        $size = @getimagesize($this->_file);
        list($sourceWidth, $sourceHeight, $sourceType) = $size;
        
        
        //=============== start 优先级别最高的特殊设置 ================
        //ratio 按照比例存储，优先级最高，可放大可缩小
        $ratio = array_get($options,'ratio',false);
        $ratio = abs($ratio);
        
        //=============== end 优先级别最高的特殊设置 ================
        
        $is_crop = array_get($options,'crop',false); //默认不剪切
        $isResize = array_get($options,'resize',false); //默认不缩放
        $quality = array_get($options,'quality',100); //默认质量100
        
        $disk = array_get($options,'disk',''); //默认存储本地
        
        //只有本地存储时，检测是否有预先设置的存储方式
        if(!$disk && config('sudaconf.image.disk')){
            $disk = config('sudaconf.image.disk');
        }

        $this->save_disk = $disk;
        
        try{
            
            $save_model = "App\\Models\\".ucfirst($saveType);
            $this->type_data = Media::getTypes();
            
            if(array_key_exists(strtolower($saveType),$this->type_data['models']['default'])){
                $save_model = $this->type_data['models']['default'][strtolower($saveType)];
            }elseif(array_key_exists(strtolower($saveType),$this->type_data['models']['custom'])){
               $save_model = $this->type_data['models']['custom'][strtolower($saveType)];
            }
            
            //先存储，再进行后续动作
            //文件存储
            $imagefile = Image::make($this->_file)->stream();
            Storage::disk($this->save_disk)->put($saveImage, $imagefile);
            
            $this->resizeImage($saveDirPath,$basename,$this->save_disk,$sourceWidth,$sourceHeight,$ratio,$is_crop,$quality);
            
            $type_id = array_get($options,'type_id',0);
            
            
            $mediaModel = new Media;
            $mediaModel->name = $basename;
            $mediaModel->user_type = $user_type;
            $mediaModel->user_id = $this->user->id;
            $mediaModel->size = $this->_file->getSize();
            $mediaModel->dist = $this->save_disk;
            $mediaModel->path = $savePath;
            $mediaModel->crop = $is_crop;
            $mediaModel->type = $this->image_types[$sourceType];
            
            $mediaModel->save();
            
            return [$mediaModel->id,$savePath];//返回media_id 对应 相对存储地址

        } catch (Exception $Ex) {
           $Error = $Ex;
           return false;
       }
    }
    
    public function resizeImage($saveDirPath,$basename,$disk,$sourceWidth,$sourceHeight,$ratio,$is_crop=false,$quality){
        
        //宽高都有值，按照实际存储(resize可能会造成图片变形)
        // $medium_config = $this->options['image_versions']['medium'];
        // $thumbnail_config = $this->options['image_versions']['thumbnail'];
        
        $saveImageMedium    = $saveDirPath.'/m'.$basename;
        $saveImageThumbmail     = $saveDirPath.'/s'.$basename;

        $media_setting = Setting::where(['key'=>'media_setting','group'=>'media'])->first();
        
        if($media_setting){
            $setting = $media_setting->value_array;
        }else{
            $setting = [
                'size'=>[
                    'medium'=>[
                        'width'=>config('sudaconf.image.size.medium',400),
                        'height'=>config('sudaconf.image.size.medium',400),
                    ],
                    'small'=>[
                        'width'=>config('sudaconf.image.size.small',200),
                        'height'=>config('sudaconf.image.size.small',200),
                    ],
                ],
            ];
        }

        if($is_crop || (isset($setting['crop']) && $setting['crop']==1)){
            $is_crop = true;
        }

        //medium缩略图
        if($sourceWidth>=$sourceHeight){
            $medium_width = $setting['size']['medium']['width'];
            $medium_height = ceil(($medium_width/$sourceWidth)*$sourceHeight);

            if($is_crop){
                $medium_height = $setting['size']['medium']['height'];
            }
        }else{
            $medium_height = $setting['size']['medium']['height'];
            $medium_width = ceil(($medium_height/$sourceHeight)*$sourceWidth);

            if($is_crop){
                $medium_width = $setting['size']['medium']['width'];
            }
        }

        if($ratio && $ratio > 0){
            //按照比例进行缩放
            $medium_width = $ratio*$sourceWidth;
            $medium_height = $ratio*$sourceHeight;
        }
        
        
        //small缩略图
        if($sourceWidth>=$sourceHeight){
            $small_width = $setting['size']['small']['width'];
            $small_height = ceil(($small_width/$sourceWidth)*$sourceHeight);
            if($is_crop){
                $small_height = $setting['size']['small']['height'];
            }
        }else{
            $small_height = $setting['size']['small']['height'];
            $small_width = ceil(($small_height/$sourceHeight)*$sourceWidth);
            if($is_crop){
                $small_width = $setting['size']['small']['width'];
            }
        }
        
        if($is_crop){

            $x = $y = 0;
            if($sourceWidth >= $medium_width){
                $x = ceil(($sourceWidth-$medium_width)/2);
            }
            if($sourceHeight >= $medium_height){
                $y = ceil(($sourceHeight-$medium_height)/2);
            }

            $this->cropImage($saveImageMedium,$disk,$medium_width,$medium_height,$x,$y);

            $x = $y = 0;
            if($sourceWidth >= $small_width){
                $x = ceil(($sourceWidth-$small_width)/2);
            }
            if($sourceHeight >= $small_height){
                $y = ceil(($sourceHeight-$small_height)/2);
            }

            $this->cropImage($saveImageThumbmail,$disk,$small_width,$small_height,$x,$y);

        }else{
            $resizeMediumImage = Image::make($this->_file)->resize($medium_width, $medium_height)->stream();
            Storage::disk($diisk)->put($saveImageMedium, $resizeMediumImage);
            
            $resizeThumbnailImage = Image::make($this->_file)->resize($small_width, $small_height)->stream();
            Storage::disk($disk)->put($saveImageThumbmail, $resizeThumbnailImage);
        }
        
        
        return true;
    }
    
    public function cropImage($savePath,$disk,$saveWidth,$saveHeight,$x=0,$y=0){
        
        $cropImage = Image::make($this->_file)->crop($saveWidth, $saveHeight,$x,$y)->stream();
        Storage::disk($disk)->put($savePath, $cropImage);
        
    }

    //设置显示 && //设置隐藏
    public function doHidden($media_id,$hidden='0',$type='')
    {
        $file_type = $type=='image'?'图片':'文件';
        
        $media = Media::where(['id'=>$media_id])->first();
        
        if(!$media){
            return $this->response('fail',$file_type.'不存在');
        }
        
        Media::where(['id'=>$media_id])->update([
            'hidden'=>$hidden,
        ]);

        return $this->response('success',$file_type.'已更新');
    }
    
    
    //删除图片
    public function doRemove($media_id,$type='')
    {
        
        $file_type = $type=='image'?'图片':'文件';
        
        $media = Media::where(['id'=>$media_id])->first();
        
        if(!$media){
            return $this->response('fail',$file_type.'不存在');
        }
        
        //其实我们不用在意文件是否存在
        $path_dir = pathinfo($media->path,PATHINFO_DIRNAME);
        $path_file = pathinfo($media->path,PATHINFO_BASENAME);
        
        if(strstr($media->type,'FILE')===false){
            $path_file_p = $path_dir.'/p'.$path_file;
            $path_file_m = $path_dir.'/m'.$path_file;
            $path_file_s = $path_dir.'/s'.$path_file;
            $path_file_l = $path_dir.'/l'.$path_file;
        }else{
            $path_file_p = $path_dir.'/'.$path_file;
        }
        
        //从数据库删除,并自动物理删除
        Media::destroy([$media_id]);

        //Mediatable::where(['media_id'=>$media_id])->delete();


        return $this->response('success',$file_type.'已删除');
        
    }
    
    public function generateTargetName($targetFolder, $extension = 'jpg', $chunk = FALSE) {
       if (!$extension) {
           $extension = trim(pathinfo($this->_file->getClientOriginalExtension(), PATHINFO_EXTENSION), '.');
       }
       
       do {
          if ($chunk) {
             $name = Str::random(12);
             $subdir = sprintf('%05d', mt_rand(0, 99999)).'/';
          } else {
             $name = Str::random(12);
             $subdir = '';
          }
          $path = "$targetFolder/{$subdir}$name.$extension";
       } while(file_exists($path));
       
       return $path;
    }
    
    protected function header($str) {
        header($str);
    }

    protected function get_query_param($id) {
        return @$_GET[$id];
    }

    protected function get_server_var($id) {
        return @$_SERVER[$id];
    }

    protected function get_version_param() {
        return $this->basename(stripslashes($this->get_query_param('version')));
    }

    protected function get_singular_param_name() {
        return substr($this->options['param_name'], 0, -1);
    }

    protected function get_file_names_params() {
        $params = $this->get_query_param($this->options['param_name']);
        if (!$params) {
            return null;
        }
        foreach ($params as $key => $value) {
            $params[$key] = $this->basename(stripslashes($value));
        }
        return $params;
    }


    protected function send_content_type_header() {
        $this->header('Vary: Accept');
        if (strpos($this->get_server_var('HTTP_ACCEPT'), 'application/json') !== false) {
            $this->header('Content-type: application/json');
        } else {
            $this->header('Content-type: text/plain');
        }
    }

    protected function send_access_control_headers() {
        $this->header('Access-Control-Allow-Origin: '.$this->options['access_control_allow_origin']);
        $this->header('Access-Control-Allow-Credentials: '
            .($this->options['access_control_allow_credentials'] ? 'true' : 'false'));
        $this->header('Access-Control-Allow-Methods: '
            .implode(', ', $this->options['access_control_allow_methods']));
        $this->header('Access-Control-Allow-Headers: '
            .implode(', ', $this->options['access_control_allow_headers']));
    }
    
    public function response($type,$content, $redirect=''){
        $this->response_content = $content;
        
        $arr = [
            'response_type' => $type,
            'response_msg' => $content,
            'response_url' => $redirect
        ];
        
        $code=422;
        if($type=='success'){
            $code=200;
        }
        
        $header = [];
        if ($this->get_server_var('HTTP_CONTENT_RANGE')) {
            $files = isset($content[$this->options['param_name']]) ? $content[$this->options['param_name']] : null;
            
            if ($files && is_array($files) && is_object($files[0]) && $files[0]->size) {
                $header = ['Range: 0-'.($this->fix_integer_overflow((int)$files[0]->size) - 1)];
            }
        }
        
        return Response::json($arr, $code, $header);
    }

    public function get_response () {
        return $this->response_content;
    }

    public function head($success = true) {
        
        if(!$success){
            $this->header('HTTP/1.1 422 Unprocessable Entity');
        }
        
        $this->header('Pragma: no-cache');
        $this->header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->header('Content-Disposition: inline; filename="files.json"');
        // Prevent Internet Explorer from MIME-sniffing the content-type:
        $this->header('X-Content-Type-Options: nosniff');
        if ($this->options['access_control_allow_origin']) {
            $this->send_access_control_headers();
        }
        
        $this->send_content_type_header();
    }

    public function get($json_response = true) {
        //
    }

    public function post($request) {
        
        $json_response = $this->options['json_response'];
        
        if(!$request){
            return $this->response('fail',$this->get_error_message(8));
        }
        
        if ($this->get_query_param('_method') === 'DELETE') {
            return $this->delete($json_response);
        }
        
        // Parse the Content-Disposition header, if available:
        $content_disposition_header = $this->get_server_var('HTTP_CONTENT_DISPOSITION');
        
        //$file_name = $content_disposition_header ? rawurldecode(preg_replace('/(^[^"]+")|("$)/','',$content_disposition_header)) : null;
        
        // Parse the Content-Range header, which has the following form:
        // Content-Range: bytes 0-524287/2000000
        $content_range_header = $this->get_server_var('HTTP_CONTENT_RANGE');
        $content_range = $content_range_header ? preg_split('/[^0-9]+/', $content_range_header) : null;
        $size =  $content_range ? $content_range[3] : null;
        $is_crop = $this->options['is_crop'];

        $files = [];
        $msg = '';
        
        if ($request) {
            return $this->handle_file_upload($request,$size,$content_range,$files,$is_crop,$msg);
        }
        
        
        return $this->response('fail',$msg);
    }

    public function delete($json_response = true) {
        //
    }

    protected function basename($filepath, $suffix = null) {
        $splited = preg_split('/\//', rtrim ($filepath, '/ '));
        return substr(basename('X'.$splited[count($splited)-1], $suffix), 1);
    }
    
    
    public function viewFile(Filesystem $files, Request $request,$folder,$filename){
        
        if(!$this->getUser('operate')){
            abort(404);
        }
        
        if(strstr($folder,'-')){
            
            $folders = explode('-',$folder);
            $folder = implode('/',$folders);
        }
        
        $path = storage_path('app/public/images/' . $folder . '/' . $filename);
        
        if (!$files->exists($path)) {
            abort(404);
        }

        $file = $files->get($path);
        $type = $files->mimeType($path);
        
        

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
        
    }
    
}
