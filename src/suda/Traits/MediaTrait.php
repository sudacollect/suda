<?php

namespace Gtd\Suda\Traits;

use Gtd\Suda\Models\Mediatable;
use Gtd\Suda\Models\Media;

use Illuminate\Support\Facades\DB;

trait MediaTrait
{
    //返回多对多关系
    public function mediatabled()
    {
        return $this->morphMany(Mediatable::class, 'mediatable');
    }
    
    //返回medias
    public function medias()
    {
        return $this->morphToMany(Media::class, 'mediatable')->withTimestamps();
    }
    
    public function createMediatables($media_ids,$position='')
    {
        $media_ids = $this->makeidsArray($media_ids);
        
        foreach((array) $media_ids as $media_id){
            
            $media = Media::where('id',$media_id)->first();
            
            if(!$media){
                continue;
            }
            
            $position_str = $position;
            if(is_array($position)){
                if(array_key_exists($media->id,$position)){
                    $position_str = $position[$media->id];
                }
            }
            
            $this->medias()->sync([$media->id=>['position'=>$position_str]],false);
        }
        return;
        $this->medias()->detach();
    }
    
    public function getMedias($by = 'id')
    {
        return $this->medias()->pluck($by);
    }
    
    public function hasMedia($position='')
    {
        return $this->getMedias();
    }
    
    public function removeMediatable($media_id,$position='')
    {
        if($position){
            return $this->mediatabled()->where('media_id', $media_id)->where('position',$position)->delete();
        }
        return $this->mediatabled()->where('media_id', $media_id)->delete();
    }
    
    public function scopeWithMediatable($query,$media_id)
    {
        
        $media = Media::where('id', $media_id)->first();

        return $query->whereHas('mediatabled', function($q) use($media) {
            $q->where('media', $media->id);
        });
    }
    
    public function makeidsArray($ids)
    {
         if (is_array($ids)) {
              return $ids;
         } else if (is_string($ids)) {
              return explode('|', $ids);
         }
         return (array) $ids;
    }
}