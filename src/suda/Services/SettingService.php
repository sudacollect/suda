<?php

namespace Gtd\Suda\Services;

use View;
use Artisan;
use Illuminate\Support\Arr;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\FileviewFinder;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use Gtd\Suda\Models\Media;
use Gtd\Suda\Models\Setting;

class SettingService
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
                    return $media_data;
                } else {
                    return suda_image($media,['size'=>'large','url'=>true]);
                }
            }
            return null;
        }
        return $values?$values:null;
    }

    public function data()
    {
        if(Cache::store(config('sudaconf.admin_cache','file'))->has('setting')){
            $settings = Cache::store(config('sudaconf.admin_cache','file'))->get('setting');
        }else{
            $settings = $this->updateCache();
        }
        
        $settings['site_name'] = config('app.name',trans('suda_lang::press.system_name'));
        $settings['keywords'] = $settings['site_name'];

        if(array_key_exists('site',$settings)){
            if(array_key_exists('site_name', $settings['site'])){
                $settings['site_name'] = $settings['site']['site_name'];
                $settings['keywords'] = $settings['site_name'];
            }
            if(array_key_exists('seo', $settings['site'])){
                $settings['keywords'] = $settings['site']['seo']['keywords'];
            }
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

    public function updateCache()
    {
        $gets = Setting::whereIn('group', ['dashboard','site','extension','theme'])->get();
    
        $settings_data = [];
        if($gets->count() > 0)
        {
            foreach($gets as $get)
            {
                $settings_data[$get->group][$get->key] = $this->processSettingData($get, false);
            }
        }

        if(isset($settings_data['dashboard']) && !array_key_exists('dashboard_apps',$settings_data['dashboard'])){
            $settings_data['dashboard']['dashboard_apps'] = ['start'=>'on','welcome'=>'on','quickin'=>'on'];
        }
        
        Cache::store(config('sudaconf.admin_cache','file'))->put('setting',$settings_data,86400);
        return $settings_data;
    }
    
}
