<?php

namespace Gtd\Suda\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use Gtd\Suda\Models\Mediatable;
use Gtd\Suda\Models\Media;
use Gtd\Suda\Models\Taxonomy;

use Gtd\Suda\Http\Controllers\Media\ImageController;
use Gtd\Suda\Http\Controllers\Media\MediaController;

use Illuminate\Support\Facades\View;
use Auth;

trait MediaBoxTrait
{

    public $user;
    public $guard='';
    public $media_type;
    public $onlyUser=false;
    public $resize = true;
    public $ratio = false;
    public $hidden = '0';
    
    public function mediaSetting($guard='',$media_type='',$onlyUser=false,$resize=true,$ratio=false){

        if(!empty($guard)){
            $this->guard=$guard;
        }

        if(!empty($media_type)){
            $this->media_type=$media_type;
        }
        
        $this->onlyUser = $onlyUser;
        $this->resize=$resize;
        $this->ratio=$ratio;
    }

    protected function checkSetting(){
        
        $this->mediaSetting();

        if(!$this->guard){
            exit('用户类型配置异常');
        }
        $this->user = Auth::guard($this->guard)->user();
        
        if(!$this->user || !$this->guard){
            exit('配置错误');
        }

    }

    public function modal(Request $request,$media_type){

        $this->checkSetting();

        //图片排列

        $column = $request->get('column');
        $mediabox_width = $request->get('mediabox_width');

        
        
        $this->title('媒体管理');
        $page_no = 0;
        $page_size = 32;
        if($request->get('page')){
            $page_no = $request->get('page');
        }
        
        $filter = $request->all();

        $tag = false;
        
        if($request->has('tag_id') && $request->tag_id)
        {
            //标签信息
            $taxonomyObj = new Taxonomy;
            $tag = $taxonomyObj->where('taxonomy','media_tag')->where(['id'=>$request->tag_id])->with('term')->first();
            $filter['filter'] = true;
        }
        
        $datas = false;
        $this->_filter($filter,$page_size,$page_no,$datas);
        
        $datas['media_type'] = $media_type;


        $taxonomyObj = new Taxonomy;
        $tags = $taxonomyObj->lists('media_tag');

        $datas['tags'] = $tags;
        $datas['tag'] = $tag;

        $layout_site = suda_path('resources/views/site');
        View::addNamespace('view_app', $layout_site);

        return view('view_suda::site.component.modal.gallery')->with($datas);
    }


    protected function _filter($filter=[],$page_size=20,$page_no=0,&$data){
        
        $data = [];
        $objectModel = new Media;

        
        
        //只获取当前用户的图片
        if($this->onlyUser){
            $objectModel = new Media;
            $objectModel = $objectModel->where('user_type',$this->guard)->where('user_id',$this->user->id);
        }else{
            $objectModel = $objectModel->where([]);
        }
        
        
        
        if($filter && array_key_exists('filter',$filter) && $filter['filter']=='true'){
            
            $comma = '';
            
            if(array_key_exists('page',$filter)){
                unset($filter['page']);
            }
            if(array_key_exists('filter',$filter)){
                unset($filter['filter']);
            }
            if(array_key_exists('column',$filter)){
                unset($filter['column']);
            }
            if(array_key_exists('mediabox_width',$filter)){
                unset($filter['mediabox_width']);
            }
            if(array_key_exists('_',$filter)){
                unset($filter['_']);
            }
            
            foreach($filter as $k=>$v){
            
                //多选搜索
                if(is_string($v) && strpos($v,',')!==false){
                    $v = explode(',',$v);
                }
            
                if($k=='name'){
                    $objectModel = $objectModel->where('name','like',DB::raw("'%$v%'"));
                    $filter_arr[$k] = $v;
                    
                }elseif($k=='tag_id'){

                    $tag_ids = [];
                    if(is_string($v) || is_int($v)){
                        $tag_ids[] = $v;
                    }
                    $objectModel = $objectModel->whereHas('tags',function($query) use ($tag_ids){
                        $query->whereIn('taxonomy_id',$tag_ids);
                    });
                    
                }else{
                    if(is_string($v)){
                        $objectModel = $objectModel->where([$k=>$v]);
                        $filter_arr[$k] = $v;
                    }
            
                    if(is_array($v)){
                        $objectModel = $objectModel->whereIn($k,$v);
                        $filter_arr[$k] = implode(',',$v);
                    }
                }
                
                
            }

            
            
            $medias = $objectModel->where(['hidden'=>"0"])->orderBy('id','desc')->paginate($page_size,['*'],'page',$page_no);
            
            $data['medias'] = $medias;

            $filter_arr['filter'] = 'true';
            
            $data['filter_arr'] = $filter_arr;
            $comma = '';
            $filter_str='';
            foreach($filter_arr as $k=>$v){
                $filter_str .= $comma.$k.'='.$v;
                $comma = '&';
            }
            if($filter_str){
                $filter_str .= '&filter=true';
                $data['filter_str'] = $filter_str;
            }
        }else{
            
            $data['filter_arr'] = [];
            
            $medias = $objectModel->where(['hidden'=>"0"])->orderBy('id','desc')->paginate($page_size,['*'],'page',$page_no);

            $data['medias'] = $medias;
        }
        
    }


