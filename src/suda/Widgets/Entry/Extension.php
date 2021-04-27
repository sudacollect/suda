<?php

namespace Gtd\Suda\Widgets\Entry;

use Arrilot\Widgets\AbstractWidget;
use View;
use Gtd\Suda\Widgets\Widget;

use Gtd\Suda\Models\Setting;

class Extension extends Widget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'count' => 10   //支持10组模块
    ];
    
    // public $reloadTimeout = 10;
    
    public function placeholder()
    {
        // return view('view_suda::widgets.loading');
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        return view('view_suda::widgets.entry.extension',['config'=>$this->config]);
        
    }
}
