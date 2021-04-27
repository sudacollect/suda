<?php

namespace Gtd\Suda\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;


class ThemeWidget extends Model
{
        
    protected $table = 'theme_widgets';

    protected $guarded = [];
    public $timestamps = true;
    
    protected $fillable = [
        'app',
        'theme',
        'widget_area',
        'widget_slug',
        'widget_ctl',
        'widget_id',
        'content',
        'order',
    ];

    protected $appends = ['contents'];
    

    public function getContentsAttribute()
    {
        if($this->content && is_string($this->content)){
            return unserialize($this->content);
        }

        return [];
    }

    
    
    
}