    public function uploadImage(Request $request,$media_type="default")
    {
        $this->checkSetting();

        $imageController = new ImageController;
        $validate = $imageController->validateUpload($request,$msg);
        
        if (!$validate) {
            
            //指定的input[file] name='img'
            $msgtxt = '异常上传出错，请稍后重试';
            if(is_string($msg)){
                $msgtxt = $msg;
            }else{
                if($msg->hasAny('media_type')){
                    $msgtxt = $msg->first('media_type');
                }
                if($msg->hasAny('img')){
                    $msgtxt = $msg->first('img');
                }
            }
            
            return Response::json([
                'error' => $msgtxt
            ], 200);
            
        } else {
            //发起的上传位置
            if($request->media_type){
                $media_type = $request->media_type;
            }
            $media_crop = $request->media_crop;
            $media_name = $request->media_name;
            if(!$media_name){
                $media_name = 'default';
            }
            
            $save_data = [
                'user_type'=>$this->guard,
                'user_id'=>$this->user->id,
                'media_type'=>$media_type,
                'media_crop'=>$media_crop,
                'resize'=>$this->resize, //生成缩略图
                'ratio'=>$this->ratio, //是否缩放
                'hidden'=>$this->hidden,//是否从选图中隐藏
            ];
            
            $msg = '';
            if($imageController->_file_data['file_type']=='image'){
                $medias = $imageController->saveImage($save_data,$msg);
            }else{
                $medias = $imageController->saveFile($save_data,$msg);
            }
            
            
            if($medias){
                
                list($media_id,$media_path) = $medias;
                
                $media = Media::where('id',$media_id)->first();
                
                if($media){
                    return Response::json([
                        'image' => suda_media($media,['size'=>'medium','imageClass'=>'image_show']),
                        'url' => suda_media($media,['size'=>'medium','imageClass'=>'image_show','url'=>true]),
                        'media_id' => $media_id,
                        'name'=> $media->name,
                        'media_path' => $media_path,
                        'media_name'   => $media_name=='default'?$media_id:$media_name
                    ], 200);
                }
                
            }else{
                $msgtxt = $msg?$msg:'文件上传失败，请稍后重试';
                return Response::json([
                    'error' => $msgtxt
                ], 200);
            }
        }
    }


    public function toUpload(Request $request,$media_type="default")
    {
        $this->checkSetting();

        $media_handler = new MediaController;
        $result = $media_handler->doUpload($request,$this->user,['user_type'=>$this->guard]);
        return $result;
        
    }


    public function showMedia(Request $request,$id)
    {
        //NOTHING TO DO
        return Response::json(['msg'=>'ok'], 200);
    }


    public function removeImage(Request $request,$media_type="default")
    {
        $this->checkSetting();

        //$media_type 参数暂时没用
        $media_handler = new MediaController;
        if(!intval($request->media_id)){
            return $media_handler->response('fail','文件不存在');
        }
        
        //删除图片
        $media_id = intval($request->media_id);
        return $media_handler->doRemove($media_id);
    }
}