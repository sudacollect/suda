<?php
/**
 * InstallCommand.php
 * description
 * date 2018-06-01 12:14:31
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */
 
namespace Gtd\Suda\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

use Gtd\Suda\Traits\Seedable;
use Gtd\Suda\SudaServiceProvider;
use Gtd\Suda\Sudacore;

use willvincent\Feeds\FeedsServiceProvider;
use Gtd\Suda\Providers\SendCloudServiceProvider;
use Gtd\MediaManager\MediaManagerServiceProvider;

class InstallCommand extends Command
{
    use Seedable;
    protected $seedersPath = __DIR__.'/../../../publish/database/seeds/';
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'suda:install';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Suda System package';
    protected function getOptions()
    {
        return [
            // ['with-demo', null, InputOption::VALUE_NONE, 'Install with demo data', null],
            // ['without-admin', null, InputOption::VALUE_NONE, 'Install with demo data', null],
        ];
    }
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
        $this->info('Publishing the Suda assets, database, and config files');
        
        //更新静态资源数据表
        $tags = ['core_assets','seeds','core_demo'];
        $theme_tag = ['core_themes'];
        //注册类
        $this->call('vendor:publish', ['--provider' => SudaServiceProvider::class, '--tag' => $tags,'--force'=>true]);
        $this->call('vendor:publish', ['--provider' => SudaServiceProvider::class, '--tag' => $theme_tag]);
        // $this->call('vendor:publish', ['--provider' => ImageServiceProviderLaravel5::class]);
        $this->call('vendor:publish', ['--provider' => FeedsServiceProvider::class]);
        // $this->call('vendor:publish', ['--provider' => SendCloudServiceProvider::class]);

        //媒体管理器MediaManager
        $this->call('vendor:publish', ['--provider' => MediaManagerServiceProvider::class]);
        
        //注册数据表
        $this->info('Migrating the database tables into your application');
        $this->call('migrate');
        
        
        //数据插入
        $this->info('Seeding data into the database');
        $this->seed('SudaDatabaseSeeder');
        
        $this->seed('OperateTableSeeder');
        
        //更新自动加载
        $this->info('Dump the autoloaded and reload files');
        $composer = $this->findComposer();
        $composer_arr = [$composer.' dump-autoload'];
        $process = new Process($composer_arr);
        $process->setTimeout(null); //Setting timeout to null to prevent installation from stopping at a certain point in time
        $process->setWorkingDirectory(base_path())->run();
        
        //加载路由器
        $this->info('Add Suda routes to routes/web.php');
        $routes_contents = $filesystem->get(base_path('routes/web.php'));
        if (false === strpos($routes_contents, 'Sudacore::routes()')) {
            $filesystem->append(
                base_path('routes/web.php'),
                "\n\nSudacore::routes();\n"
            );
        }
        
        // Sudacore::routes();
        
        
        
        //注册配置文件
        $this->call('vendor:publish', ['--provider' => SudaServiceProvider::class, '--tag' => 'config']);
        
        $this->info('Add storage symlink to your public folder');
        $this->call('storage:link');
        
        $this->info('Successfully installed Suda!');
    }
}