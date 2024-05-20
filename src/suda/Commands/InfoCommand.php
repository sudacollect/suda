<?php
/**
 * InfoCommand.php
 * description
 * date 2018-06-01 12:14:31
 * author suda <dev@gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */
 
namespace Gtd\Suda\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

use Illuminate\Support\Facades\Cache;

use Storage;

class InfoCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'suda:info {info?}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'show suda infomation';
    
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
        $this->info('--------- INFO --------');
        
        $name = \Gtd\Suda\Sudacore::NAME;
        $version = \Gtd\Suda\Sudacore::VERSION;

        $license_str = 'PRODUCT:'.$name.' '.$version."\r\n";

        $this->info("\r\n".$license_str);
        $this->info('--------- INFO END --------');

        exit;
        
        $this->info('Everything is Good.');
    }
}