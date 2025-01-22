<?php
/**
 * MediasController.php
 * 适用于后台的媒体资源管理
 * date 2017-11-01 09:59:37
 * author suda <dev@panel.cc>
 * @copyright Suda. All Rights Reserved.
 */
 


namespace Gtd\Suda\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;
use Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;

use Gtd\Suda\Services\ImageService;
use Gtd\Suda\Services\MediaService;

use Gtd\Suda\Models\Media;
use Gtd\Suda\Models\Mediatable;
use Gtd\Suda\Models\Setting;
use Gtd\Suda\Models\Taxonomy;

use Gtd\Suda\Traits\SettingTrait;

class MediaController extends DashboardController
{
    use SettingTrait;

    public $view_in_suda = true;
    
    protected $hidden = [
        'Gtd\Suda\Models\Operate'
    ];
    
    protected $mediaHandler;
    
    function __construct(){
        
        $this->mediaHandler = new MediaService;
        
        parent::__construct();
    }

    public function getAll(Request $request,$view='list')
    {

        $this->gate('media',app(Setting::class));
        
        $this->title('媒体管理');
        
        $page_no = 0;
        $page_size=60;
        if($request->get('page')){
            $page_no = $request->get('page');
        }
        
        $filter = $request->all();
        if($request->has('tag_id') && $request->tag_id)
        {
            //标签信息
            $taxonomyObj = new Taxonomy;
            $tag = $taxonomyObj->where('taxonomy','media_tag')->where(['id'=>$request->tag_id])->with('term')->first();
            $filter['filter'] = true;

            $this->setData('tag',$tag);
        }
        
        
        $data = false;
        $this->_filter('',$filter,$page_size,$page_no,$data);
        
        $this->setData('medias',$data);
        $this->setData('media_tab','all');
        $this->setMenu('media');
        
        return $this->display('media.'.$view);
        
    }

    public function getHiddens(Request $request){
        
        $this->title('媒体管理');
        
        $page_no = 0;
        $page_size=60;
        if($request->get('page')){
            $page_no = $request->get('page');
        }
        
        $filter = $request->all();
        if($request->has('tag_id') && $request->tag_id)
        {
            //标签信息
            $taxonomyObj = new Taxonomy;
            $tag = $taxonomyObj->where('taxonomy','media_tag')->where(['id'=>$request->tag_id])->with('term')->first();
            $filter['filter'] = true;

            $this->setData('tag',$tag);
        }
        
        
        $data = false;
        $filter['filter'] = true;
        $filter['hidden'] = '1';
        $this->_filter('',$filter,$page_size,$page_no,$data);
        
        $this->setData('medias',$data);
        $this->setData('media_tab','hidden');
        $this->setData('media_tab','hidden');
        $this->setMenu('media');
        
        return $this->display('media.list');
        
    }
    
    public function images(Request $request,$view='list'){
        
        $this->title('媒体管理');
        
        $page_no = 0;
        $page_size=60;
        if($request->get('page')){
            $page_no = $request->get('page');
        }
        
        $filter = $request->all();
        
        
        $data = false;
        $this->_filter('images',$filter,$page_size,$page_no,$data);
        
        $this->setData('medias',$data);
        $this->setData('media_tab','image');
        $this->setMenu('media');
        
        return $this->display('media.'.$view);
        
    }

    public function files(Request $request,$view='list'){
        
        $this->title('媒体管理');
        
        $page_no = 0;
        $page_size=60;
        if($request->get('page')){
            $page_no = $request->get('page');
        }
        
        $filter = $request->all();
        
        $data = false;
        $this->_filter('files',$filter,$page_size,$page_no,$data);
        
        $this->setData('medias',$data);
        $this->setData('media_tab','file');
        
        $this->setMenu('media');
        
        return $this->display('media.list');
        
    }

    
    public function editMedia(Request $request,$id){
        
        $this->title('媒体管理');
        $this->setMenu('media');
        $this->setData('modal_title',__('suda_lang::press.edit'));
        $this->setData('modal_icon_class','ion-image-outline');
        
        $media = Media::where('id',$id)->first();
        if(!$media){
            return $this->responseAjax('404','媒体文件不存在');
        }
        
        $this->setData('media',$media);

        //选中的关键词
        $tags = $media->getTerms('media_tag');
        $this->setData('tags',$tags);
        
        return $this->display('media.edit');
        
    }
    
