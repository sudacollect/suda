<?php
namespace Gtd\Suda\Providers;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Mail\MailManager;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

use Gtd\Suda\SendCloud\SendCloudTransport;

class SendCloudServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        // $this->mergeConfigFrom(
        //     suda_path('publish/config/services.php'),
        //     'services'
        // );


        $this->app->afterResolving(MailManager::class, function (MailManager $mail_manager) {
            $mail_manager->extend("sendcloud", function ($config) {
                
                $api_user = config('services.sendcloud.api_user');
                $api_key = config('services.sendcloud.api_key');

                return new SendCloudTransport($api_user, $api_key);

            });

        });
        
    }

    public function boot()
    {
        
    }
}
