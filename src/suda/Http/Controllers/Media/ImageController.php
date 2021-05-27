<?php
/**
 * ImageController.php
 * description
 * date 2017-11-06 10:23:31
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */

namespace Gtd\Suda\Http\Controllers\Media;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use Storage;

use Gtd\Suda\Http\Controllers\Controller as BaseController;
use Gtd\Suda\Models\Media;
use Gtd\Suda\Models\Setting;
use Illuminate\Support\Str;

class ImageController extends BaseController
{
    public $_file;

    public $_filename;
    
    public $_file_data = [];
    
    public $type_data = [];
    
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
    
    function __construct(){
        
        $this->type_data = Media::getTypes();
        
        $this->filename();

    }

    public function filename($filename="img")
    {
        $this->_filename = $filename;
    }

    public function validateFile(array $data){
        
        $type_data = Media::getTypes();
        
        $typelist = implode(',',$type_data['types']);
        
        $roles = [
            $this->_filename => [
                'bail',
                'required',
                'file',
                'mimes:pdf,zip,doc,docx,xls,xlsx,ppt,pptx,tar,psd,txt,md',
            ],
            'media_type' => [
                'bail',
                'required',
                'in:'.$typelist
            ],
        ];
        
        $messages = [
            'required' => '请选择文件',
            'file' => '请确认上传文件类型正确',
            'mimes' => '不支持的文件类型',
            'in'=>'上传类型错误',
            'media_type.required'=>'需指定媒体类型',
        ];
        return Validator::make($data, $roles,$messages);
    }
    
    //校验上传的图片信息
    public function validateImage(array $data){
        
        $type_data = Media::getTypes();
        
        $typelist = implode(',',$type_data['types']);
        
        $roles = [
            $this->_filename => [
                'bail',
                'required',
                // 'dimensions:min_width=10,min_height=10',
                'mimes:jpeg,bmp,png,jpg,gif,pdf,zip,doc,docx,xls,xlsx,ppt,pptx,tar,psd',
                // 'image'
            ],
            'media_type' => [
                'bail',
                'required',
                'in:'.$typelist
            ],
        ];
        
        $messages = [
            'required' => '请正确选择上传文件',
            // 'image' => '请选择图片文件',
            // 'dimensions' => '图片尺寸不能小于10像素',
            'mimes' => '不支持的文件类型',
            'in'=>'上传类型错误',
            'media_type.required'=>'需指定媒体类型',
        ];
        return Validator::make($data, $roles,$messages);
    }
    
    //验证上传
    public function validateUpload($request,&$msg=''){
        //检查上传的图片,filename = 'img'
        $validator = $this->validateImage($request->all());
        if($validator->fails()){
            $msg = $validator->errors();
            return false;
        }
        //获取资源
        $this->_file = $request->file($this->_filename);
        if($this->_file){
            
            $this->_file_data['extension'] = $this->_file->getClientOriginalExtension();
            
            $this->_file_data['file_type'] = 'file';

            if(in_array($this->_file_data['extension'],['jpeg','bmp','png','jpg','gif','svg'])){
                $size = @getimagesize($this->_file);
                list($sourceWidth, $sourceHeight, $sourceType) = $size;
            
                $this->_file_data['source_width'] = $sourceWidth;
                $this->_file_data['source_height'] = $sourceHeight;
                $this->_file_data['source_type'] = $sourceType;
                $this->_file_data['file_type'] = 'image';
            }
            
            $this->_file_data['size'] = $this->_file->getSize();
            
            
            return $this->_file;
        }
        $msg = '上传失败';
        return false;
    }
    
    public function makeFile($file){
        
        $image = Image::make($file);
        return $image;
    }
    
    public function makeFileFromBinary($data){
        
        $image = Image::make(file_get_contents($data));
        return $image;
    }
    
    public function setFile($file){
        
        $this->_file = $file;
        
    }
    
