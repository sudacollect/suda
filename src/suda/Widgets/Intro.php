<?php

namespace Gtd\Suda\Widgets;

use Arrilot\Widgets\AbstractWidget;
use View;
use Gtd\Suda\Widgets\Widget;

class Intro extends Widget
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

        $soperate = auth('operate')->user();

        return view('view_suda::widgets.intro', ['config' => $this->config,'soperate'=>$soperate]);
    }
}
