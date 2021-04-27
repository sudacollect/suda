<?php

namespace Gtd\MediaManager;

use Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Filesystem\Filesystem;

use Gtd\MediaManager\App\Commands\PackageSetup;
// use ctf0\PackageChangeLog\PackageChangeLogServiceProvider;

class MediaManagerServiceProvider extends ServiceProvider
{
    protected $file;

    public function boot()
    {
        $this->file = $this->app['files'];

        $this->packagePublish();
        $this->socketRoute();
        $this->viewComp();
        $this->command();
    }

    /**
     * publish package assets.
     *
     * @return [type] [description]
     */
    protected function packagePublish()
    {
        // config
        $this->publishes([
            __DIR__ . '/config' => config_path(),
        ], 'config');

        // database
        $this->publishes([
            __DIR__ . '/database/MediaManager.sqlite' => database_path('MediaManager.sqlite'),
        ], 'db');

        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations'),
        ], 'migration');

        // resources
        // $this->publishes([
        //     __DIR__ . '/resources/assets' => resource_path('assets/vendor/MediaManager'),
        // ], 'assets');

        // trans
        $this->loadTranslationsFrom(realpath(__DIR__ . '/resources/lang'), 'MediaManager');
        
        
        // $this->publishes([
        //     __DIR__ . '/resources/lang' => resource_path('lang/vendor/MediaManager'),
        // ], 'trans');

        // views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'MediaManager');
        
        // $this->publishes([
        //     __DIR__ . '/resources/views' => resource_path('views/vendor/MediaManager'),
        // ], 'view');


        //补充js和图片
        $this->publishes([
            __DIR__ . '/resources/assets/publicfile' => public_path('/'),
        ], 'assets');
    }

    protected function socketRoute()
    {
        Broadcast::channel('User.{id}.media', function ($user, $id) {
            return $user->id == $id;
        });
    }

    /**
     * share data with view.
     *
     * @return [type] [description]
     */
    protected function viewComp()
    {
        $data   = [];
        $config = $this->app['config']->get('mediaManager');
        
        if ($config) {
            // base url
            $url = $this->app['filesystem']
                        ->disk($config['storage_disk'])
                        ->url('/');
            $data['base_url'] = preg_replace('/\/+$/', '/', $url);

            // upload panel bg patterns
            $pattern_path = public_path('vendor/suda/assets/mediamanager/patterns');

            if ($this->file->exists($pattern_path)) {
                $patterns = collect(
                    $this->file->allFiles($pattern_path)
                )->map(function ($item) {
                    $name = str_replace('\\', '/', $item->getPathName());

                    return preg_replace('/.*\/patterns/', '/vendor/suda/assets/mediamanager/patterns', $name);
                });

                $data['patterns'] = json_encode($patterns);
            }

            // share
            view()->composer('MediaManager::_manager', function ($view) use ($data) {
                $view->with($data);
            });
        }
    }

    /**
     * package commands.
     *
     * @return [type] [description]
     */
    protected function command()
    {
        $this->commands([
            PackageSetup::class,
        ]);
    }

    /**
     * extra functionality.
     *
     * @return [type] [description]
     */
    public function register()
    {
        // $this->app->register(PackageChangeLogServiceProvider::class);
    }
}