    public function setFileData($data){
        
        //process file data
        $this->_file_data = $data;
        
    }
    
    
    public function saveImage($options=[],&$msg){
        //验证规则
        //$options = ['media_type','user_type','user_id','resize'=>true, 'crop'=>true,'ratio'=>1, 'quality'=>100, 'storage'=>['local','oss','qiniu']]
        //生成对应目录文件名规则
        
        if(!array_key_exists('user_type',$options) || !array_key_exists('user_id',$options)){
            
            $msg = '缺少上传文件对应的用户信息';
            return false;
            
        }
        
        //support folders
        $media_type = array_get($options,'media_type','upload');
        if(strpos($media_type,'.')){
            $type_path = str_replace('.','/',$media_type);
            
            $media_types = explode('.',$media_type);
            foreach($media_types as &$stype){
                $stype = ucfirst($stype);
            }
            
            $media_type = implode("\\",$media_types);
            
        }else{
            $type_path = $media_type;
            $media_type = ucfirst($media_type);
        }
        
        
        $targetImage = $this->generateTargetName(storage_path('app/public/images'), $this->_file_data['extension'], TRUE);
        $basename = pathinfo($targetImage, PATHINFO_BASENAME);
        $subdir = stringBeginsWith(dirname($targetImage), storage_path('app/public/images'), FALSE, TRUE);
        
        $savePath = 'public/images/'.$type_path.$subdir.'/'.$basename;
        $saveImage = 'public/images/'.$type_path.$subdir.'/p'.$basename;
        $saveImageMedium = 'public/images/'.$type_path.$subdir.'/m'.$basename;
        $saveImageSmall = 'public/images/'.$type_path.$subdir.'/s'.$basename;
        
        
        $sourceWidth = $this->_file_data['source_width'];
        $sourceHeight = $this->_file_data['source_height'];
        $sourceType = $this->_file_data['source_type'];
        
        //宽高都有值，按照实际存储(resize可能会造成图片变形)
        $save_width = array_get($options,'save_width',$sourceWidth);
        $save_height = array_get($options,'save_height',$sourceHeight);

        //获取相应的值
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

        $is_crop = false;
        $media_crop = array_get($options,'media_crop',false);
        
        if($media_crop || (isset($setting['crop']) && $setting['crop']==1)){
            $is_crop = true;
        }

        //medium缩略图
        if($sourceWidth >= $sourceHeight){

            $save_medium_width = array_get($options,'save_medium_width',$setting['size']['medium']['width']);
            
            // if($save_medium_width>$sourceWidth){
            //     $save_medium_width = $sourceWidth;
            // }

            $save_medium_height = ceil(($save_medium_width/$sourceWidth)*$sourceHeight);

            if($is_crop){
                $save_medium_height = array_get($options,'save_medium_height',$setting['size']['medium']['height']);
            }

        }else{

            $save_medium_height = array_get($options,'save_medium_height',$setting['size']['medium']['height']);
            if($save_medium_height>$sourceHeight){
                $save_medium_height = $sourceHeight;
            }
            $save_medium_width = ceil(($save_medium_height/$sourceHeight)*$sourceWidth);

            if($is_crop){
                $save_medium_width = array_get($options,'save_medium_width',$setting['size']['medium']['width']);
            }

        }
        
        //small缩略图
        if($sourceWidth >= $sourceHeight){

            $save_small_width = array_get($options,'save_small_width',$setting['size']['small']['width']);
            // if($save_small_width>$sourceWidth){
            //     $save_small_width = $sourceWidth;
            // }
            $save_small_height = ceil(($save_small_width/$sourceWidth)*$sourceHeight);

            if($is_crop){
                $save_small_height = array_get($options,'save_small_height',$setting['size']['small']['height']);
            }

        }else{

            $save_small_height = array_get($options,'save_small_height',$setting['size']['small']['height']);
            if($save_small_height>$sourceHeight){
                $save_small_height = $sourceHeight;
            }
            $save_small_width = ceil(($save_small_height/$sourceHeight)*$sourceWidth);

            if($is_crop){
                $save_small_width = array_get($options,'save_small_width',$setting['size']['small']['width']);
            }

        }
        
        $saveWidth = $save_width;
        $saveHeight = $save_height;
        
        //宽高只有一个有值，按比例存储，建议一般情况下只给一个值即可
        if(!empty($saveWidth) && empty($saveHeight)){
            $saveHeight = ceil(($saveWidth/$sourceWidth)*$sourceHeight);
        }
        if(!empty($saveHeight) && empty($saveWidth)){
            $saveWidth = ceil(($saveHeight/$sourceHeight)*$sourceWidth);
        }
        //宽高值都没有，则按照原始大小存储
        if(empty($saveWidth) && empty($saveHeight)){
            $saveWidth = $sourceWidth;
            $saveHeight = $sourceHeight;
        }
        
        //=============== start 优先级别最高的特殊设置 ================
        //ratio 按照比例存储，优先级最高，可放大可缩小
        $ratio = array_get($options,'ratio',false);
        $ratio = abs($ratio);
        if($ratio && $ratio > 0){
            //按照比例进行缩放
            $saveWidth = $ratio*$sourceWidth;
            $saveHeight = $ratio*$sourceHeight;
        }
        //=============== end 优先级别最高的特殊设置 ================
        
        // $is_crop = array_get($options,'crop',$is_crop); //默认不剪切
        $isResize = array_get($options,'resize',false); //默认不缩放
        $quality = array_get($options,'quality',100); //默认质量100
        $storage = array_get($options,'storage',''); //默认存储本地

        //判断系统是否设置强制剪切

        
        //没有指定storage时，设置为默认存储
        if(!$storage){
            $storage = config('sudaconf.image.storage','local');
        }
        
        try {
            //先存储，再进行后续动作
            if($storage=='local'){
                //文件存储

                $imagefile = Image::make($this->_file)->stream();
                Storage::disk('local')->put($saveImage, $imagefile);
                
                // $image = Image::make($this->_file);
                // $image->save($saveImage);
                
                if($is_crop){
                    //计算x,y,截取图的中间位置
                    $x = $y = 0;
                    if($sourceWidth >= $save_medium_width){
                        $x = ceil(($sourceWidth-$save_medium_width)/2);
                    }
                    if($sourceHeight >= $save_medium_height){
                        $y = ceil(($sourceHeight-$save_medium_height)/2);
                    }

                    $this->cropImage($saveImageMedium,$storage,$save_medium_width,$save_medium_height,$x,$y);

                    $x = $y = 0;
                    if($sourceWidth >= $save_small_width){
                        $x = ceil(($sourceWidth-$save_small_width)/2);
                    }
                    if($sourceHeight >= $save_small_height){
                        $y = ceil(($sourceHeight-$save_small_height)/2);
                    }
                    $this->cropImage($saveImageSmall,$storage,$save_small_width,$save_small_height,$x,$y);
                }elseif($isResize){
                    $this->resizeImage($saveImageMedium,$storage,$save_medium_width,$save_medium_height,$quality);
                    $this->resizeImage($saveImageSmall,$storage,$save_small_width,$save_small_height,$quality);
                }
                
                $mediaModel = new Media;
                $mediaModel->name = $basename;
                $mediaModel->user_type = array_get($options,'user_type');
                $mediaModel->user_id = array_get($options,'user_id');
                $mediaModel->size = $this->_file_data['size'];
                $mediaModel->path = $savePath;
                $mediaModel->crop = $is_crop;
                $mediaModel->hidden = array_get($options,'hidden',0);
                $mediaModel->type = array_key_exists($sourceType,$this->image_types)?$this->image_types[$sourceType]:$sourceType;
                
                $mediaModel->save();
                return [$mediaModel->id,$savePath];//返回media_id 对应 相对存储地址
            }
            if($storage=='aliyun'){
                return 'oss:'.$savePath;
            }
            if($storage=='aliyun'){
                return 'qiniu:'.$savePath;
            }
        }catch (Exception $Ex) {
           $Error = $Ex;
           return false;
       }
    }
    
