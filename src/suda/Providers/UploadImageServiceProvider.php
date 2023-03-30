<?php
/**
 * UploadImageServiceProvider.php
 * description
 * date 2017-11-04 15:23:20
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */
 



namespace Gtd\Suda\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UploadImageServiceProvider extends ServiceProvider
{
    public function boot()
    {

        Blade::directive('uploadBox', function($components) {
            
            //$components = [media_type,upload_max,columns,data]
            /**
             * $components[0]=media_type: 上传文件的类型，和typetable关联
             * $components[1]=upload_max: 允许最多上传多少张图片
             * $components[2]=columns: 一行几列显示
             * $components[3]=data: 二次编辑时，载入的数据
             * $components[4]=input_name: 对应的input name参数
             * $components[5]=view: 对应的显示页面
             */
            $components = $this->getArguments($components);
            
            $data_string = '';
            if(count($components)>3 && $components[3]){
                $data_string = ', "data"=>'.$components[3];
            }
            
            $input_name = '';
            $media_type = $components[0];

            if(Str::contains($media_type, '@')){
                $media_type = str_replace(['"',"'"],'',$media_type);

                $types = explode('@',$media_type);
                $media_type = "'".$types[0]."'";
                $input_name = isset($types[1])?"'".$types[1]."'":'';

                $data_string .= ', "input_name"=>'.$input_name;
            }else{
                $data_string .= ', "input_name"=>""';
            }

            $is_crop = 0;
            if(count($components)>4){
                $is_crop = $components[4];
            }
            
            $view = "view_suda::media.upload_box";
            
            if(count($components)>5){
                $view = $components[5];
            }
            
            return '<?php
                echo View( "'.$view.'",["media_type"=>'.$media_type.',"max"=>'.$components[1].',"is_crop"=>'.$is_crop.',"columns"=>'.$components[2].$data_string.']);
            ?>';
        });
        
        
        Blade::directive('uploadCroppie', function($components) {
            
            //$components = [data]
            /**
             * media_type: operates/users or other avatar
             * upload_max: 1
             * columns: 1
             * data: 二次编辑时，载入的数据
             */
            
            $components = $this->getArguments($components);
            
            $data_string = '';
            if(count($components)>1){
                if(!empty($components[1])){
                    $data_string = ', "data"=>'.$components[1];
                }
            }
            
            $view = "view_suda::media.upload_croppie";
            
            return '<?php
                echo View( "'.$view.'",["media_type"=>'.$components[0].$data_string.']);
            ?>';
        });
        
    }
    
    private function getArguments($argumentString)
    {
        //return explode(',', str_replace(['[', ']'], '', $argumentString));

        if(Str::startsWith($argumentString, '[')){
            $argumentString = Str::replaceFirst('[','',$argumentString);
            $argumentString = Str::replaceLast(']','',$argumentString);
        }
        
        return explode(',', $argumentString);
    }

    /**
     * 在容器中注册绑定.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}