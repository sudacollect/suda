<?php
/**
 * ComponentController.php
 * description
 * date 2017-11-06 13:19:35
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */


namespace Gtd\Suda\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;
use Response;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;

class ComponentController extends DashboardController
{
    public $view_in_suda = true;   
    //加载模态框
    public function loadModal(Request $request){
        return false;
        $modal_content = '';
        if($request->modal_content){
            $modal_content = $request->modal_content;
        }
        if(!$modal_content){
            return $this->responseAjax('fail','页面请求异常，请重试');
        }
        
        return $this->display('component.modal');
    }
    
    //加载模态框
    //$layout = ['img,txt,video,carousel']
    public function loadLayout(Request $request,$layout,$type='default'){
        $data = (array)$request->all();
        
        $this->setData('media_type',$type);
        
        $media_name = '';
        if($request->media_name){
            $media_name = $request->media_name;
        }
        $media_max = 1;
        if($request->media_max){
            $media_max = $request->media_max;
        }
        $this->setData('media_name',$media_name); //img_one,img_two
        $this->setData('media_max',$media_max);
        
        return $this->display('component.layout_'.$layout);
    }
    
}
