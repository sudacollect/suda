<?php
/**
 * MetaServiceProvider.php
 * description
 * date 2017-11-27 23:51:05
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */

namespace Gtd\Suda\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class MetaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('zmeta', function($meta) {
            $title = '';
            if(!empty($meta)){
                if(is_string($meta)){
                    $title = $meta.' - '.config('app.name',trans('suda_lang::press.system_name'));
                }else{
            
                    if(is_array($meta)){
                        $meta = arrayObject($meta);
                    }
            
                    if(property_exists($meta,'title') && !empty($meta->title)){
                        if(property_exists($meta,'settings')){
                            $title = $meta->title.' - '.$meta->settings->site_name;
                        }else{
                            $title = $meta->title.' - '.config('app.name',trans('suda_lang::press.system_name'));
                        }
                    }
                }
        
            }else{
                $title = config('app.name',trans('suda_lang::press.system_name'));
            }
            if(show_copyright()=='true'){
                return $title.' - Powered by Suda';
            }
            return $title;
        });
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