    public function updateMedia(Request $request){
        
        
        $media = Media::where('id',$request->id)->first();
        if(!$media){
            return $this->responseAjax('404','媒体文件不存在');
        }
        if(!$request->name || empty($request->name)){
            return $this->responseAjax('404','请填写媒体名称');
        }
        
        Media::where('id',$request->id)->update([
            'name'=>$request->name
        ]);

        //保存关键字
        $media->removeAllTerms('media_tag');
        if($request->keyword && !empty($request->keyword)){
            $keywords = $request->keyword;
            $media->addTerm($keywords,'media_tag');
        }

        return $this->responseAjax('success','完成保存','self.refresh');
    }

    //重新生成图片
    public function rebuildMedia(Request $request){
        
        
        $media = Media::where('id',$request->id)->first();
        if(!$media){
            return $this->responseAjax('404','媒体文件不存在');
        }
        
        //rebuild
        $imgObj = new ImageService;
        $msg = '';
        $result = $imgObj->rebuildImage($media->id,$msg);

        if(!$result){
            return $this->responseAjax('fail',$msg);    
        }

        return $this->responseAjax('success','生成完毕','self.refresh');

    }
    
    public function deleteMedia(Request $request,$id){
        
        
        $media = Media::where('id',$request->id)->first();
        if(!$media || $id!=$request->id){
            return $this->responseAjax('404','媒体文件不存在');
        }
        //删除图片
        $media_id = intval($request->id);
        $this->mediaHandler->doRemove($media_id);
        
        return $this->responseAjax('success','文件已删除');
    }

    //批量打标签
    public function batchTag(Request $request)
    {
        $this->setMenu('media');
        $this->setData('modal_title',__('suda_lang::press.tags.tag'));
        $this->setData('modal_icon_class','ion-image-outline');
        
        $mediaObj = new Media;

        //选中的关键词
        $tags = $mediaObj->getTerms('media_tag');
        $this->setData('tags',$tags);
        
        
        return $this->display('media.batch_tag');   
        
    }

    //保存批量标签
    public function batchTagSave(Request $request)
    {
        if(!$request->has('formids') || !$request->formids)
        {
            return $this->responseAjax('404','请重新选择要操作的图片');
        }

        $formids = $request->formids;
        $formids_arr = explode(',',$formids);

        //保存关键字
        if($formids_arr && $request->keyword && !empty($request->keyword))
        {
            foreach($formids_arr as $id)
            {
                $media = Media::where(['id'=>$id])->first();
                if($media)
                {
                    $media->removeAllTerms('media_tag');
                    $keywords = $request->keyword;
                    $media->addTerm($keywords,'media_tag');
                }
            }
        }
        
        return $this->responseAjax('success','设置成功','self.refresh');
        
    }

    //批量显示图片
    public function showBatchMedia(Request $request)
    {
        
        if(!$request->medias){
            return $this->responseAjax('404','请选择要删除的媒体文件');
        }

        $medias = explode(',',$request->medias);
        if(count($medias)>0)
        {
            foreach($medias as $id)
            {
                //删除图片
                $this->mediaHandler->doHidden(intval($id),'0');
            }
        }
        
        return $this->responseAjax('success','已更新');
    }

    //批量隐藏图片
    public function hiddenBatchMedia(Request $request)
    {
        
        // return $this->responseAjax('404','请选择要隐藏的媒体文件');

        if(!$request->medias){
            return $this->responseAjax('404','请选择要隐藏的媒体文件');
        }
        
        $medias = explode(',',$request->medias);
        if(count($medias)>0)
        {
            foreach($medias as $id)
            {
                //删除图片
                $this->mediaHandler->doHidden(intval($id),'1');
            }
        }
        
        return $this->responseAjax('success','已更新');
    }

