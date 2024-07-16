<?php
/**
 * InstallCommand.php
 * description
 * date 2018-06-01 12:14:31
 * author suda <dev@gtd.xyz>
 * @copyright Suda. All Rights Reserved.
 */
 
namespace Gtd\Suda\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Gtd\Suda\Traits\Seedable;
use Gtd\Suda\SudaServiceProvider;
use Gtd\Suda\Sudacore;

use willvincent\Feeds\FeedsServiceProvider;

class InstallCommand extends Command
{
    use Seedable;
    protected $seedersPath = __DIR__.'/../../../publish/database/seeders/';
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
        
        //updatea assets
        $tags = ['assets','seeds','demo'];
        $theme_tag = ['themes'];
        
        //providers
        $this->call('vendor:publish', ['--provider' => SudaServiceProvider::class, '--tag' => $tags,'--force'=>true]);
        $this->call('vendor:publish', ['--provider' => SudaServiceProvider::class, '--tag' => $theme_tag]);
        $this->call('vendor:publish', ['--provider' => FeedsServiceProvider::class]);
        
        
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
        $composer_commands = [
            $composer.' dump-autoload'
        ];
        
        // run dump-autoload
        Process::fromShellCommandline(implode(' && ', $composer_commands),null,[],null,null)->run();
        
        //加载路由器
        $this->info('Add Suda routes to routes/web.php');
        $routes_contents = $filesystem->get(base_path('routes/web.php'));
        if (false === strpos($routes_contents, 'Sudacore::routes()')) {
            $filesystem->append(
                base_path('routes/web.php'),
                "\n\nSudacore::routes();\n"
            );
        }
        
        // 注册配置文件
        $this->call('vendor:publish', ['--provider' => SudaServiceProvider::class, '--tag' => 'config']);

        // 注册livewire
        $this->call('livewire:publish', ['--assets'=>true]);
        $this->call('livewire:publish', ['--config'=>true]);
        
        $this->info('Add storage symlink to your public folder');
        $this->call('storage:link');


        
        $this->info('Successfully installed Suda!');
    }

}