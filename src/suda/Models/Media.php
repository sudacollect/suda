<?php

namespace Gtd\Suda\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;
use Log;

use Gtd\Suda\Models\Mediatable;
use Gtd\Suda\Traits\HasTaxonomies;

class Media extends Model
{
    use HasTaxonomies;

    protected $table = 'medias';
    protected $fillable = [
        'name',
        'user_type',
        'user_id',
        'type',
        'size',
        'disk',
        'path',
        'crop',
        'hidden'
    ];
    protected $dispatchesEvents = [
        'deleted' => \Gtd\Suda\Events\MediaDeleted::class
    ];

    protected $appends = ['original_path','original_medium_path','original_large_path','original_small_path','original_type'];
    
    public function scopeName($query, $name)
    {
        return $query->where('name', $name);
    }
    
    public function operate()
    {
        return $this->belongsTo('Gtd\Suda\Models\Operate','user_id','id');
    }
    
    public static function removeFiles($media){
        
        if($media){
            $path = $media->path;
            $path_dir = dirname($path);
            $path_file = basename($media->path);
            
            if(strstr($media->type,'FILE')===false){

                $path_file_p = $path_dir.'/p'.$path_file;   // 原图
                $path_file_m = $path_dir.'/m'.$path_file;   // medium
                $path_file_s = $path_dir.'/s'.$path_file;   // small
                $path_file_l = $path_dir.'/l'.$path_file;   // large
                
                if(Storage::disk('local')->exists($path_file_p)){
                    Storage::disk('local')->delete([$path_file_p,$path_file_m,$path_file_s,$path_file_l]);
                }

            }else{
                $path_file_p = $path_dir.'/'.$path_file;   //文件
                if(Storage::disk('local')->exists($path_file_p)){
                    Storage::disk('local')->delete([$path_file_p]);
                }
            }
            
        }
    }
    
    public static function getTypes(){
        
        //'goods','customize','customize.category','news','store'
        
        $default_types = [
            'page',
            'article',
            'media',
            'setting',
            'editor',
            'operate',
            'user',
            'upload',
        ];
        
        $types_model = [
            'operate'   => "Gtd\\Suda\\Models\\Operate",
            'setting'   => "Gtd\\Suda\\Models\\Setting",
            'page'      => "Gtd\\Suda\\Models\\Page",
            'article'   => "Gtd\\Suda\\Models\\Article",
            'media'     => "Gtd\\Suda\\Models\\Media"
        ];
        $custom_types_model = config('sudaconf.media.types_model',[]);

        $custom_types_model = config('suda_custom.media',[]);
        
        //get types
        $custom_types = array_keys($custom_types_model);
        $types = array_merge($default_types,$custom_types);
        
        $models = [
            'default'=>$types_model,
            'custom'=>$custom_types_model
        ];
        
        return ['types'=>$types,'models'=>$models];
    }
    
    
    public function pages()
    {
        return $this->morphedByMany('Gtd\Suda\Models\Page', 'mediatable', 'mediatables');
    }
    
    public function articles()
    {
        return $this->morphedByMany('Gtd\Suda\Models\Article', 'mediatable', 'mediatables');
    }
    
    public function getOriginalPathAttribute()
    {

        return suda_image($this,['size'=>'large','imageClass'=>'image_show','url'=>true]);

    }
    public function getOriginalMediumPathAttribute()
    {

        return suda_image($this,['size'=>'medium','imageClass'=>'image_show','url'=>true]);

    }
    public function getOriginalLargePathAttribute()
    {

        return suda_image($this,['size'=>'large','imageClass'=>'image_show','url'=>true]);

    }
    public function getOriginalSmallPathAttribute()
    {

        return suda_image($this,['size'=>'small','imageClass'=>'image_show','url'=>true]);

    }
    
    public function getOriginalTypeAttribute()
    {

        $type = $this->type;
        $types = explode('|',$type);
        if($types[0]!='FILE'){
            return 'image';
        }
        return 'file';

    }

    public function tags(){
        return $this->morphMany('Gtd\Suda\Models\Taxable', 'taxable')->with(['taxonomy'=>function($query){
            $query->where('taxonomy','media_tag')->with('term');
        }]);
    }
}
