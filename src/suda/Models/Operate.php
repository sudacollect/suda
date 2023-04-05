<?php

namespace Gtd\Suda\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Gtd\Suda\Auth\Authority;
use Gtd\Suda\Contracts\Operate as OperateContract;

use Gtd\Suda\Traits\Permission;
use Gtd\Suda\Traits\HasTaxonomies;
use Gtd\Suda\Traits\MediaTrait;

class Operate extends Authenticatable implements OperateContract
{
    use SoftDeletes;
    use Permission;
    use MediaTrait;
    use HasTaxonomies;
    
    
    protected $fillable = [];
    
    protected $guarded = [];
    
    protected $dates = ['deleted_at'];
    
    protected $table = 'operates';
    
    // hidden
    protected $hidden = ['password', 'remember_token'];
    
    protected $appends = ['level'];
    
    public function avatar()
    {
         return $this->hasOne('Gtd\Suda\Models\Mediatable','mediatable_id','id')->where('mediatable_type','Gtd\Suda\Models\Operate')->where('position','avatar')->with('media');
    }

    public function categories(){
        return $this->morphMany('Gtd\Suda\Models\Taxable', 'taxable')->with(['taxonomy'=>function($query){
            $query->where('taxonomy','org_category')->with('term');
        }]);
    }
    
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
    
    public function isSuperAdmin()
    {
      if ($this->superadmin == 1) {
          return true;
      }
      return false;
    }

    public function getLevelAttribute()
    {
        if($this->superadmin == 1)
        {
            return Authority::superadmin->name;
        }
        if($this->role && $this->role->disable == 0)
        {
            return $this->role->authority;
        }
        return '';
    }

    public function getPermissionAttribute($value)
    {
        if(!empty($value)){
            return unserialize($value);
        }
        return [];
        
    }
    
}
