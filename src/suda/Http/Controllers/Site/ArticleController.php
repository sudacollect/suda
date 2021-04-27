<?php

namespace Gtd\Suda\Http\Controllers\Site;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use Gtd\Suda\Http\Controllers\SiteController;
use Gtd\Suda\Models\Article;
use Gtd\Suda\Models\Taxonomy;

class ArticleController extends SiteController{
    
    public $view_in_suda = true;
    
    public function __construct()
    {
        parent::__construct();

        $this->setData('active_tab','articles');
    }
    
    public function index(Request $request,$page_id,$preview_str='')
    {

        //增加预览
        
        if($preview_str)
        {
            
            
            $saved_str = Cache::get('article_preview_'.$page_id,false);

            if($saved_str==$preview_str)
            {
                $article = Article::where('id','=',$page_id)->withTrashed()->with('heroimage')->first();
                $this->setData('article_preview',1);
            }

        }else{
            $article = Article::where('slug','=',$page_id)->where('disable',0)->with('heroimage')->first();
        }
        
        

        if(!$article){
            $article = Article::where('id','=',intval($page_id))->where('disable',0)->with('heroimage')->first();
        }
        
        
        
        if(!$article || !$page_id){
            return redirect('sdone/status/404');
        }

        $hero_image = [];

        if($article->heroimage && $article->heroimage->media){
            $hero_image = [
                'image'=>suda_image($article->heroimage->media,['size'=>'medium','imageClass'=>'image_show',"url"=>true]),
            ];
        }
        if($hero_image){
            $this->setData('hero_image',$hero_image);
        }
        
        
        $this->setData('article',$article);
        
        
        $tags = $article->getTerms('post_tag');
        
        if($tags){
            $tags_str = [];
            foreach($tags as $tag){
                
                $tags_str[] = $tag->name;
            }
            $tags_str = implode(',',$tags_str);
            $this->keywords($tags_str);
            
            $this->setData('tags',$tags);
        }
        
        $this->breadParent('文章','articles');

        $cates = '';
        $comma = '';
        foreach($article->categories as $cate){

            if($cate->taxonomy && $cate->taxonomy->term){
                $cates .= $comma.'<a href="'.url('category/'.$cate->taxonomy->term->slug).'">'.$cate->taxonomy->term->name.'</a>';
                $comma = ', ';
            }
            

        }
        $this->setData('cates',$article->categories);

        $this->title($article->title);
        
        return $this->display('article.single');
        
    }
    
    public function showAll(Request $request,$view='list')
    {

        $this->breadParent('所有文章','');

        $this->title('所有文章');
        $page_no = 1;
        $page_size = 20;
        if($request->get('page')){
            $page_no = $request->get('page');
        }
        
        
        $data = false;
        $filter = $request->all();

        $this->_filter($filter,$page_size,$page_no,$data);
        
        $this->setData('data_count',Article::where('deleted_at','=',null)->count());
        $this->setData('data_list',$data);

        
        if($page_no==1){
            //获取置顶的文章
            $sticks = Article::where('disable',0)->where('stick_top',1)->orderBy('sort','desc')->orderBy('id','desc')->with('heroimage')->with('categories')->paginate(3,['*'],'page',0);

            if($sticks->count()<1){
                $sticks = $data->slice(0,3);
            }
            
            $this->setData('sticks',$sticks);
        }
        
        //获取所有分类
        $taxonomyObj = new Taxonomy;
        $catgories = $taxonomyObj->where('parent',0)->where('taxonomy','post_category')->orderBy('sort','asc')->get();
        $this->setData('categories',$catgories);
        
        
        return $this->display('article.list');
    }


    //显示分类的内容
    public function showCategory(Request $request,$slug)
    {

        $this->breadParent('所有文章','');

        $articleObj = new Article;

        //分类信息
        $taxonomyObj = new Taxonomy;
        $category = $taxonomyObj->where('taxonomy','post_category')->whereHas('term',function($query) use ($slug){
            $query->where('slug',$slug);
        })->with('term')->first();

        if(!$category){
            return $this->errors('没有分类信息');
        }

        $this->setData('category',$category);
        $this->title($category->term->name);

        $page_no = 1;
        $page_size = 20;
        if($request->get('page')){
            $page_no = $request->get('page');
        }

        $data = false;
        $filter = $request->all();
        $filter['filter'] = 'true';
        $filter['category'] = $category->id;
        $this->_filter($filter,$page_size,$page_no,$data);
        
        $this->setData('data_count',Article::where('deleted_at','=',null)->count());
        $this->setData('data_list',$data);
        
        //获取所有分类
        
        $catgories = $taxonomyObj->where('parent',0)->where('taxonomy','post_category')->orderBy('sort','asc')->get();
        $this->setData('categories',$catgories);
        
        

        return $this->display('article.list');
    }


    //显示分类的内容
    public function showTag(Request $request,$tag_name)
    {

        $this->breadParent('所有文章','');

        $articleObj = new Article;

        //标签信息
        $taxonomyObj = new Taxonomy;
        $tag = $taxonomyObj->where('taxonomy','post_tag')->whereHas('term',function($query) use ($tag_name){
            $query->where('name',$tag_name);
        })->with('term')->first();

        if(!$tag){
            return $this->errors('没有标签信息');
        }

        $this->setData('tag',$tag);
        $this->title($tag->term->name);

        $page_no = 1;
        $page_size = 20;
        if($request->get('page')){
            $page_no = $request->get('page');
        }

        $data = false;
        $filter = $request->all();
        $filter['filter'] = 'true';
        $filter['tag'] = $tag->id;
        $this->_filter($filter,$page_size,$page_no,$data);
        
        $this->setData('data_count',Article::where('deleted_at','=',null)->count());
        $this->setData('data_list',$data);
        

        return $this->display('article.list');
    }
    
    public function _filter($filter=[],$page_size=20,$page_no=0,&$data){
        
        $objectModel = new Article;
        
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
            
                if($k=='title'){
                    $objectModel = $objectModel->where('title','like',DB::raw("'%$v%'"));
                    $filter_arr[$k] = $v;
                    
                }elseif($k=='deleted_at'){
                    
                    $objectModel = $objectModel->onlyTrashed();
                    $filter_arr[$k] = true;
                    
                }elseif($k=='category'){

                    $category_ids = [];
                    if(is_string($v) || is_int($v)){
                        $category_ids[] = $v;
                    }
                    $objectModel = $objectModel->whereHas('categories',function($query) use ($category_ids){
                        $query->whereIn('taxonomy_id',$category_ids);
                    });
                    
                }elseif($k=='tag'){

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
            
            
            $data = $objectModel->where('disable',0)->orderBy('sort','desc')->orderBy('id','desc')->with('heroimage')->with('categories')->paginate($page_size,['*'],'page',$page_no);
            
            $filter_arr['filter'] = 'true';
            $this->setData('filter_arr',$filter_arr);
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
            $objectModel->where([]);
            $data = $objectModel->where('disable',0)->orderBy('sort','desc')->orderBy('id','desc')->with('heroimage')->with('categories')->paginate($page_size,['*'],'page',$page_no);
        }
        
    }
}