<?php

namespace Gtd\Suda\Widgets\Theme;

use Arrilot\Widgets\AbstractWidget;
use View;
use Gtd\Suda\Widgets\Widget;
use Gtd\Suda\Models\Page;

class PageWidget extends Widget
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

        return view('view_suda::widgets.theme.page.config', $params);
    }

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

            $pages = Page::where('disable',0)->orderBy('stick_top','desc')->orderBy('id','desc')->with('operate')->with('heroimage')->limit($content['num'])->get();
            
            $params['pages'] = $pages;
        }
        

        return view('view_suda::widgets.theme.page.view', $params);

    }

}
