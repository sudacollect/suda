<?php

namespace Gtd\Suda\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;
use Gtd\Suda\Models\Media;
use Gtd\Suda\Models\Page;


class PageController extends DashboardController
{
    public $view_in_suda = true;
    
    public $table = 'pages';
    public $self_url = 'page/list';
    protected $by_sort = 'id';
    
    public function index(Request $request,$view='list',$sort='id')
    {
        $this->gate('page.page_list',app(Page::class));

        $this->breadParent(__('suda_lang::press.menu_items.dashboard'),'/');
        $this->breadSet(__('suda_lang::press.menu_items.page'),'');

        $this->title(__('suda_lang::press.menu_items.page'));
        $this->setMenu('page','page_list');

        return $this->getAll($request,$view,$sort);
        
    }
    
    public function deletedList(Request $request,$view='list')
    {
        $this->title('页面管理 - 回收站');
        $page_no = 0;
        $page_size = 20;
        if($request->get('page')){
            $page_no = $request->get('page');
        }
        
        $data = false;
        $filter = $request->all();
        $filter['search'] = 'true';
        $filter['deleted_at'] = true;
        $this->_filter($filter,$data);
        
        $this->setData('data_count',Page::where([])->onlyTrashed()->count());
        $this->setData('data_list',$data);
        
        $this->setMenu('page','page_list');
        return $this->display('page.deleted_list');
    }
    
    public function create()
    {
        $this->breadParent(__('suda_lang::press.menu_items.dashboard'),'/');
        $this->breadSet(__('suda_lang::press.menu_items.page'),'page/list');
        $this->breadSet(__('suda_lang::press.menu_items.page_new'),'');

        $this->gate('page.page_new',app(Page::class));
                
        $this->title(__('suda_lang::press.menu_items.page_new'));
        
        $this->setMenu('page','page_new');
        return $this->display('page.add');
    }
    
    public function update($id=0)
    {
        
        $this->breadParent(__('suda_lang::press.menu_items.dashboard'),'/');
        $this->breadSet(__('suda_lang::press.menu_items.page'),'page/list');
        $this->breadSet(__('suda_lang::press.btn.edit'),'');
        
        $id = intval($id);
        
        $page = Page::where('id','=',$id)->with('heroimage')->first();
        
        if(!$page){
            return $this->dispatchError(404);
        }
        
        $hero_images = [];
        
        if($page->heroimage && $page->heroimage->media){
            $hero_images['heroimage'] = [
                'image'=>suda_image($page->heroimage->media,['size'=>'medium','imageClass'=>'image_show',"url"=>true]),
                'media_id'=>$page->heroimage->media_id,
            ];
        }
        
        
        $this->setData('hero_images',$hero_images);
        
        $this->setData('page',$page);
        
        $tags = $page->getTerms('post_tag');
        
        $this->setData('tags',$tags);
        
        $this->title('编辑页面');
        
        
        $this->setMenu('page','page_list');
        return $this->display('page.edit');
    }
    
