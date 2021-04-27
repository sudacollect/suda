<?php
//2020-1-14这个方法暂时用，解析出来的数据结构没法使用
namespace Gtd\Suda\Widgets;

use Arrilot\Widgets\Facade as ArriWidget;
use Arrilot\Widgets\AsyncFacade as AsyncWidget;
use View;
use Gtd\Suda\Widgets\Widget;

class WidgetExtend extends Widget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'count' => 1
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {

        $widget_extends = $this->config['extend'];

        if(is_string($widget_extends) && strpos($widget_extends,'@')!==false){

            $widgets = explode('@',$widget_extends);
            $class_name = $widgets[0];
            $func_name = $widgets[1];
            return (new $class_name)->$func_name();

        }elseif($widget_extends){
            return AsyncWidget::run($widget_extends,['async'=>true]);
        }

        return false;

    }
}
