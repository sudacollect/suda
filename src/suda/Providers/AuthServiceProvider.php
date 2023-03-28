<?php

namespace Gtd\Suda\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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

        $this->app['auth']->provider('authsuda_provider', function ($app, array $config) {
            $model = $app['config']['auth.providers.authsuda.model'];
            return new AuthSudaServiceProvider($app['hash'], $model);
        });
        
        //定义dashboard的权限
        
    }

    // protected function customLoadPolicies(){

    //     //从缓存里读取相关的权限配置
    //     $suda_policies = SudaCache::init()->get('suda_policies',[]);

    //     $this->policies = array_merge($suda_policies,$this->policies);

    // }
}
