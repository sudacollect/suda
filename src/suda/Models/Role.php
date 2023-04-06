<?php

namespace Gtd\Suda\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Gtd\Suda\Auth\Authority;

class Role extends Authenticatable
{
    use Notifiable;
    protected $table = 'roles';
    
    protected $appends = ['permissions', 'authority_data'];
    
    public function operates()
    {
         return $this->hasMany('Gtd\Suda\Models\Operate');
    }
    
    

    public function getAuthorityDataAttribute()
    {
        return Authority::fromName($this->authority);
    }
    
    public function getPermissionsAttribute()
    {

        if($this->disable==1){
            return [
                'sys'=>[],
                'exts'=>[],
            ];
        }

        $permissions = unserialize($this->attributes['permission']);
        if(!$this->attributes['permission']){
            $permissions = [
                'sys'=>[],
                'exts'=>[],
            ];
        }

        if(!array_key_exists('sys',$permissions)){
            $permissions['sys'] = [];
        }

        if(!array_key_exists('exts',$permissions)){
            $permissions['exts'] = [];
        }
        if(!is_array($permissions['sys'])){
            $permissions['sys'] = [];
        }
        return $permissions;

    }

}