    public function resizeImage($savePath,$storage,$saveWidth,$saveHeight,$quality){
        //生成缩略图
        $resizeImage = Image::make($this->_file)->resize($saveWidth, $saveHeight)->stream();
        return Storage::disk('local')->put($savePath, $resizeImage);
    }
    
    public function cropImage($savePath,$storage,$saveWidth,$saveHeight,$x=0,$y=0){
        
        $cropImage = Image::make($this->_file)->crop($saveWidth, $saveHeight,$x,$y)->stream();
        Storage::disk('local')->put($savePath, $cropImage);

    }

    //rebuild图片
    public function rebuildImage($media_id,&$msg='')
    {
        $media = Media::where('id',$media_id)->first();

        //这个并不存在
        $media_path = storage_path('app/'.$media->path);
        
        //获取保存目录
        $dir_path = dirname($media_path);
        $file_name = basename($media_path);
        
        try {
            $media_file = Image::make($dir_path.'/p'.$file_name);
            
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return false;
        }

        // $media_file = $this->makeFile($media_path);

        $source_width = $media_file->width();
        $source_height = $media_file->height();

        

        //保存相关文件

        //较大尺寸=原始尺寸
        // $media_file->save($dir_path.'/p'.$file_name);

        //获取相应的值
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

        $is_crop = false;
        if($media->crop==1 || (isset($setting['crop']) && $setting['crop']==1)){
            $is_crop = true;
        }

        $save_medium_size = $this->getWidthHeight($source_width,$source_height,$setting['size']['medium']['width'],$setting['size']['medium']['height']);
        $save_small_size = $this->getWidthHeight($source_width,$source_height,$setting['size']['small']['width'],$setting['size']['small']['height']);

        if($is_crop){
            $save_medium_width = $setting['size']['medium']['width'];
            $save_medium_height = $setting['size']['medium']['height'];

            $save_small_width = $setting['size']['small']['width'];
            $save_small_height = $setting['size']['small']['height'];

            
            $media_file->fit($save_medium_width,$save_medium_height)->save($dir_path.'/m'.$file_name);
            $media_file->fit($save_small_width,$save_small_height)->save($dir_path.'/s'.$file_name);
            
            
        }else{
            $save_medium_width = $save_medium_size[0];
            $save_medium_height = $save_medium_size[1];

            $save_small_width = $save_small_size[0];
            $save_small_height = $save_small_size[1];

            //保存图片
            $media_file->resize($save_medium_width,$save_medium_height)->save($dir_path.'/m'.$file_name);
            $media_file->resize($save_small_width,$save_small_height)->save($dir_path.'/s'.$file_name);
        }

        

        return true;

    }
    
