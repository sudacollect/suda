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
use Gtd\Suda\Services\ExtensionService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use View;

class ExtensionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //可以这么绑定,这需要use App;
        App::bind("suda_extension",function(){
            return new ExtensionService();
        });
    }
}
