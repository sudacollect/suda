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
        $extension_dir = config('sudaconf.extension_dir','extensions');
        $ucf_extension_dir = ucfirst($extension_dir);

        $ucf_extname = $this->argument('extension');
        $extname = strtolower($ucf_extname);

        // if ($this->option('list')) {
        //
        //     //列出所有安装的应用
        //
        //     $this->info('Successfully listed all extensions');
        // }
        
        if(!$filesystem->exists(app_path($ucf_extension_dir.'/'.$ucf_extname))){
            $this->info('extension '.$ucf_extname.' not exists');
            exit;
        }

        $this->info('===========START=========');
        
        $to_dir = 'database/migrations/'.$extension_dir.'/'.$ucf_extname;
        $from_path = app_path($ucf_extension_dir.'/'.$ucf_extname.'/publish/database/migrations');

        $dest_folder = public_path($extension_dir.'/'.$extname);

        //安装数据库
        if(!$filesystem->exists(base_path($to_dir))){
            $filesystem->makeDirectory(base_path($to_dir));
        }

        if ($this->argument('run')=='install') {

           
            if($filesystem->exists($from_path)){
                
                $filesystem->copyDirectory($from_path,base_path($to_dir));

                $file_list = $filesystem->files($from_path);
                $sub_directories = $filesystem->directories($from_path);

                $this->info('== Migrating the database tables into your application');

                if($file_list){
                    // foreach($file_list as $file){
                    //     $filesystem->copy((string)$file,base_path('database/migrations/'.$extension_dir.'/'.pathinfo($file, PATHINFO_BASENAME)));
                    // }
                    
                    $this->info('== '.$to_dir);
                    $this->call('migrate',['--path'=>$to_dir]);
                }
                if($sub_directories)
                {
                    foreach($sub_directories as $sub_path)
                    {
                        $this->info('== '.$to_dir.'/'.pathinfo($sub_path, PATHINFO_BASENAME));
                        $this->call('migrate',['--path'=>$to_dir.'/'.pathinfo($sub_path, PATHINFO_BASENAME)]);
                    }
                }
            }

            
            //更新静态文件
            if(!$filesystem->exists(public_path($extension_dir))){
                $filesystem->makeDirectory(public_path($extension_dir));
            }

            if(!$filesystem->exists($dest_folder)){
                $filesystem->makeDirectory($dest_folder);
            }
            
            //安装静态资源
            $filesystem->copyDirectory(app_path($ucf_extension_dir.'/'.$ucf_extname.'/publish/assets'),$dest_folder.'/assets');
            $this->info('== update the assets into your application');


            $this->info('===========END=========');
            $this->info('Successfully installed '.$ucf_extname.' extension');
        }
        
        if ($this->argument('run')=='flush') {
            
            
            if($filesystem->exists($from_path)){

                $filesystem->copyDirectory($from_path,base_path($to_dir));

                $file_list = $filesystem->files($from_path);
                $sub_directories = $filesystem->directories($from_path);
                
                $this->info('== Migrating the database tables into your application');

                if($file_list){
                    // foreach($file_list as $file){
                    //     $filesystem->copy((string)$file,base_path('database/migrations/'.$extension_dir.'/'.pathinfo($file, PATHINFO_BASENAME)));
                    // }

                    
                    $this->info('== '.$to_dir);
                    $this->call('migrate',['--path'=>$to_dir]);
                }

                if($sub_directories)
                {
                    foreach($sub_directories as $sub_path)
                    {
                        $this->info('== '.$to_dir.'/'.pathinfo($sub_path, PATHINFO_BASENAME));
                        $this->call('migrate',['--path'=>$to_dir.'/'.pathinfo($sub_path, PATHINFO_BASENAME)]);
                    }
                }
            }
            
            
            //安装静态资源
            $filesystem->copyDirectory(app_path($ucf_extension_dir.'/'.$ucf_extname.'/publish/assets'),$dest_folder.'/assets');
            $this->info('== update the assets into your application');

            $this->info('===========END=========');

            $resets = 'app/'.$ucf_extension_dir.'/'.$ucf_extname;
            $this->info('Successfully flush '.$resets.'');
            
        }

    }
}