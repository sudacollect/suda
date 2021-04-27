<?php

namespace Gtd\Suda\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
    
    //批量复制白名单
    protected $fillable = [];
    //不能被批量复制
    protected $guarded = [];
    
    protected $dates = ['deleted_at'];
    
    protected $table = 'operates';
    
    //隐藏字段
    protected $hidden = ['password', 'remember_token'];
    
    protected $appends = ['user_role'];
    
    
    
    public function avatar()
    {
         return $this->hasOne('Gtd\Suda\Models\Mediatable','mediatable_id','id')->where('mediatable_type','Gtd\Suda\Models\Operate')->where('position','avatar')->with('media');
    }


    public function categories(){
        return $this->morphMany('Gtd\Suda\Models\Taxable', 'taxable')->with(['taxonomy'=>function($query){
            $query->where('taxonomy','org_category')->with('term');
        }]);
    }
    
    public function organization()
    {
        // return $this->belongsTo('Gtd\Suda\Models\Organization');
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

    public function getUserRoleAttribute()
    {
        
        if($this->superadmin==1){
            return 9;
        }else{
            
            $role = $this->role;
            
            if($this->role && $this->role->disable==0){
                return $this->role->level;
            }else{
                return 0;
            }

        }

    }

    public function getPermissionAttribute($value)
    {
        if(!empty($value)){
            return unserialize($value);
        }
        return [];
        
    }
    
}
