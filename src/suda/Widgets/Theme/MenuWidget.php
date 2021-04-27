<?php

namespace Gtd\Suda\Widgets\Theme;

use Arrilot\Widgets\AbstractWidget;
use View;
use Gtd\Suda\Widgets\Widget;
use Gtd\Suda\Models\Menu;

class MenuWidget extends Widget
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

        //读取所有菜单分组
        $menus = Menu::where('id','>',1)->get();
        $params['menus'] = $menus;

        return view('view_suda::widgets.theme.menu.config', $params);
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

            if(isset($content['menu'])){
                $menu = Menu::where('id',$content['menu'])->first();
                if($menu){
                    $items = Menu::getMenuByName($menu->name);
                    $params['menu'] = $menu;
                    $params['items'] = $items;
                }
            }
            
            
            return view('view_suda::widgets.theme.menu.view', $params);
        }

    }
}
