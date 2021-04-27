<?php

namespace Gtd\Suda\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Gtd\Suda\Traits\HasTaxonomies;
use Gtd\Suda\Traits\MediaTrait;

class Page extends Model
{
    use SoftDeletes;
    use HasTaxonomies;
    use MediaTrait;
    
    protected $table = 'pages';
    protected $fillable = [
        'disable'
    ];

    protected $appends = [
        'real_url'
    ];
    
    public function operate()
    {
        return $this->belongsTo('Gtd\Suda\Models\Operate','operate_id','id');
    }
    
    public function heroimage()
    {
        return $this->hasOne('Gtd\Suda\Models\Mediatable','mediatable_id','id')->where('mediatable_type','Gtd\Suda\Models\Page')->where('position','hero_image')->with('media');
    }

    public function getRealUrlAttribute(){

        if(!empty($this->redirect_url)){
            return $this->redirect_url;
        }
        
        if(!empty($this->slug)){
            return url('page/'.$this->slug);
        }else{
            return url('page/'.$this->id);
        }

    }
    
    public function getSlugAttribute($value){
        
        if(!empty($value)){
            return $value;
        }else{
            return '';
        }

    }
    
    public function tags($tags){
        return $this->morphMany('Gtd\Suda\Models\Taxonomy', 'taxable');
    }
    
    public function getAll($limit=30){
        
    }
}
