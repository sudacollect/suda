<?php

namespace Gtd\Suda\Traits;

use Gtd\Suda\Http\Controllers\Media\ImageController;
use Gtd\Suda\Http\Controllers\Media\MediaController;
use Gtd\Suda\Models\Media;

use Illuminate\Support\Facades\DB;

trait AvatarTrait
{
    
    //save base64 image data
    public function uploadCroppie($data,$user_type,$user){
        
        if(!empty($data)){
            $imageHandler = new ImageController;
            
            $file = $imageHandler->makeFileFromBinary($data);
            $file->resize(400,400);
            $imageHandler->setFile($file->stream());
            
            $file_data = [];
            
            $file_size = strlen(base64_decode($data)) - 22;
            
            $file_data['extension'] = $imageHandler->getExtesionByMime($file->mime());
            $file_data['size'] = $file_size;
            $file_data['source_width'] = $file->width();
            $file_data['source_height'] = $file->height();
            $file_data['source_type'] = strtoupper($file_data['extension']);
            
            $imageHandler->setFileData($file_data);
            
            $save_data = [
                'user_type' => $user_type,
                'user_id' =>$user->id,
                'media_type' => $user_type,
                'resize' => true,
                'ratio' => false,
                'hidden'=>'1',//头像属于可以隐藏的图片
            ];
            
            $msg = '';
            $medias = $imageHandler->saveImage($save_data,$msg);
            
            if($medias){
                
                list($media_id,$media_path) = $medias;
                
                $media = Media::where('id',$media_id)->first();
                
                //#1 移除旧头像的关系
                $operate = $user;
                
                if($operate->avatar && $operate->avatar->media_id != $media_id){
                    $operate->removeMediatable($operate->avatar->media_id,'avatar');
                }

                //#2 保存新的头像关系
                $operate->createMediatables($media_id,'avatar');
                
            }
        }
    }

    function human_filesize($bytes, $decimals = 2) {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

}