<?php

namespace Gtd\Suda\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Gtd\Suda\Traits\MediaTrait;
use Gtd\Suda\Models\Media;

class Setting extends Model
{
    use MediaTrait;
        
    protected $table = 'settings';
    protected $setting = [];
    protected $guarded = [];
    public $timestamps = true;
    
    protected $appends = ['value_array'];

    //定义可控制的授权
    public $policy = ['setting','appearance','tool'];
    
    public static function getSettings()
    {
        
        
        if(Cache::store(config('sudaconf.admin_cache','file'))->has('setting')){
            $settings = Cache::store(config('sudaconf.admin_cache','file'))->get('setting');
        }else{
            $settings = self::updateSettings();
        }
        
        if(!array_key_exists('site_name',$settings)){
            $settings['site_name'] = config('app.name',trans('suda_lang::press.system_name'));
        }
        if(!array_key_exists('keywords',$settings)){
            $settings['keywords'] = $settings['site_name'];
        }
        if(!array_key_exists('description',$settings)){
            $settings['description'] = $settings['site_name'];
        }

        if(!array_key_exists('share_image',$settings['site'])){
            if(!array_key_exists('og_image',$settings)){
                $settings['og_image'] = suda_asset('/images/share.png');
            }else{
                $settings['og_image'] = $settings['og_image'];
            }
        }else{
            $settings['og_image'] = $settings['site']['share_image'];
        }
        
        
        return $settings;
    }
    
    public static function updateSettings($group='')
    {
        
        $settings = self::where([])->get();
    
        $settings_data = [];
        
        foreach((array)$settings as $k=>$v){
            if($v){
                foreach($v as $key=>$values){
                    
                    $vvalues = $values->values;
                    if($values->type == "image"){
                        //获取图片
                        $media = Media::where(['id'=>$values->values])->first();
                        if($media){
                            $vvalues = suda_image($media,['size'=>'large','url'=>true]);
                        }
                        
                    }
                    
                    if($values->group=='site'){
                        $settings_data[$values->key] = $vvalues;
                    }

                    if($values->key=='dashboard_apps'){
                        $vvalues = unserialize($vvalues);
                    }

                    $settings_data[$values->group][$values->key] = $vvalues;
                }
            }
        }

        if(isset($settings_data['dashboard']) && !array_key_exists('dashboard_apps',$settings_data['dashboard'])){
            $settings_data['dashboard']['dashboard_apps'] = ['start'=>'on','welcome'=>'on','quickin'=>'on'];
        }
        
        Cache::store(config('sudaconf.admin_cache','file'))->put('setting',$settings_data,86400);
        return $settings_data;
    }
    
    public function getValueArrayAttribute()
    {

        $data = @unserialize($this->values);
        if ($data !== false) {
            return $data;
        }
        return $this->values;

    }
}