    public function save(Request $request)
    {
        
        if(!$this->user){
            return redirect('home');
        }
        
        
        
        $id = $request->id;
        
        $roles=[];
        
        if($id){
            
            $roles = [
                'title' => 'required|min:2|max:64|unique:pages,title,'.$id,
            ];
            $messages = [
                'title.required'=>'请填写名称',
                'title.unique'=>'标题重复',
            ];
            
        }else{
            
            $roles = [
                'title' => 'required|min:2|max:64|unique:pages',
            ];
            $messages = [
                'title.required'=>'请填写名称',
                'title.unique'=>'标题重复',
            ];
        }
        
        if($request->images){

        }else{
            $roles['images'] = 'required';
            $messages['images.required'] = '请上传标题图片';
        }
        
        $response_msg = '';
        $ajax_result = $this->ajaxValidator($request->all(),$roles,$messages,$response_msg);
        
        
        $objectModel = new Page;
        
        $update_object = false;
        
        if($id && !$response_msg){
            
            $object_exist=false;
            
            if($object_exist){
                $roles = [
                    'title' => 'unique:page'
                ];
                $messages = ['title.unique'=>'名称不能重复，请重新填写'];
                $ajax_result = $this->ajaxValidator($request->all(),$roles,$messages,$response_msg);
                
            }else{
                
                $update_object = true;
                $objectModel->id = $id;
            }
            
            
        }
        
        
        if(!$response_msg){
            
            //名称
            $objectModel->title = $request->title;

            $objectModel->content = $request->content;
            $objectModel->operate_id = $this->user->id;
            $objectModel->disable = $request->disable;
            $objectModel->stick_top = $request->stick_top;

            $objectModel->slug = $request->slug;
            $objectModel->redirect_url = $request->redirect_url;

            if($request->published_at)
            {
                $objectModel->published_at = $request->published_at;
            }else{
                $objectModel->published_at = date('Y-m-d H:i:s');
            }
            
            if($id){
                
                $objectModel->where('id',$id)->update([
                    'title'=>$request->title,
                    'content'=>$request->content,
                    'slug'=>$request->slug,
                    'redirect_url'=>$request->redirect_url,
                    'disable'=>$request->disable,
                    'published_at'=>$objectModel->published_at,
                    'stick_top'=>$request->stick_top
                ]);
                $page = Page::where('id',$id)->first();
            }else{
                $objectModel->save();
                $page = Page::where('id',$objectModel->id)->first();
            }
            
            
            $item_id = $page->id;
        
            if($item_id && $request->images){
                //存储图片
                $mediaModel = new Media;
            
                //取消之前的
                if($update_object){
                
                    //#1 判断之前的已经存储的图片，不要删除
                    $item = $page->with('heroimage')->first();
                    
                    if($item->heroimage && !in_array($item->heroimage->media_id,$request->images)){
                        $page->removeMediatable($item->heroimage->media_id,'hero_image');
                    }
                    
                    //#2 保存新的图片
                }
            
                foreach($request->images as $k=>$media_id){
                    $page->createMediatables($media_id,'hero_image');
                    Page::where('id',$page->id)->update(['hero_image'=>$media_id]);
                }
                
                
                //保存关键字
                $page->removeAllTerms('post_tag');
                if($request->keyword && !empty($request->keyword)){
                    $keywords = $request->keyword;
                    foreach($keywords as $keyword){
                        if(!$page->hasTerm($keyword,'post_tag')){
                            $page->addTerm($keyword,'post_tag');
                        }
                    }

                }
            
            }
            
            $redirect_url = $request->previous_url?$request->previous_url:$this->self_url;
            return $this->responseAjax('success',__('suda_lang::press.msg.success'),$redirect_url);
        }
        
        
        
        // $url = admin_url('page/list');
        return $this->responseAjax('fail',$response_msg);
        
    }
    
    public function deletePage(Request $request,$id)
    {
        
        if($request->id && !empty($request->id) && intval($id)==$request->id){
            $item = Page::where('id',$request->id)->first();
            if($item){
                $page = Page::where('id',$request->id)->first();
                // $page->removeMediatable($page->hero_image,'hero_image');
                Page::where('id',$request->id)->delete();
                $url = '';
                return $this->responseAjax('success','删除成功',$this->self_url);
                
            }else{
                return $this->responseAjax('error','数据不存在,请重试',$this->self_url);
            }
        }else{
            return $this->responseAjax('error','数据不存在,请重试',$this->self_url);
        }
        
    }
    
    public function restorePage(Request $request,$id)
    {
        
        if($request->id && !empty($request->id) && intval($id)==$request->id){
            $item = Page::withTrashed()->where('id',$request->id)->first();
            if($item){
                Page::withTrashed()->find($request->id)->restore();
                $url = 'page/list';
                return $this->responseAjax('success','完成页面恢复',$url);
                
            }else{
                return $this->responseAjax('warning','数据不存在,请重试');
            }
        }else{
            return $this->responseAjax('warning','数据不存在,请重试');
        }
        
    }


    //强制删除
    public function forceDelete(Request $request,$id){
        
        if($request->id && !empty($request->id) && intval($id)==$request->id){
            $item = Page::withTrashed()->where('id',$request->id)->first();
            if($item){
                Page::withTrashed()->where('id',$request->id)->forceDelete();
                $url = '';
                return $this->responseAjax('success','删除成功',$this->self_url);
                
            }else{
                return $this->responseAjax('error','数据不存在,请重试',$this->self_url);
            }
        }else{
            return $this->responseAjax('error','数据不存在,请重试',$this->self_url);
        }
        
    }


