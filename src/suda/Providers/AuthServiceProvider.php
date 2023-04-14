<?php

namespace Gtd\Suda\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Foundation\Application;

use Gtd\Suda\Auth\OperateGuard;

use Gtd\Suda\Providers\AuthSudaServiceProvider;

use Gtd\Suda\Models\Operate as OperateModel;
use Gtd\Suda\Policies\OperatePolicy;
use Gtd\Suda\Policies\SettingPolicy;

use Gtd\Suda\Cache as SudaCache;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        OperateModel::class                 => OperatePolicy::class,
        \Gtd\Suda\Models\Setting::class     => SettingPolicy::class,
        \Gtd\Suda\Models\Role::class        => SettingPolicy::class,
        \Gtd\Suda\Models\Media::class       => SettingPolicy::class,
        \Gtd\Suda\Models\Organization::class => SettingPolicy::class,
        \Gtd\Suda\Models\Menu::class        => SettingPolicy::class,
        \Gtd\Suda\Models\Article::class     => SettingPolicy::class,
        \Gtd\Suda\Models\Page::class        => SettingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        //#1 从缓存获取policies
        //$this->customLoadPolicies();

        $this->registerPolicies();

        Auth::provider('authsuda_provider', function ($app, array $config) {
            // $config
            // Array
            // (
            //     [driver] => authsuda_provider
            //     [model] => Gtd\Suda\Models\Operate
            // )
            
            $model = $config['model'];
            return new AuthSudaServiceProvider($app['hash'], $model);
        });

        // custom guard driver
        // Auth::extend('suda_operate', function (Application $app, string $name, array $config) {
        //     // $config
        //     // Array
        //     // (
        //     //     [driver] => operate
        //     //     [provider] => operates
        //     // )
            
        //     $provider = Auth::createUserProvider('operates');
        //     $guard = new OperateGuard($name,$provider,$app['session.store']);

        //     if (method_exists($guard, 'setCookieJar')) {
        //         $guard->setCookieJar($app['cookie']);
        //     }
    
        //     if (method_exists($guard, 'setDispatcher')) {
        //         $guard->setDispatcher($app['events']);
        //     }
    
        //     if (method_exists($guard, 'setRequest')) {
        //         $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
        //     }
    
        //     if (isset($config['remember'])) {
        //         $guard->setRememberDuration($config['remember']);
        //     }

        //     return $guard;
        // });
    }

}
