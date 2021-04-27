<?php

namespace Gtd\Suda\Http\Controllers\Taxonomy;

use Gtd\Suda\Http\Controllers\Controller as BaseController;
use Gtd\Suda\Models\Tag;

use Illuminate\Http\Request;
use Response;

class TagController extends BaseController{
    
    
    
    //get tag by name
    function getTagsByName(Request $request,$returnJson=false)
    {
        
        $model = new Tag;

        $name = $request->name;
        $tags = $model->getTermsByName($name, $request->taxonomy, 5);
        
        if($returnJson){
            $tag_datas = [];
            foreach($tags as $tag){
                $tag_datas[] = [
                    'id'=> $tag->name,
                    'text'=> $tag->name,
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