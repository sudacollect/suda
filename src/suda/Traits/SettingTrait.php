<?php

 
namespace Gtd\Suda\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Exception;
use Storage;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Log;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Carbon;

use Gtd\Suda\Models\Setting;
use Gtd\Suda\Models\Media;

use Gtd\Suda\Services\SettingService;

trait SettingTrait
{
    protected function processSettingData(Setting $get, $media_data = true)
    {
        $values = $get->values;
        if($get->type == 'serialize' || is_serialized($values))
        {
            return unserialize($values);
        }
        if($get->type == 'image')
        {
            $media = Media::find($values);
            if($media)
            {
                if($media_data)
                {
                    return $media;
                } else {
                    return suda_image($media,['size'=>'large','url'=>true]);
                }
            }
            return null;
        }
        return $values?$values:null;
    }
    // 获取配置
    public function getSettingByKey( $key,$group = '', $default_value=null, $media_data = true )
    {
        if(is_array($key))
        {
            $gets = Setting::where(['group'=>$group])->whereIn('key',$key)->get();
            $values = [];
            if($gets->count() > 0)
            {
                foreach($gets as $get)
                {
                    $values[$get->key] = $this->processSettingData($get, $media_data);
                }
            }
            
            return $values;

        } else {
            $get = Setting::where(['key'=>$key,'group'=>$group])->first();
            $values = $default_value;
            if($get)
            {
                $values = $this->processSettingData($get, $media_data);
            }
            return $values;
        }

        return null;
    }

    // 保存配置
    public function saveSettingByKey( $key, $group, $content, $type='text' )
    {

        if($type == 'serialize')
        {
            $content = serialize($content);
        }

        if($get = Setting::where(['key'=>$key,'group'=>$group])->first()) {
            $data = [
                'type'      => $type,
                'values'    => $content,
            ];
            Setting::where(['key'=>$key,'group'=>$group])->update($data);
        }else{
            $settingModel = new Setting;
            $data = [
                'key'       => $key,
                'group'     => $group,
                'type'      => $type,
                'values'    => $content,
            ];
            $settingModel->fill($data)->save();
        }
        
        (new SettingService)->updateCache();

        return true;
    }
    
    // 删除配置
    public function deleteSettingByKey( $key, $group, $remove_key = true)
    {
        $get = Setting::where(['key'=>$key,'group'=>$group])->first();
        
        if($get)
        {
            if($remove_key)
            {
                Setting::where(['key'=>$key,'group'=>$group])->delete();
            }else{
                Setting::where(['key'=>$key,'group'=>$group])->update([
                    'values'=>'',
                ]);
            }
        }

        return true;
    }

    
}
