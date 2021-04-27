<?php

namespace Gtd\Suda\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Role extends Authenticatable
{
    use Notifiable;
    protected $table = 'roles';
    
    protected $appends = ['permissions','authority_name','level'];
    
    public function operates()
    {
         return $this->hasMany('Gtd\Suda\Models\Operate');
    }

    public function authorites(){
        $auths = [
            'general'=>'普通管理',  //就是根据权限
            'extension'=>'应用管理',//只授权应用
            'operation'=>'运营主管',   //管理员
            'superadmin'=>'超级管理员', //等同于超级管理员
            
        ];
        return $auths;
    }

    public function getAuthoritesByLevel($level=''){
        $auths = [
            '1'=>'general',  //就是根据权限
            '2'=>'extension',//只授权应用
            '6'=>'operation',   //管理员
            '9'=>'superadmin', //等同于超级管理员
        ];

        if($level && array_key_exists($level,$auths)){

            switch($level)
            {
                case 9:
                    return [$auths[1],$auths[2],$auths[6]];
                break;
                case 6:
                    return [$auths[1],$auths[2]];
                break;
            }

            return [$auths[$level]];
        }
        return false;
    }


    public function getLevelAttribute()
    {
        switch($this->authority){
            case 'extension':
                return 2;
            break;

            case 'operation':
                return 6;
            break;

            case 'superadmin':
                return 9;
            break;

            default:
                return 1;
            break;
        }
    }

    public function getAuthorityNameAttribute()
    {
        $auths = $this->authorites();

        if(array_key_exists($this->authority,$auths)){
            return $auths[$this->authority];
        }

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
