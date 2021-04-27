<?php
/**
 * ThemeServiceProvider.php
 * description
 * date 2017-06-06 11:00:57
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */
 



namespace Gtd\Suda\Providers;

use App;
use Gtd\Suda\Services\ThemeService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use View;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('mobilehead', function($theme_name) {
            
            //$theme_name = $this->getParameters($theme_name);
            
            $shared_data = View::shared('sdcore');
            $theme_name = $shared_data->theme;
            
            if(config('app.debug')){
                $style_files = [
                    '/theme/mobile/'.$theme_name.'/design/style.css',
                ];
            }else{
                $style_files = [
                    '/theme/mobile/'.$theme_name.'/design/style.min.css',
                ];
            }
            
            
            $string = '';
            foreach($style_files as $style){
                $string .= '<link rel="stylesheet" href="'.$style.'">';
            }
            
            return $string;
        });
        
        Blade::directive('sitehead', function($theme_name) {
            
            //$theme_name = $this->getParameters($theme_name);
            
            $shared_data = View::shared('sdcore');
            $theme_name = $shared_data->theme;
            
            if(config('app.debug')){
                $style_files = [
                    '/theme/site/'.$theme_name.'/design/style.css',
                ];
            }else{
                $style_files = [
                    '/theme/site/'.$theme_name.'/design/style.min.css',
                ];
            }
            
            $string = '';
            foreach($style_files as $style){
                $string .= '<link rel="stylesheet" href="'.$style.'" />';
            }
            
            return $string;
            
        });
    }
    
    
    protected function getTitle($meta){
        
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
        if(with_copyright()=='true'){
            return $title.' - Powered by Suda';
        }
        return $title;
        
    }
    
    protected function getParameters($params){
        if(is_string($params)){
            $params = str_replace("'",'',$params);
            $params = str_replace('"','',$params);
        }
        return $params;
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //可以这么绑定,这需要use App;
        App::bind("theme",function(){
            return new ThemeService();
        });
    }
}