    //批量删除图片
    public function deleteBatchMedia(Request $request)
    {
        
        if(!$request->medias){
            return $this->responseAjax('404','请选择要删除的媒体文件');
        }

        $medias = explode(',',$request->medias);
        if(count($medias)>0)
        {
            foreach($medias as $id)
            {
                //删除图片
                $this->mediaHandler->doRemove(intval($id));
            }
        }
        
        return $this->responseAjax('success','文件已删除');
    }
    
    
    public function _filter($media_t='',$filter=[],$page_size=20,$page_no=0,&$data){
        
        $objectModel = new Media;

        if(!isset($filter['hidden']))
        {
            $filter['filter']='true';
            $filter['hidden']='0';
        }
        
        if($filter && array_key_exists('filter',$filter) && $filter['filter']=='true'){
            
            $comma = '';
            
            if(array_key_exists('page',$filter)){
                unset($filter['page']);
            }
            if(array_key_exists('filter',$filter)){
                unset($filter['filter']);
            }
            
            $objectModel = $objectModel->where([]);
            
            foreach($filter as $k=>$v){
            
                //多选搜索
                if(is_string($v) && strpos($v,',')!=false){
                    $v = explode(',',$v);
                }
            
                if($k=='name'){
                    $objectModel->where('name','like',DB::raw("'%$v%'"));
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

            if($media_t=='images'){
                $objectModel = $objectModel->where('type','NOT LIKE',DB::raw("'FILE%'"));
            }elseif($media_t=='files'){
                $objectModel = $objectModel->where('type','LIKE',DB::raw("'FILE%'"));
            }
            
            $data = $objectModel->with('tags')->orderBy('id','desc')->paginate($page_size,['*'],'page',$page_no);
            
            $filter_arr['filter'] = 'true';
            $this->setData('filter_arr', $filter_arr);
            $comma = '';
            $filter_str='';
            foreach($filter_arr as $k=>$v){
                $filter_str .= $comma.$k.'='.$v;
                $comma = '&';
            }
            if($filter_str){
                $filter_str .= '&filter=true';
                $this->setData('filter_str',$filter_str);
            }
        }else{
            $this->setData('filter_arr',[]);

            if($media_t=='images'){
                $objectModel = $objectModel->where('type','NOT LIKE',DB::raw("'FILE%'"));
            }elseif($media_t=='files'){
                $objectModel = $objectModel->where('type','LIKE',DB::raw("'FILE%'"));
            }
            
            $data = $objectModel->with('tags')->orderBy('id','desc')->paginate($page_size,['*'],'page',$page_no);
        }
        
    }


    public function setting(Request $request)
    {

        $this->setData('modal_title','媒体设置');
        $this->setData('modal_icon_class','ion-image-outline');

        $setting = $this->getSettingByKey('media_setting','media');

        if($setting){
            
            if(isset($setting['default']) && !empty($setting['default'])){
                $media = Media::where('id',$setting['default'])->first();
                $this->setData('media',$media);
            }
        }else{
            $setting = [
                'size'=>[
                    'medium'=>[
                        'width'=>config('sudaconf.media.size.medium',400),
                        'height'=>config('sudaconf.media.size.medium',400),
                    ],
                    'small'=>[
                        'width'=>config('sudaconf.media.size.small',200),
                        'height'=>config('sudaconf.media.size.small',200),
                    ],
                ],
            ];
        }

        $this->setData('setting',$setting);

        return $this->display('media.setting');

    }

    public function settingSave(Request $request)
    {

        $medium = array_merge(['width'=>config('sudaconf.media.size.medium',400),'height'=>config('sudaconf.media.size.medium',400)],$request->medium);
        $small = array_merge(['width'=>config('sudaconf.media.size.small',200),'height'=>config('sudaconf.media.size.small',200)],$request->small);
        
        $default_image = 0;
        if($request->images){
            $images = $request->images;
            $default_image = Arr::first($images);
        }
        
        $medium['width'] = intval($medium['width']);
        $medium['height'] = intval($medium['height']);
        $small['width'] = intval($small['width']);
        $small['height'] = intval($small['height']);

        $save = [
            'size'=>[
                'medium'=>$medium,
                'small'=>$small,
            ],
            'default'=>$default_image,
            'crop'=>$request->crop?1:0,
        ];


        $this->saveSettingByKey('media_setting','media',$save,'serialize');

        return $this->responseAjax('success',__('suda_lang::press.msg.success'),'media');

    }
}
