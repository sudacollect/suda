<?php

namespace Gtd\Suda;

use Illuminate\Support\Facades\Cache as CacheCore;

class Cache {
    
    public static function init(){
        
        return CacheCore::store(config('sudaconf.admin_cache','file'));
    }
    
}