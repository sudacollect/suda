<?php


namespace Gtd\Suda;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;

use Arrilot\Widgets\ServiceProvider as WidgetsProvider;
use willvincent\Feeds\FeedsServiceProvider;
use Cviebrock\EloquentSluggable\ServiceProvider as SluggableServiceProvider;
use SimpleSoftwareIO\QrCode\QrCodeServiceProvider;

use Gtd\Suda\Http\Middleware\ViewRenderMiddleware;
use Gtd\Suda\Http\Middleware\OperateAdminMiddleware;
use Gtd\Suda\Http\Middleware\OperateExtensionMiddleware;
use Gtd\Suda\Http\Middleware\CertificateMiddleware;
use Gtd\Suda\Http\Middleware\AuthSuperadminMiddleware;

use Gtd\Suda\Providers\ThemeServiceProvider;
use Gtd\Suda\Providers\ExtensionServiceProvider;
use Gtd\Suda\Providers\MetaServiceProvider;
use Gtd\Suda\Providers\PowerServiceProvider;
use Gtd\Suda\Providers\UploadImageServiceProvider;
use Gtd\Suda\Providers\SudaEventServiceProvider;
use Gtd\Suda\Providers\AuthServiceProvider;

use Gtd\Suda\Sudacore;

use Livewire;
use Gtd\Suda\Components\Livewire\CategoryComponent;
use Gtd\Suda\Components\Livewire\SettingComponent;

class SudaServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(Router $router)
    {

        $extension_dir = config('sudaconf.extension_dir','extensions');
        $ucf_extension_dir = ucfirst($extension_dir);
        
        $router->middlewareGroup('admin', [OperateAdminMiddleware::class,CertificateMiddleware::class]);
        $router->middlewareGroup('admin/extension', [OperateAdminMiddleware::class,CertificateMiddleware::class]);
        $router->middlewareGroup('extadmin/extension', [OperateExtensionMiddleware::class,CertificateMiddleware::class]);
        
        $router->pushMiddlewareToGroup('web', ViewRenderMiddleware::class);
        $router->pushMiddlewareToGroup('web', CertificateMiddleware::class);
        
        $router->aliasMiddleware('auth.superadmin', AuthSuperadminMiddleware::class);

        //2020-1-12修改
        $this->loadTranslationsFrom(realpath(__DIR__.'/../../publish/lang'), 'suda_lang');

        $this->loadMigrationsFrom(realpath(__DIR__.'/../../migrations'));
        $this->loadMigrationsFrom(base_path('database/migrations/'.$extension_dir));
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'view_suda');
        $this->loadViewsFrom(app_path($ucf_extension_dir), 'view_extension');

        // 自定义 livewire components
        Livewire::component('category-component', CategoryComponent::class);
        Livewire::component('setting-component', SettingComponent::class);
        
        Paginator::useBootstrap();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(WidgetsProvider::class);
        $this->app->register(FeedsServiceProvider::class);
        $this->app->register(SluggableServiceProvider::class);
        $this->app->register(QrCodeServiceProvider::class);
        
        $this->app->register(AuthServiceProvider::class);
        
        $this->app->register(ThemeServiceProvider::class);
        $this->app->register(ExtensionServiceProvider::class);
        $this->app->register(MetaServiceProvider::class);
        // $this->app->register(PowerServiceProvider::class);
        $this->app->register(UploadImageServiceProvider::class);
        
        $this->app->register(SudaEventServiceProvider::class);
        
        $app = $this->app;
        $app->bind('sudacore', function ($app) {
            return new Sudacore($app);
        });
        
        //注册配置文件
        
        $this->mergeConfigFrom(
            suda_path('/publish/config/sudaconf.php'), 'suda'
        );
        
        if ($this->app->runningInConsole()) {
            $this->registerPublishResources();
            $this->registerConsoleCommands();
        }
        
    }
    
    //注册命令
    private function registerConsoleCommands()
    {
        $this->commands(\Gtd\Suda\Commands\InstallCommand::class);
        $this->commands(\Gtd\Suda\Commands\ResetCommand::class);
        // $this->commands(\Gtd\Suda\Commands\LicenseCommand::class);
        $this->commands(\Gtd\Suda\Commands\InfoCommand::class);
        $this->commands(\Gtd\Suda\Commands\ExtCommand::class);
        $this->commands(\Gtd\Suda\Commands\MakeCommand::class);
        $this->commands(\Gtd\Suda\Commands\AdminCommand::class);
    }
    
    //注册资源
    private function registerPublishResources()
    {
        $publishPath = suda_path('/publish');

        $publishes = [
            'config' => [
                "{$publishPath}/config/sudaconf.php" => config_path('sudaconf.php'),
            ],
            'area' => [
                "{$publishPath}/config/suda_districts.php" => config_path('suda_districts.php'),
            ],
            'core_themes' => [
                "{$publishPath}/theme/" => public_path('theme'),
            ],
            'core_assets' => [
                "{$publishPath}/assets/" => public_path(config('sudaconf.core_assets_path','/vendor/suda/assets')),
            ],
            'core_demo' => [
                "{$publishPath}/demo/" => storage_path('app/public/images/demo'),
            ],
            // 'seeds' => [
            //     "{$publishPath}/database/seeders/" => database_path('seeds'),
            // ],

        ];

        foreach ($publishes as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }
}
