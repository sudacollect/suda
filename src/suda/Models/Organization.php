<?php

namespace Gtd\Suda\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Organization extends Authenticatable
{
    use Notifiable;
    protected $table = 'organization';
    
    public function operates()
    {
         return $this->hasMany('Gtd\Suda\Models\Operate');
    }
}
