<?php

namespace Gtd\Suda\Http\Controllers\Admin\Taxonomy;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;
use Gtd\Suda\Models\PostTag;

use Illuminate\Http\Request;
use Response;

class PostTagController extends DashboardController{
    
    
    
    //get tag by name
    function getTagsByName(Request $request,$returnJson=false){
        
        $model = new PostTag;
        
        //$model->addTerm('suda','post_tag');
        
        $name = $request->name;
        $tags = $model->getTermsByName($name,'post_tag');
        
        
        if($returnJson){
            $tag_datas = [];
            foreach($tags as $tag){
                $tag_datas[] = [
                    'slug'=> $tag->slug,
                    'name'=> $tag->name,
                ];
            }
            
            return Response::json(['tags'=>$tag_datas], 200);
        }
        
        return $tags;
    }
    
    //get all keywords
    function keywords(){
        
    }
    
    
}