<?php
/**
 * AdminCommand.php
 * description
 * date 2018-06-01 12:14:31
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */
 
namespace Gtd\Suda\Commands;

use Illuminate\Support\Facades\Hash;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;
use Illuminate\Support\Str;

use Gtd\Suda\Traits\Seedable;

use Gtd\Suda\SudaServiceProvider;

use Gtd\Suda\Models\Operate;


class AdminCommand extends Command
{
    use Seedable;
    protected $seedersPath = __DIR__.'/../../../publish/database/seeders/';
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'suda:admin';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reset admin account & password';
    
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

        $username = $this->ask('Input new admin username:');
        $this->info('Your new admin username is '.$username);

        $email = $this->ask('Input new admin email:');
        $this->info('Your new admin email is '.$email);

        $password = $this->ask('Input new password for '.$username.':');
        $this->info('Your account '.$email.'\'s password is '.$password);


        if ($this->confirm('Confirm username, email and password?')) {
            
            
            //新增账号和密码
            //#TODO 优化密码的加密机制
            $salt = Str::random(6);
            $operateObj = new Operate;
            $password_link = config('sudaconf.password_link','zp');

            $operateObj->username           = $username;
            $operateObj->email              = $email;
            $operateObj->password           = Hash::make($password.$password_link.$salt);
            $operateObj->superadmin         = 1;
            $operateObj->organization_id    = 0;
            $operateObj->is_company         = 0;
            $operateObj->enable             = 1;
            $operateObj->salt               = $salt;
            
            $operateObj->save();

            $this->info('Your login account '.$email.' has been created!');
            exit;
        }

        $this->info('nothing!');
        exit;

    }
}