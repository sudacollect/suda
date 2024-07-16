<?php
/**
 * ExtCommand.php
 * description
 * date 2018-06-01 12:14:31
 * author suda <dev@gtd.xyz>
 * @copyright Suda. All Rights Reserved.
 */
 
namespace Gtd\Suda\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;

use Gtd\Suda\SudaServiceProvider;


class MakeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sudamake:extension {extension}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create extension';
    
    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd().'/composer.phar')) {
            return '"'.PHP_BINARY.'" '.getcwd().'/composer.phar';
        }
        return 'composer';
    }
    public function fire(Filesystem $filesystem)
    {
        return $this->handle($filesystem);
    }
    /**
     * Execute the console command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return void
     */
    public function handle(Filesystem $filesystem)
    {
        $this->files = $filesystem;

        $extension_dir = config('sudaconf.extension_dir','extensions');
        $ucf_extension_dir = ucfirst($extension_dir);
        
        if(!$this->argument('extension'))
        {
            $this->info('Extension missing');
            exit;
        }

        $ucf_extname = ucfirst($this->argument('extension'));
        $extname = strtolower($ucf_extname);

        if($filesystem->exists(app_path($ucf_extension_dir.'/'.$ucf_extname))){
            $this->info('extension '.$ucf_extname.' exists');
            exit;
        }

        $this->info('..........START..........');

        if (!$filesystem->exists(app_path($ucf_extension_dir))) {
            $filesystem->makeDirectory(app_path($ucf_extension_dir));
        }
        
        $ext_path = app_path($ucf_extension_dir.'/'.$ucf_extname);

        $this->info('-> create extension folders');
        $this->info('-> '.$ext_path);
        
        $filesystem->makeDirectory($ext_path);
        $filesystem->makeDirectory($ext_path.'/Controllers');
        $filesystem->makeDirectory($ext_path.'/Controllers/Admin');
        $filesystem->makeDirectory($ext_path.'/Controllers/Site');
        $filesystem->makeDirectory($ext_path.'/publish/assets', 0755, true);
        $filesystem->makeDirectory($ext_path.'/publish/database/migrations',0755, true);
        $filesystem->makeDirectory($ext_path.'/resources/views/admin', 0755, true);
        $filesystem->makeDirectory($ext_path.'/resources/views/site', 0755, true);
        $filesystem->makeDirectory($ext_path.'/routes');
        $filesystem->makeDirectory($ext_path.'/Models');
        
        $this->info('-> create extension config');
        
        $replace = [
            'name'      => $ucf_extname,
            'slug'      => $extname,
            'ucf_slug'  => $ucf_extname,
            'desc'      => 'suda extension',
            'date'      => date('Y-m-d'),
            'author'    => env('APP_NAME')??'suda',
            'email'     => 'test@test.dev',
            'website'   => env('APP_URL')??'https://suda.run',
            'ext_dir'   => $ucf_extension_dir,
            
            'menu'      => __('suda_lang::press.commands.menu'),
            'index'     => __('suda_lang::press.commands.index'),
            'help'      => __('suda_lang::press.commands.help'),
        ];

        
        $config_stub = $this->populateStub($this->getStub('config'), $replace);
        $this->files->put(
            $ext_path.'/config.yaml', $config_stub
        );

        $menu_stub = $this->populateStub($this->getStub('menu'), $replace);
        $this->files->put(
            $ext_path.'/menu.yaml', $menu_stub
        );

        $custom_navi_stub = $this->populateStub($this->getStub('custom_navi'), $replace);
        $this->files->put(
            $ext_path.'/custom_navi.yaml', $custom_navi_stub
        );


        $this->info('-> create extension routes');

        $route_admin_stub = $this->populateStub($this->getStub('route.admin'), $replace);
        $this->files->put(
            $ext_path.'/routes/admin.php', $route_admin_stub
        );

        $route_web_stub = $this->populateStub($this->getStub('route.web'), $replace);
        $this->files->put(
            $ext_path.'/routes/web.php', $route_web_stub
        );

        $route_api_stub = $this->populateStub($this->getStub('route.api'), $replace);
        $this->files->put(
            $ext_path.'/routes/api.php', $route_api_stub
        );


        $this->info('-> create extension controller');

        $controller_admin_stub = $this->populateStub($this->getStub('AdminController'), $replace);
        $this->files->put(
            $ext_path.'/Controllers/AdminController.php', $controller_admin_stub
        );
        $controller_stub = $this->populateStub($this->getStub('Admin/HomeController'), $replace);
        $this->files->put(
            $ext_path.'/Controllers/Admin/HomeController.php', $controller_stub
        );

        $controller_stub = $this->populateStub($this->getStub('SiteController'), $replace);
        $this->files->put(
            $ext_path.'/Controllers/SiteController.php', $controller_stub
        );

        $controller_stub = $this->populateStub($this->getStub('Site/HomeController'), $replace);
        $this->files->put(
            $ext_path.'/Controllers/Site/HomeController.php', $controller_stub
        );

        $this->info('-> create extension views');

        $view_stub = $this->populateStub($this->getStub('views/admin/index.blade'), $replace);
        $this->files->put(
            $ext_path.'/resources/views/admin/index.blade.php', $view_stub
        );

        $view_stub = $this->populateStub($this->getStub('views/admin/help.blade'), $replace);
        $this->files->put(
            $ext_path.'/resources/views/admin/help.blade.php', $view_stub
        );

        $view_stub = $this->populateStub($this->getStub('views/site/index.blade'), $replace);
        $this->files->put(
            $ext_path.'/resources/views/site/index.blade.php', $view_stub
        );

        $this->files->copy($this->stubPath().'/icon.png', $ext_path.'/icon.png');

        $this->info('Successfully created extension.');
    }

    protected function getStub($config_name)
    {
        $stub = $this->stubPath().'/'.$config_name.'.stub';
        return $this->files->get($stub);
    }

    protected function populateStub($stub, $replace)
    {
        foreach($replace as $k=>$v)
        {
            $stub = str_replace(
                ['{{ '.$k.' }}', '{{'.$k.'}}'],
                $v, $stub
            );
        }
        
        return $stub;
    }

    public function stubPath()
    {
        return __DIR__.'/../../../publish/stubs/extension';
    }
}