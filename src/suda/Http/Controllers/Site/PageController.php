<?php

namespace Gtd\Suda\Http\Controllers\Site;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use Gtd\Suda\Http\Controllers\SiteController;
use Gtd\Suda\Models\Page;

class PageController extends SiteController{
    
    public $view_in_suda = true;
    
    public function __construct()
    {
        parent::__construct();

        $this->setData('active_tab','pages');
    }
    
    public function index(Request $request,$page_id,$preview_str=''){

        if($preview_str)
        {
            
            
            $saved_str = Cache::get('suda_page_preview_'.$page_id,false);

            if($saved_str==$preview_str)
            {
                $page = Page::where('id','=',$page_id)->withTrashed()->with('heroimage')->first();
                $this->setData('page_preview',1);
            }

        }else{
            $page = Page::where('slug','=',$page_id)->where('disable',0)->with('heroimage')->first();
        }

        

        if(!$page){
            $page = Page::where('id','=',intval($page_id))->where('disable',0)->with('heroimage')->first();
        }
        
        
        
        if(!$page || !$page_id){
            return redirect('sudarun/status/404');
        }

        $hero_image = [];

        if($page->heroimage && $page->heroimage->media){
            $hero_image = [
                'image'=>suda_image($page->heroimage->media,['size'=>'medium','imageClass'=>'image_show',"url"=>true]),
            ];
        }
        if($hero_image){
            $this->setData('hero_image',$hero_image);
        }
        
        
        $this->setData('page',$page);
        
        
        $tags = $page->getTerms('post_tag');
        
        if($tags){
            $tags_str = [];
            foreach($tags as $tag){
                
                $tags_str[] = $tag->name;
            }
            $tags_str = implode(',',$tags_str);
            $this->keywords($tags_str);
            
            $this->setData('tags',$tags);
        }
        
        
        
        $this->title($page->title);
        
        return $this->display('page.single');
        
    }
    
    public function showAll(Request $request,$view='list')
    {
        $this->title('所有页面');
        $page_no = 0;
        $page_size = 20;
        if($request->get('page')){
            $page_no = $request->get('page');
        }
        
        
        $data = false;
        $this->_filter($request->all(),$page_size,$page_no,$data);
        
        $this->setData('data_count',Page::where('deleted_at','=',null)->count());
        $this->setData('data_list',$data);
        
        return $this->display('page.list');
    }
    
    
    public function _filter($filter=[],$page_size=20,$page_no=0,&$data){
        
        $objectModel = new Page;
        
        if($filter && array_key_exists('filter',$filter) && $filter['filter']=='true'){
            
            $comma = '';
            
            if(array_key_exists('page',$filter)){
                unset($filter['page']);
            }
            if(array_key_exists('filter',$filter)){
                unset($filter['filter']);
            }
            
            $sModel = $objectModel->where([]);
            
            foreach($filter as $k=>$v){
            
                //多选搜索
                if(is_string($v) && strpos($v,',')!=false){
                    $v = explode(',',$v);
                }
            
                if($k=='title'){
                    $sModel->where('title','like',DB::raw("'%$v%'"));
                    $filter_arr[$k] = $v;
                    
                }elseif($k=='deleted_at'){
                    
                    $sModel->onlyTrashed();
                    $filter_arr[$k] = true;
                    
                }else{
                    if(is_string($v)){
                        $sModel->where([$k=>$v]);
                        $filter_arr[$k] = $v;
                    }
            
                    if(is_array($v)){
                        $sModel->whereIn($k,$v);
                        $filter_arr[$k] = implode(',',$v);
                    }
                }
                
            }
            
            
            $data = $sModel->where('disable',0)->orderBy('id','desc')->orderBy('sort','desc')->with('heroimage')->paginate($page_size,['*'],'page',$page_no);
            
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
            $data = $objectModel->where('disable',0)->orderBy('id','desc')->orderBy('sort','desc')->with('heroimage')->paginate($page_size,['*'],'page',$page_no);
        }
        
    }
}