    public function getAll(Request $request,$view='list',$sort='id')
    {
        
        $this->by_sort = $sort;
        
        $data = false;
        $filter = $request->all();
        $this->_filter($filter,$data);

        $this->setData('data_list',$data);
        $this->setData('by_sort',$sort);
        
        
        $this->makeSearchFilter();
        return $this->display('page.list');

    }
    
    
    public function modalBox(Request $request,$view='list',$name="")
    {
        $this->title('选择页面');
        
        $data = false;
        $filter = $request->all();
        $this->_filter($filter,$data);
        
        $this->setData('data_count',Page::where('deleted_at','=',null)->count());
        $this->setData('data_list',$data);
        
        $this->setData('result_name',$name);
        
        $this->setMenu('page','page_list');

        return $this->display('page.modalbox.list');
    }

    public function search(Request $request,$view='list',$sort='id')
    {
        if(!$request->has('search')){

            return redirect(admin_url('page/list'));

        }
        $search = $request->search;
        if($search){
            $search['search'] = 1;
        }
        $values = http_build_query($search, null, '&', PHP_QUERY_RFC3986);
        if($values){
            $url = admin_url('page?'.$values);
        }else{
            $url = admin_url('page');
        }
        return redirect($url);
    }
    
    
    public function _filter($filter,&$data)
    {
        
        $objectModel = new Page;

        //分页处理
        $page_no = 1;
        $page_size = 20;
        if(isset($filter['page'])){
            $page_no = $filter['page'];
        }
        
        if(isset($filter['search'])){
            
            $comma = '';

            unset($filter['_token']);
            unset($filter['page']);
            unset($filter['search']);
            
            foreach($filter as $k=>$v){
            
                //多选搜索
                if(is_string($v) && strpos($v,',')!=false){
                    $v = explode(',',$v);
                }

                switch($k){

                    case 'title':
                    $objectModel = $objectModel->where('title','like',DB::raw("'%$v%'"));

                    break;

                    case 'category':
                    $category_ids = [];
                    if(is_string($v)){
                        $category_ids[] = $v;
                    }
                    $objectModel = $objectModel->whereHas('categories',function($query) use ($category_ids){
                        $query->whereIn('taxonomy_id',$category_ids);
                    });

                    break;

                    case 'date':
                    $start_date = Carbon::parse($v)->startOfMonth();
                    $end_date = Carbon::parse($v)->endOfMonth();

                    $objectModel = $objectModel->whereBetween('updated_at',[$start_date,$end_date]);

                    break;

                    case 'deleted_at':
                    $objectModel = $objectModel->onlyTrashed();

                    break;

                    default:

                    if(is_string($v)){
                        $objectModel = $objectModel->where([$k=>$v]);
                        
                    }
            
                    if(is_array($v)){
                        $objectModel = $objectModel->whereIn($k,$v);
                        
                    }

                    break;

                }
                
            }
            
            if(count($filter)>0){
                $filter['search'] = 1;
            }
            $this->setData('filter',$filter);
            
        }else{
            $this->setData('filter',[]);
            $objectModel = $objectModel->where([]);
        }

        if($this->by_sort=='sort'){
            $objectModel = $objectModel->orderBy('sort','asc');
        }
        
        $data = $objectModel->orderBy('stick_top','desc')->orderBy('id','desc')->with('operate')->with('heroimage')->paginate($page_size,['*'],'page',$page_no);
        
    }


    //修改排序
    public function editSort(Request $request){
        
        if(!intval($request->inedit_id)){
            return $this->responseAjax('error','请求异常，请重试');
        }
        
        $sort = intval($request->inedit_value);
        
        $cate = Page::where(['id'=>$request->inedit_id])->first();
        
        if(!$cate){
            return $this->responseAjax('error','数据不存在，请重新检查');
        }
        
        Page::where(['id'=>$request->inedit_id])->update([
            'sort'=>$sort
        ]);
        
        return $this->responseAjax('success','排序已保存');
    }


    //预览文章
    public function preview(Request $request,$id)
    {

        $item = Page::where(['id'=>$request->id])->first();
        if(!$item)
        {
            return $this->responseAjax('error','数据不存在,请重试');
        }

        $str = md5($item->id.$item->created_at.time());
        
        Cache::put('suda_page_preview_'.$item->id,$str,now()->addSeconds(3600));
        
        return redirect(url('page/'.$item->id.'/'.$str));
    }


    public function makeSearchFilter()
    {
        $dates = Page::select(DB::raw('YEAR(updated_at) as year'),DB::raw('MONTH(updated_at) as month'))
            ->where([])
            ->groupBy(DB::raw('YEAR(updated_at)'))
            ->groupBy(DB::raw('MONTH(updated_at)'))->get();

        $this->setData('dates',$dates);
    }
    
    
}
