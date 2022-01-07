<?php

namespace Gtd\Suda\Http\Controllers\Site;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Gtd\Suda\Http\Controllers\SiteController;
use Gtd\Suda\Models\Page;

class HomeController extends SiteController{
    
    public $view_in_suda = true;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(Request $request){
        
        //判断是否新增自定义首页
        
        if(array_key_exists('site',$this->data['sdcore']['settings']) && array_key_exists('default_page',$this->data['sdcore']['settings']['site'])){
            
            $default_page = $this->data['sdcore']['settings']['site']['default_page'];
            
            $default_page = $default_page?unserialize($default_page):[];
            
            if(count($default_page)>0){
                
                $page_type = $default_page['page_type'];
                $page_value = $default_page['page_value'];
                
                switch($page_type){
                    
                    case 'default_page':
                    break;
                    
                    case 'single_page':
                        $page_id = $page_value;
                        
                        $page = Page::where('id','=',$page_id)->with('heroimage')->first();
                        
                        
                        if(!$page){
                            return redirect('sdone/status/404');
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
                        
                    break;
                    
                    case 'link_page':
                        return redirect(url($default_page['page_value']));
                    break;
                    
                }
                
            }
            
        }

        $this->setData('active_tab','home');
        
        $this->title('Welcome');
        return $this->display('home');
    }
}