<?php
/**
 * PowerServiceProvider.php
 * description
 * date 2017-10-27 10:51:05
 * author suda <dev@gtd.xyz>
 * @copyright Suda. All Rights Reserved.
 */

namespace Gtd\Suda\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class PowerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('sudacopyright', function($expression) {
            
            if(show_copyright()=='true'){
                $render  = "<?php \$__env->startSection('sudacopyright'); ?>";
                $render .= "<?php \$__env->stopSection(); ?>";
                $render .= "<?php echo '<div id=\"'.base64_decode('emhpbGEtcG93ZXJlZA==').'\" style=\"color'.':#999'.';font-'.'size:'.'12'.'px;text'.'-align:'.'center'.';margin'.':10p'.'x 0px'.';\">'.base64_decode('UG93ZXJlZCBieSA8YSBocmVmPSJodHRwOi8vemVzdC5xdXlvdWluYy5jb20iPlpFU1Q8L2E+').'</div>'; ?>";
                return $render;
            }
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