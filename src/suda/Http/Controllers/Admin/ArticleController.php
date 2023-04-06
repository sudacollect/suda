<?php

namespace Gtd\Suda\Http\Controllers\Admin;


use Illuminate\Http\Request;
use \Illuminate\Contracts\Routing\UrlGenerator;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;
use Gtd\Suda\Models\Media;
use Gtd\Suda\Models\Article;

use Gtd\Suda\Models\Taxonomy;
use Gtd\Suda\Models\Term;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class ArticleController extends DashboardController
{
    public $view_in_suda = true;
    
    public $table = 'articles';
    public $self_url = 'articles/gallery';
    protected $by_sort = 'id';
    
    
    public function index(Request $request,$view='list',$sort='id')
    {
        
        $this->gate('article.article_list',app(Article::class));

        $this->breadParent(__('suda_lang::press.menu_items.dashboard'),'/');
        $this->breadSet(__('suda_lang::press.menu_items.article'),'');

        $this->title(__('suda_lang::press.menu_items.article'));
        $this->setMenu('article','article_list');
        
        return $this->getAll($request,$view,$sort);
    }
    
    public function create()
    {
        $this->gate('article.article_new',app(Article::class));
                
        $this->title(__('suda_lang::press.menu_items.article_new'));

        $this->breadParent(__('suda_lang::press.menu_items.dashboard'),'/');
        $this->breadParent(__('suda_lang::press.menu_items.article'),'articles');
        $this->breadSet(__('suda_lang::press.menu_items.article_new'),'');
        
        // x-suda::select-category
        
        $this->setData('editor_height',500);
        $this->setMenu('article','article_new');
        return $this->display('article.add');
    }
    
    public function update($id=0){
        
        $id = intval($id);
        
        $article = Article::where('id','=',$id)->with('categories')->with('heroimage')->first();
        
        if(!$article){
            return $this->dispatchError(404);
        }
        
        $hero_images = [];
        
        if($article->heroimage && $article->heroimage->media){
            $hero_images['heroimage'] = [
                'image'=>suda_image($article->heroimage->media,['size'=>'medium','imageClass'=>'image_show',"url"=>true]),
                'media_id'=>$article->heroimage->media_id,
            ];
        }
        
        $this->setData('hero_images',$hero_images);
        
        $this->setData('item',$article);
        
        // x-suda::select-category
        
        //选中的分类
        $cates = $article->getTerms('post_category');
        $select_cates = [];
        if($cates){
            foreach($cates as $cate){
                $select_cates[] = $cate->taxonomies[0]->id;
            }
        }
        $this->setData('cates',$select_cates);
        
        //选中的关键词
        $tags = $article->getTerms('post_tag');
        if($tags->count()<1){
            //取最新的5个tag
            $tags = Term::where('taxonomy','post_tag')->limit(10)->get();
        }
        $this->setData('tags',$tags);
        
        $this->setData('editor_height',500);
        
        $this->title(__('suda_lang::press.menu_items.article_update'));
        $this->breadParent(__('suda_lang::press.menu_items.dashboard'),'/');
        $this->breadParent(__('suda_lang::press.menu_items.article'),'articles');
        $this->breadSet(__('suda_lang::press.menu_items.article_update'),'');

        $this->setMenu('article','article_list');
        return $this->display('article.edit');
    }
    
    public function save(Request $request){
        
        if(!$this->user){
            return redirect('home');
        }
        
        $id = $request->id;
        
        $roles = [
            'title' => 'required|min:2|max:200',
            'category'=>'required',
            'content'=>'required',
        ];
        $messages = [
            'title.required'=>'请填写文章标题',
            'category.required'=>'请至少选择一个文章分类',
            'content.required'=>'请填写文章内容',
        ];
        
        //#TODO 增加配置项，可以设定是否必须传标题图

        if($request->images){
            
        }else{
            $roles['images'] = 'required';
            $messages['images.required'] = '请上传标题图';
        }
        
        $response_msg = '';
        $ajax_result = $this->ajaxValidator($request->all(),$roles,$messages,$response_msg);
        
        $objectModel = new Article;
        
        if(!$response_msg){
            
            //名称
            $objectModel->title = $request->title;
            $objectModel->content = $request->content;
            $objectModel->operate_id = $this->user->id;
            $objectModel->display_operate_id = $this->user->id;
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
                $article = Article::where('id',$id)->first();
                $article->where('id',$id)->update([
                    'display_operate_id'=> $this->user->id,
                    'title'             => $request->title,
                    'content'           => $request->content,
                    'disable'           => $request->disable,
                    'stick_top'         => $request->stick_top,
                    'published_at'         => $objectModel->published_at,
                    'slug'           => $request->slug,
                    'redirect_url'           => $request->redirect_url
                ]);
            }else{
                $objectModel->save();
                $article = Article::where('id',$objectModel->id)->first();
            }
            
            
            $item_id = $article->id;
            
            //保存文章分类
            $article->removeAllTerms('post_category');
            if($request->category && !empty($request->category)){
                $cate_ids = $request->category;
                //处理文章分类，确保不新增分类
                foreach($cate_ids as $cate_id){
                    $cate = Taxonomy::where('id',$cate_id)->with('term')->first();
                    if($cate){
                        $article->addTerm($cate->term->name,'post_category',$cate->parent,$cate->sort);
                    }
                }
            }
            
            //保存关键字
            $article->removeAllTerms('post_tag');
            if($request->keyword && !empty($request->keyword)){
                $keywords = $request->keyword;
                $article->addTerm($keywords,'post_tag');
            }
            
            if($item_id && $request->images){
                //存储图片
                $mediaModel = new Media;
                
                //#1 判断之前的已经存储的图片，不要删除
                $item = Article::where('id',$article->id)->with('heroimage')->first();
                
                if($item->heroimage && !in_array($item->heroimage->media_id,$request->images)){
                    
                    $item->removeMediatable($item->heroimage->media_id,'hero_image');
                }
                
                //#2 保存新的图片
                foreach($request->images as $k=>$media_id){
                    $article->createMediatables($media_id,'hero_image');
                    $article->update(['hero_image'=>$media_id]);
                }
            }
            
            //更新成功
            return $this->responseAjax('success','保存成功',$this->self_url);
        }
        
        
        
        $url = admin_url('articles/gallery');
        return $this->responseAjax('fail',$response_msg,$url);
        
    }
    
    public function delete(Request $request,$id){
        
        if($request->id && !empty($request->id) && intval($id)==$request->id){
            $item = Article::where('id',$request->id)->first();
            if($item){
                Article::where('id',$request->id)->delete();
                $url = '';
                return $this->responseAjax('success','删除成功',$this->self_url);
                
            }else{
                return $this->responseAjax('error','数据不存在,请重试',$this->self_url);
            }
        }else{
            return $this->responseAjax('error','数据不存在,请重试',$this->self_url);
        }
        
    }

    public function search(Request $request)
    {
        if(!$request->has('search')){

            return redirect(admin_url('article'));

        }
        $search = $request->search;
        if($search){
            $search['search'] = 1;
        }
        $values = http_build_query($search, null, '&', PHP_QUERY_RFC3986);

        
        $view = $request->view_type=='list_gallery'?'gallery':'list';

        if($values){
            $url = admin_url('articles/'.$view.'?'.$values);
        }else{
            $url = admin_url('articles/'.$view);
        }
        return redirect($url);
    }

    public function getAll(Request $request,$view='list',$sort='id')
    {
        
        $this->by_sort = $sort;
        
        $data = false;

        $page_no = $request->page?$request->page:1;
        
        $filter = $this->parseFilter($request->all());

        if($view=='deleted')
        {
            $filter['deleted'] = 1;
            $view = 'list';
            $this->setData('article_only_deleted',true);
        }

        $this->_filter($filter,$page_no,$data);
        
        $this->setData('data_list',$data);
        $this->setData('by_sort',$sort);

        $this->setData('view_type',$view=='gallery'?'list_gallery':'list_default');
        
        
        $this->makeSearchFilter();

        return $this->display('article.list');

    }

    public function processFilter(Request $request)
    {
        if(!$request->filter){
            return $this->responseAjax('error','查询异常,请重新尝试.');
        }

        $data = false;

        $page_no = $request->page?$request->page:1;

        $filter = $this->parseFilter($request->all());

        $view_type = 'list_content';
        if(isset($filter['view_type']))
        {
            if($filter['view_type']=='list_gallery')
            {
                $view_type = 'list_gallery_content';
            }
            unset($filter['view_type']);
        }

        $this->_filter($filter,$page_no,$data);
        
        $this->setData('data_list',$data);


        $this->setData('process_filter',1);
        
        return $this->display('article.'.$view_type)->render();
        
    }

    protected function parseFilter($filter)
    {
        if(isset($filter['page']))
        {
            unset($filter['page']);
        }
        if(isset($filter['_token']))
        {
            unset($filter['_token']);
        }
        if(isset($filter['search']))
        {
            unset($filter['search']);
            if(isset($filter['filter']))
            {
                $filter_s = [];
                parse_str($filter['filter'],$filter_s);
                unset($filter['filter']);
                $filter = array_merge($filter,$filter_s);
            }
            
            return $filter;
        }
        
        
        return [];
    }
    
    public function _filter($filter,$page_no = 1,&$data){
        
        $objectModel = new Article;


        //分页处理
        $page_no = $page_no;
        $page_size = 20;
        
        if(count($filter) > 0){
            
            //更新日期的方法,大于/等于/小于
            $updated_at_method = false;
            if(array_key_exists('updated_at_method',$filter)){
                $updated_at_method = $filter['updated_at_method'];
                unset($filter['updated_at_method']);
            }
            
            foreach($filter as $k=>$v){
            
                //多选搜索
                if(is_string($v) && strpos($v,',')!=false){
                    $v = explode(',',$v);
                }

                switch($k){

                    case 'title':
                    if(!empty($v)){
                        $objectModel = $objectModel->where('title','like',DB::raw("'%$v%'"));
                    }
                    

                    break;

                    case 'category':
                    
                    $category_ids = [];
                    if(is_string($v) && !empty($v)){
                        $category_ids[] = $v;
                    }
                    if(count($category_ids)>0){
                        $objectModel = $objectModel->whereHas('categories',function($query) use ($category_ids){
                            $query->whereIn('taxonomy_id',$category_ids);
                        });
                    }

                    break;

                    case 'date':
                    if(!empty($v)){
                        $start_date = Carbon::parse($v)->startOfMonth();
                        $end_date = Carbon::parse($v)->endOfMonth();

                        $objectModel = $objectModel->whereBetween('updated_at',[$start_date,$end_date]);
                    }
                    break;

                    //发布者
                    case 'author':
                    
                    if(!empty($v)){
                        $objectModel = $objectModel->whereHas('operate',function($query) use ($v){
                            $query->where('username','like',DB::raw("'%$v%'"));
                        });
                    }

                    break;

                    //更新时间
                    case 'updated_at':
                    $methods = [
                        'equal'=>'=',
                        'less'=>'<=',
                        'than'=>'>=',
                    ];
                    if(!empty($v) && array_key_exists($updated_at_method,$methods)){
                        $objectModel = $objectModel->where('updated_at',"$methods[$updated_at_method]",$v);
                    }
                    

                    break;

                    //是否置顶
                    case 'stick_top':
                        
                    $objectModel = $objectModel->where('stick_top',$v);

                    break;

                    //是否发布
                    case 'disable':
                    
                    $objectModel = $objectModel->where('disable',$v);

                    break;

                    //是否删除
                    case 'deleted':
                    
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

        $objectModel = $objectModel->orderBy('stick_top','desc');
        
        if($this->by_sort=='sort'){
            $objectModel = $objectModel->orderBy('sort','asc');
        }

        $data = $objectModel->with('operate')->with('categories')->with('heroimage')->orderBy('id','desc')->paginate($page_size,['*'],'page',$page_no);
        
    }


    //修改排序
    public function editSort(Request $request){
        
        if(!intval($request->inedit_id)){
            return $this->responseAjax('error','请求异常，请重试');
        }
        
        $sort = intval($request->inedit_value);
        
        $cate = Article::where(['id'=>$request->inedit_id])->first();
        
        if(!$cate){
            return $this->responseAjax('error','数据不存在，请重新检查');
        }
        
        Article::where(['id'=>$request->inedit_id])->update([
            'sort'=>$sort
        ]);
        
        return $this->responseAjax('success','排序已保存');
    }

    public function restoreItem(Request $request,$id)
    {
        
        if($request->id && !empty($request->id) && intval($id)==$request->id){
            $item = Article::withTrashed()->where('id',$request->id)->first();
            if($item){
                Article::withTrashed()->find($request->id)->restore();
                $url = 'articles/gallery';
                return $this->responseAjax('success','完成恢复',$url);
                
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
            $item = Article::withTrashed()->where('id',$request->id)->first();
            if($item){
                Article::withTrashed()->where('id',$request->id)->forceDelete();
                $url = '';
                return $this->responseAjax('success','删除成功',$this->self_url);
                
            }else{
                return $this->responseAjax('error','数据不存在,请重试',$this->self_url);
            }
        }else{
            return $this->responseAjax('error','数据不存在,请重试',$this->self_url);
        }
        
    }

    //预览文章
    public function preview(Request $request,$id)
    {

        $item = Article::where(['id'=>$request->id])->first();
        if(!$item)
        {
            return $this->responseAjax('error','数据不存在,请重试');
        }

        $str = md5($item->id.$item->created_at.time());
        
        Cache::put('article_preview_'.$item->id,$str,now()->addSeconds(3600));
        
        return redirect(url('article/'.$item->id.'/'.$str));
    }
    
    public function makeSearchFilter()
    {
        //搜索条件
        
        $dates = Article::select(DB::raw('YEAR(updated_at) as year'),DB::raw('MONTH(updated_at) as month'))->where([])->groupBy(DB::raw('YEAR(updated_at)'))->groupBy(DB::raw('MONTH(updated_at)'))->get();
        $this->setData('dates',$dates);

        $taxonomyObj = new Taxonomy;
        $catgories = $taxonomyObj->listAll('post_category');
        $this->setData('categories',$catgories);
    }
}
