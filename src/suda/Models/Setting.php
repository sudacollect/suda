<?php

namespace Gtd\Suda\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Gtd\Suda\Traits\MediaTrait;
use Gtd\Suda\Models\Media;

class Setting extends Model
{
    use MediaTrait;
        
    protected $table = 'settings';
    protected $setting = [];
    protected $guarded = [];
    public $timestamps = true;
    
    protected $appends = [];

    //定义可控制的授权
    public $policy = ['setting','appearance','tool'];
    
    
}
