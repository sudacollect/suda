<?php

namespace Gtd\Suda\Http\Controllers\Admin\Compass;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Validator;
use Artisan;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;
use Gtd\Suda\Models\Operate;


class AboutController extends DashboardController
{
    
    public $view_in_suda = true;
    
    
    public function index()
    {

        if(!\Gtd\Suda\Auth\OperateCan::superadmin($this->user))
        {
            return redirect(admin_url('error'));
        }
        
        $this->title(__('suda_lang::press.compass'));
        
        $this->setMenu('tool','tool_compass');
        $this->setData('active_tab','index');

        $this->setData('districts_data',config('suda_districts',[]));
        return $this->display('compass.index');
    }
    
    public function commands(Request $request){
        
        $this->title(__('suda_lang::press.compass'));
        
        $this->setMenu('tool','tool_compass');
        $this->setData('active_tab','commands');
        
        
        
        $artisan_output = '';

        if ($request->isMethod('post')) {
            $command = $request->command;
            $args = $request->args;
            $args = (isset($args)) ? ' '.$args : '';

            try {
                $process = new Process(['php artisan '.$command.$args]);
                $process->run();

                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                $artisan_output = $process->getOutput();

                //$artisan_output = exec('cd ' . base_path() . ' && php artisan ' . $command . $args);
                // Artisan::call($command . $args);
                // $artisan_output = Artisan::output();
            } catch (Exception $e) {
                $artisan_output = $e->getMessage();
            }
        }
        
        $this->setData('artisan_output',$artisan_output);
        
        $commands = $this->getArtisanCommands();
        $this->setData('commands',$commands);
        return $this->display('compass.commands');
    }
    
    public function about(){
        
        $this->title(__('suda_lang::press.compass'));
        
        $this->setMenu('tool','tool_compass');
        $this->setData('active_tab','about');
        return $this->display('compass.about');
    }

    public function demoPage(){
        
        $this->title('演示页面');
        
        $this->setMenu('tool','tool_compass');
        $this->setData('active_tab','about');
        return $this->display('compass.demopage');
    }
    
    public function faq(){
        
        $this->title(__('suda_lang::press.faq'));
        
        $this->setMenu('tool','tool_compass');
        $this->setData('active_tab','faq');
        return $this->display('compass.faq');
    }
    
    
    private function getArtisanCommands()
    {
        Artisan::call('list');
        
        $artisan_output = Artisan::output();
        $artisan_output = $this->cleanArtisanOutput($artisan_output);
        $commands = $this->getCommandsFromOutput($artisan_output);

        return $commands;
    }
    
    
    private function cleanArtisanOutput($output)
    {
        
        $output = array_filter(explode("\n", $output));
        
        $index = array_search('Available commands:', $output);
        
        $output = array_slice($output, $index - 2, count($output));

        return $output;
    }

    private function getCommandsFromOutput($output)
    {
        $commands = [];

        foreach ($output as $output_line) {
            if (empty(trim(substr($output_line, 0, 2)))) {
                $parts = preg_split('/  +/', trim($output_line));
                $command = (object) ['name' => trim(@$parts[0]), 'description' => trim(@$parts[1])];
                array_push($commands, $command);
            }
        }

        return $commands;
    }
    
}
