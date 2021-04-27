<?php
/**
 * ExtCommand.php
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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;

use Gtd\Suda\Traits\Seedable;

use Gtd\Suda\SudaServiceProvider;


class ExtCommand extends Command
{
    use Seedable;
    protected $seedersPath = __DIR__.'/../../../publish/database/seeds/';
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'suda:ext {run} {extension}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage Suda Extensions';
    // protected function getOptions()
    // {
    //     return [
    //         // ['list', null, InputOption::VALUE_NONE, 'list all extensions', null],
    //         ['install', null, InputOption::VALUE_NONE, 'install a extension', null],
    //         ['flush', null, InputOption::VALUE_NONE, 'flush extensions folder', null],
    //     ];
    // }
    
    //
    // protected function getArguments()
    // {
    //     return [
    //         ['extension', InputArgument::OPTIONAL, 'The name of the extension'],
    //     ];
    // }
    
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
        
        // if ($this->option('list')) {
        //
        //     //列出所有安装的应用
        //
        //     $this->info('Successfully listed all extensions');
        // }
        
        if(!$filesystem->exists(app_path('Extensions/'.$this->argument('extension')))){
            $this->info('extension '.$this->argument('extension').' not exists');
            exit;
        }
        
        if ($this->argument('run')=='install') {

            
            
            //安装数据库
            if(!$filesystem->exists(base_path('database/migrations/extensions'))){
                $filesystem->makeDirectory(base_path('database/migrations/extensions'));
            }

            if($filesystem->exists(app_path('Extensions/'.$this->argument('extension').'/publish/database/migrations'))){
                $file_list = $filesystem->allFiles(app_path('Extensions/'.$this->argument('extension').'/publish/database/migrations'));
            
                if($file_list){
                    foreach($file_list as $file){
                    
                        $filesystem->copy((string)$file,base_path('database/migrations/extensions/'.pathinfo($file, PATHINFO_BASENAME)));
                    }
                
                
                    $this->info('Migrating the database tables into your application');
                    $this->call('migrate');
                }
            }

            if(!$filesystem->exists(public_path('extensions'))){
                $filesystem->makeDirectory(public_path('extensions'));
            }
            
            $dest_folder = public_path('extensions/'.strtolower($this->argument('extension')));
            if(!$filesystem->exists($dest_folder)){
                $filesystem->makeDirectory($dest_folder);
            }
            
            //安装静态资源
            $filesystem->copyDirectory(app_path('Extensions/'.$this->argument('extension').'/publish/assets'),$dest_folder.'/assets');
            
            $this->info('Successfully installed '.$this->argument('extension').' extension');
        }
        
        if ($this->argument('run')=='flush') {
            
            
            if($filesystem->exists(app_path('Extensions/'.$this->argument('extension').'/publish/database/migrations'))){
                $file_list = $filesystem->allFiles(app_path('Extensions/'.$this->argument('extension').'/publish/database/migrations'));
            
                if($file_list){
                    foreach($file_list as $file){
                    
                        $filesystem->copy((string)$file,base_path('database/migrations/extensions/'.pathinfo($file, PATHINFO_BASENAME)));
                    }
                
                
                    $this->info('Migrating the database tables into your application');
                    $this->call('migrate');
                }
            }
            
            
            //安装静态资源
            $dest_folder = public_path('extensions/'.strtolower($this->argument('extension')));
            $filesystem->copyDirectory(app_path('Extensions/'.$this->argument('extension').'/publish/assets'),$dest_folder.'/assets');
            
            $resets = 'app/Extensions';
            $this->info('Successfully flush '.$resets.'');
            
        }

    }
}