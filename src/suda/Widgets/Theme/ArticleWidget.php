<?php

namespace Gtd\Suda\Widgets\Theme;

use Arrilot\Widgets\AbstractWidget;
use View;
use Gtd\Suda\Widgets\Widget;

use Gtd\Suda\Models\Article;
use Gtd\Suda\Models\Taxonomy;

class ArticleWidget extends Widget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        if($this->config['view']=='config'){
            return $this->displayConfig();
        }
    }

    //挂件配置
    public function displayConfig()
    {
        $params = [];
        $params = ['config' => $this->config];
        $content = [];
        if(array_key_exists('content',$this->config))
        {
            $content = $this->config['content'];
            unset($this->config['content']);
            $params['content'] = $content;
        }

        //设置文章分类，设置显示的数量，设置显示时间
        $taxonomyObj = new Taxonomy;
        $catgories = $taxonomyObj->where('taxonomy','post_category')->get();
        $params['catgories'] = $catgories;

        return view('view_suda::widgets.theme.article.config', $params);
    }

    //挂件输出
    public function view($config)
    {
        $params = [];
        $params = ['config' => $config];
        $content = [];
        if(array_key_exists('content',$config))
        {
            $content = $config['content'];
            unset($config['content']);
            $params['content'] = $content;
            
            if(isset($content['category'])){
                $category_id = $content['category'];
                $articles = Article::where('disable',0)->whereHas('categories',function($query) use ($category_id){
                    $query->where('taxonomy_id',$category_id);
                })->orderBy('stick_top','desc')->orderBy('id','desc')->with('operate')->with('heroimage')->limit($content['num'])->get();
                
            }else{
                $articles = Article::where('disable',0)->orderBy('stick_top','desc')->orderBy('id','desc')->with('operate')->with('heroimage')->limit($content['num'])->get();
            }

            
            
            $params['articles'] = $articles;
        }
        

        return view('view_suda::widgets.theme.article.view', $params);

    }
}
