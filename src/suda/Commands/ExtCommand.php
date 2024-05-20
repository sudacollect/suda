<?php
/**
 * ExtCommand.php
 * description
 * date 2018-06-01 12:14:31
 * author suda <dev@gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */
 
namespace Gtd\Suda\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;

use Gtd\Suda\Traits\Seedable;

use Gtd\Suda\SudaServiceProvider;


class ExtCommand extends Command
{
    use Seedable;
    protected $seedersPath = __DIR__.'/../../../publish/database/seeders/';
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'suda:extension {run} {extension}';
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
        $extension_dir = config('sudaconf.extension_dir','extensions');
        $ucf_extension_dir = ucfirst($extension_dir);

        $ucf_extname = ucfirst($this->argument('extension'));
        $extname = strtolower($ucf_extname);

        app('suda_extension')->updateLocalCache();

        $ext = app('suda_extension')->use($extname);
        if(!$ext->extension || count($ext->extension) < 1 )
        {
            $this->info('extension not found');
            return;
        }

        $this->info('........................ START ........................');
        
        // $to_dir = 'database/migrations/'.$extension_dir.'/'.$ucf_extname;

        $migration_path = $ext->extension['path'].'/publish/database/migrations';
        $dest_folder = public_path($extension_dir.'/'.$extname);

        //安装数据库

        if ($this->argument('run')=='info') {
            $this->info('name: '.$ext->extension['name']);
            $this->info('slug: '.$ext->extension['slug']);
            $this->info('version: '.$ext->extension['version']);
            $this->info('author: '.$ext->extension['author']);
            $this->info('date: '.$ext->extension['date']);
            $this->info('path: '.$ext->extension['path']);
        }
        
        if ($this->argument('run')=='install') {
            $msg = '';
            $result = app('suda_extension')->install($extname,false,$msg);

            $this->info('........................END........................');
            if(!$result)
            {
                $this->info($msg);
            }else{
                $this->info('Successfully installed '.$ucf_extname.' extension');
            }
        }
        
        if ($this->argument('run')=='flush') {
            
            
            if($filesystem->exists($migration_path)){
                
                $file_list = $filesystem->files($migration_path);
                $sub_directories = $filesystem->directories($migration_path);
                
                $this->info('......Migrating the database tables');

                if($file_list){
                    
                    
                    $this->info('......'.$migration_path);
                    $this->call('migrate',['--path'=>$migration_path]);
                }

                if($sub_directories)
                {
                    foreach($sub_directories as $sub_path)
                    {
                        $this->info('......'.$migration_path.'/'.pathinfo($sub_path, PATHINFO_BASENAME));
                        $this->call('migrate',['--path'=>$migration_path.'/'.pathinfo($sub_path, PATHINFO_BASENAME)]);
                    }
                }
            }
            
            
            //安装静态资源
            $filesystem->copyDirectory($ext->extension['path'].'/publish/assets',$dest_folder.'/assets');
            $this->info('......reset the assets');

            // copy logo
            if($filesystem->exists($ext->extension['path'].'/icon.png')){
                $filesystem->copy($ext->extension['path'].'/icon.png',$dest_folder.'/icon.png');
            }

            $this->info('........................END........................');

            $resets = 'app/'.$ucf_extension_dir.'/'.$ucf_extname;
            $this->info('Successfully flush '.$resets.'');
            
        }

    }
}