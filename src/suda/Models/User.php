<?php

namespace Gtd\Suda\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Gtd\Suda\Traits\Permission;

class User extends Authenticatable
{
    use Permission;
    
    //批量复制白名单
    protected $fillable = [];
    //不能被批量复制
    protected $guarded = [];
    
    
    protected $table = 'users';
    
    //隐藏字段
    protected $hidden = ['password', 'remember_token'];
    
    
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
    
    public function getAll()
    {

        $users = User::where([])->get()->toArray();
        return $users;
        
    }
    
}
