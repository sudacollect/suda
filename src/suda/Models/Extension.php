<?php

namespace Gtd\Suda\Models;

use Illuminate\Database\Eloquent\Model;
use Gtd\Suda\Traits\HasTaxonomies;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


class Extension extends Model
{
    
    
    public function heroimage()
    {
        return $this->hasOne('Gtd\Suda\Models\Media','id','hero_image')
            ->where('typetable_type','Gtd\Suda\Models\Extension');
    }
    
    public function medias()
    {
        return $this->morphMany('Gtd\Suda\Models\Media', 'typetable');
    }
    
    
    
}
