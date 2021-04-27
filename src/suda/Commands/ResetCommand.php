<?php
/**
 * resetCommand.php
 * description
 * date 2018-06-01 12:14:31
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */
 
namespace Gtd\Suda\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Intervention\Image\ImageServiceProviderLaravel5;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

use Gtd\Suda\Traits\Seedable;
use Gtd\Suda\SudaServiceProvider;


class ResetCommand extends Command
{
    use Seedable;
    protected $seedersPath = __DIR__.'/../../../publish/database/seeds/';
    /**
     * The console command name.
     *
     * @var string
     */
    
    protected $signature = 'suda:reset {reset}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Suda Settings';
    protected function getOptions()
    {
        return [
            // ['config', null, InputOption::VALUE_NONE, 'reset config', null],
            // ['themes', null, InputOption::VALUE_NONE, 'reset themes', null],
            // ['assets', null, InputOption::VALUE_NONE, 'reset assets', null],
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
        $resets = '';
        
        if ($this->argument('reset')=='config') {
            $config_tag = ['config'];
            $this->call('vendor:publish', ['--provider' => SudaServiceProvider::class, '--tag' => $config_tag,'--force'=>true]);
            
            $resets = 'config/sudaconf.php';
        }

        if ($this->argument('reset')=='area') {
            $config_tag = ['area'];
            $this->call('vendor:publish', ['--provider' => SudaServiceProvider::class, '--tag' => $config_tag,'--force'=>true]);
            
            $resets = 'config/suda_districts.php';
        }
        
        if ($this->argument('reset')=='themes') {
            
            $theme_tag = ['core_themes'];
            $this->call('vendor:publish', ['--provider' => SudaServiceProvider::class, '--tag' => $theme_tag,'--force'=>true]);
            
            $resets = 'public/theme';
        }
        
        if ($this->argument('reset')=='assets') {
            
            $this->info('Publishing the Suda assets');
            //更新静态资源数据表
            $tags = ['core_assets'];
            //注册类
            $this->call('vendor:publish', ['--provider' => SudaServiceProvider::class, '--tag' => $tags,'--force'=>true]);
            //注册配置文件
            $this->call('vendor:publish', ['--provider' => SudaServiceProvider::class, '--tag' => 'config']);
            
            $resets = 'public/vendor/suda';
        }
        
        
        $this->info('Successfully reset '.$resets.' files');
    }
}