<?php
/**
 * ComponentController.php
 * description
 * date 2017-11-06 13:19:35
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */


namespace Gtd\Suda\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;

class ComponentController extends BaseController
{

    //加载模态框
    //$layout = ['img,txt,video,carousel']
    public function loadLayout(Request $request,$layout,$type='default'){

        //输出的参数
        $outputs = [];

        $data = (array)$request->all();
        
        $outputs['media_type'] =  $type;
        
        $media_name = '';
        if($request->media_name){
            $media_name = $request->media_name;
        }
        $media_max = 1;
        if($request->media_max){
            $media_max = $request->media_max;
        }
        $media_crop = 0;
        if($request->media_crop){
            $media_crop = $request->media_crop;
        }

        $outputs['media_name'] =  $media_name;
        $outputs['media_max'] =  $media_max;
        $outputs['media_crop'] =  $media_crop;

        $layout_site = suda_path('resources/views/site');
        View::addNamespace('view_app', $layout_site);

        return view('view_suda::site/component/layout_'.$layout)->with($outputs);
    }
    
}
