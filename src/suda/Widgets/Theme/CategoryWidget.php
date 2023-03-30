<?php

namespace Gtd\Suda\Widgets\Theme;

use Arrilot\Widgets\AbstractWidget;
use View;
use Gtd\Suda\Widgets\Widget;

use Gtd\Suda\Models\Taxonomy;

class CategoryWidget extends Widget
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

        return view('view_suda::widgets.theme.category.config', $params);
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


            //所有分类
            $taxonomyObj = new Taxonomy;
            if(array_key_exists('parent',$content) && $content['parent']==1){
                $categories = $taxonomyObj->where('taxonomy','post_category')->where('parent',0)->get();
            }else{
                $categories = $taxonomyObj->listAll('post_category');
            }
            
            $params['categories'] = $categories;

        }
        
        return view('view_suda::widgets.theme.category.view', $params);

    }
}
