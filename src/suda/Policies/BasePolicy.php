<?php

namespace Gtd\Suda\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Log;
use Gtd\Suda\Models\Operate as OperateModel;

class BasePolicy
{
    use HandlesAuthorization;
    
    public $name ='';
    public $actions = [];
    public $user ='';
    public $model ='';
    public $extension_slug = '';
    public $policy_name = '';
    public $method_name = '';
    
    public function __call($name, $arguments)
    {
        if (count($arguments) < 2) {
            throw new \InvalidArgumentException('not enough arguments');
        }
        
        $this->name = $name;
        
        $this->user = $user = $arguments[0];
        
        $this->model = $model = $arguments[1];
        
        return $this->detectPermission();
    }

    protected function detectPermission(){
        
        if($this->user->superadmin==1){
            return true;
        }

        $this->actions = $actions = explode('.',$this->name);

        if(count($actions)<2){
            $actions[1] = 'view';
        }
        if(!array_key_exists('1',$actions)){
            return false;
        }
        
        // detect extension
        if(count($this->actions)>=2 && strpos($this->name,'extension#')!==false){

            if(\Gtd\Suda\Auth\OperateCan::operation($this->user))
            {
                return true;
            }

            $extension_tag = explode('#',$actions[0]);
            if(isset($extension_tag[1]) && !empty($extension_tag[1]))
            {
                $this->extension_slug = $extension_tag[1];
            }else{
                return false;
            }
            
            $this->policy_name = $actions[1];
            $this->method_name = '';
            if(isset($actions[2]))
            {
                $this->method_name = $actions[2];
            }
            
        }else{

            $this->policy_name = $actions[0];
            $this->method_name = $actions[1];
            
        }
        
        if(!empty($this->method_name) && method_exists($this,$this->method_name)){
            $method_name_str = $this->method_name;
            return $this->$method_name_str();
        }
        
        return $this->checkPermission();
    }
    
    //$action = view|create|update|delete
    protected function checkPermission()
    {
        $user = $this->user;
        // $model = $this->model;
        // $action = $this->name;

        // $policy = $this->policy_name;
        // $method = $this->method_name;
        
        // $policies = '';
        
        // if(property_exists($model,'policy')){
        //     $policies = $model->policy;
            
        //     if(is_string($policies)){
                
        //         if($policy == $policies){
        //             return true;
        //         }
                
        //     }elseif(is_array($policies)){
        //         if(in_array($policy,$policies)){
        //             return true;
        //         }
        //     }
        // }
        // Log::info('请求权限',$this->actions);
        return $user->hasPermission($this->actions);
    }
    
    
}
