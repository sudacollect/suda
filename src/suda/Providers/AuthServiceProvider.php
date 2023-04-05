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

        Auth::provider('operate_provider', function ($app, array $config) {
            $model = $app['config']['auth.providers.operates.model'];
            return new AuthSudaServiceProvider($app['hash'], $model);
        });
        
        Auth::extend('operate', function (Application $app, string $name, array $config) {
            $guard = new OperateGuard($name,Auth::createUserProvider('operates'),$app['session.store']);
            return $guard;
        });
        
        
        
    }

    // protected function customLoadPolicies(){

    //     //从缓存里读取相关的权限配置
    //     $suda_policies = SudaCache::init()->get('suda_policies',[]);

    //     $this->policies = array_merge($suda_policies,$this->policies);

    // }


    public function __invoke(mixed $var)
    {

        Log::info('test22', [$var]);
        echo '<pre>';
        print_r($var);
        exit();
        
    }
}
