<?php

namespace Gtd\Suda\Widgets;

use Arrilot\Widgets\AbstractWidget;
use View;
use Gtd\Suda\Widgets\Widget;

class Start extends Widget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'count' => 5
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        return view('view_suda::widgets.start', ['config' => $this->config]);
    }
}