    //删除图片
    public function removeImage(Request $request){
        
        
        
    }
    
    public function generateTargetName($targetFolder, $extension = 'jpg', $chunk = FALSE) {
       
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
    
    public function getExtesionByMime($mime){
        
        switch($mime){
            
            case 'image/png':
                return 'png';
            break;
            
            case 'image/jpeg':
                return 'jpg';
            break;
            
            case 'image/gif':
                return 'gif';
            break;
            
            case 'image/bmp':
                return 'bmp';
            break;
            
            case 'image/webp':
                return 'webp';
            break;
            
        }
        
    }

    //计算图片的长度宽度
    public function getWidthHeight($source_width,$source_height,$save_width,$save_height)
    {
        //medium缩略图
        if($source_width >= $source_height){
            
            $save_height = ceil(($save_width/$source_width)*$source_height);

            return [$save_width,$save_height];

        }else{

            
            if($save_height>$source_height){
                $save_height = $source_height;
            }
            $save_width = ceil(($save_height/$source_height)*$source_width);

            return [$save_width,$save_height];

        }

    }

    public function saveFile($options=[],&$msg){
        //验证规则
        //$options = ['media_type','user_type','user_id','resize'=>true, 'crop'=>true,'ratio'=>1, 'quality'=>100, 'storage'=>['local','oss','qiniu']]
        //生成对应目录文件名规则
        
        if(!array_key_exists('user_type',$options) || !array_key_exists('user_id',$options)){
            
            $msg = '缺少上传文件对应的用户信息';
            return false;
            
        }
        
        //support folders
        $media_type = array_get($options,'media_type','upload');
        if(strpos($media_type,'.')){
            $type_path = str_replace('.','/',$media_type);
            
            $media_types = explode('.',$media_type);
            foreach($media_types as &$stype){
                $stype = ucfirst($stype);
            }
            
            $media_type = implode("\\",$media_types);
            
        }else{
            $type_path = $media_type;
            $media_type = ucfirst($media_type);
        }
        
        
        $targetFile = $this->generateTargetName(storage_path('app/public/files'), $this->_file_data['extension'], TRUE);
        $basename = pathinfo($targetFile, PATHINFO_BASENAME);
        $subdir = stringBeginsWith(dirname($targetFile), storage_path('app/public/files'), FALSE, TRUE);
        
        $savePath = 'public/files/'.$type_path.$subdir.'/'.$basename;
        $saveFile = 'public/files/'.$type_path.$subdir.'/'.$basename;
        
        
        $storage = array_get($options,'storage',''); //默认存储本地
        
        //没有指定storage时，设置为默认存储
        if(!$storage){
            $storage = config('sudaconf.image.storage','local');
        }
        
        try {
            //先存储，再进行后续动作
            if($storage=='local'){
                //文件存储
                Storage::disk('local')->put($saveFile, file_get_contents($this->_file));
                
                $mediaModel = new Media;
                $mediaModel->name = $basename;
                $mediaModel->user_type = array_get($options,'user_type');
                $mediaModel->user_id = array_get($options,'user_id');
                $mediaModel->size = $this->_file_data['size'];
                $mediaModel->path = $savePath;
                $mediaModel->type = 'FILE|'.$this->_file_data['extension'];
                
                $mediaModel->save();
                return [$mediaModel->id,$savePath];//返回media_id 对应 相对存储地址
            }
            if($storage=='aliyun'){
                return 'oss:'.$savePath;
            }
            if($storage=='aliyun'){
                return 'qiniu:'.$savePath;
            }
        }catch (Exception $Ex) {
           $Error = $Ex;
           return false;
       }
    }
}
