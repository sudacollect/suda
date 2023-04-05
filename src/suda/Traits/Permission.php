<?php

namespace Gtd\Suda\Traits;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Gtd\Suda\Models\Role;


trait Permission
{
    
    
    public function role()
    {
        return $this->belongsTo('Gtd\Suda\Models\Role');
    }
    
    public function permissions()
    {
        if($this->superadmin==1){
            return (object)['permission'=>''];
        }
        return (object)['permission'=>$this->role->permissions];
    }
    
    public function setRole($name)
    {
        $role = Role::where('name', '=', $name)->first();

        if ($role) {
            $this->role()->associate($role);
            $this->save();
        }

        return $this;
    }
    
    public function hasRole($name)
    {
        $roles = $this->role()->pluck('name')->toArray();

        foreach ((is_array($name) ? $name : [$name]) as $role) {
            if (in_array($role, $roles)) {
                return true;
            }
        }

        return false;
    }
    
    //$actions = [ext.policy.method]
    public function hasPermission($actions)
    {
        if(count($actions)<2)
        {
            return false;
        }
        
        if($this->role && !empty($this->role->permissions)){
            $permissions = $this->role->permissions;
            if(is_string($this->role->permissions)){
                $permissions = unserialize($this->role->permissions);
            }
            if(!is_array($permissions) || count($permissions)<1){
                return false;
            }

            $extension_permission = false;
            if(strpos($actions[0],'extension#')!==false)
            {
                $extension_tag = explode('#',$actions[0]);
                if(isset($extension_tag[1]) && !empty($extension_tag[1]))
                {
                    $extension_slug = $extension_tag[1];
                    $extension_permission = true;
                }else{
                    return false;
                }
            }
            
            if($extension_permission && array_key_exists('exts',$permissions)){
                
                $policy = $actions[1];
                $method = '';
                if(isset($actions[2]))
                {
                    $method = $actions[2];
                }

                $method_button = '';
                if(isset($actions[3]))
                {
                    $method_button = $actions[3];
                }
                
                //权限判断

                //判断是否有应用
                if(array_key_exists($extension_slug,$permissions['exts'])){
                    //判断菜单组
                    if(array_key_exists($policy,$permissions['exts'][$extension_slug])){

                        //判断菜单
                        if(empty($method) || (!empty($method) && array_key_exists($method,(array)$permissions['exts'][$extension_slug][$policy]))){

                            //判断具体的button
                            if(empty($method_button) || (!empty($method_button) && array_key_exists($method_button,(array)$permissions['exts'][$extension_slug][$policy][$method])))
                            {
                                return true;
                            }
                            
                        }

                    }

                }
                
            }elseif(!$extension_permission && array_key_exists('sys',$permissions)){

                $policy = $actions[0];
                $method = $actions[1];

                //权限判断
                if(array_key_exists($policy,$permissions['sys'])){
                    if(array_key_exists($method,(array)$permissions['sys'][$policy])){
                        return true;
                    }
                }
            }

        }
        return false;
    }

    public function hasPermissionOrFail($actions)
    {
        if (!$this->hasPermission($actions)) {
            throw new UnauthorizedHttpException(null);
        }

        return true;
    }

    public function hasPermissionOrAbort($actions, $statusCode = 403)
    {
        if (!$this->hasPermission($actions)) {
            return abort($statusCode);
        }

        return true;
    }
}
