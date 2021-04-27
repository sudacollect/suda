<?php

namespace Gtd\Suda\Widgets\Theme;

use Arrilot\Widgets\AbstractWidget;
use View;
use Gtd\Suda\Widgets\Widget;

class TextareaWidget extends Widget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'count' => 1,
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $content = [];
        if(array_key_exists('content',$this->config)){

            $content = $this->config['content'];
            unset($this->config['content']);
        }
        
        return view('view_suda::widgets.theme.textarea.config', ['config' => $this->config,'content'=>$content]);
    }

    public function view($config)
    {
        $this->config = array_merge($this->config,$config);
        $content = [];
        if(array_key_exists('content',$this->config)){

            $content = $this->config['content'];
            unset($this->config['content']);
        }
        
        return view('view_suda::widgets.theme.textarea.view')->with(['content'=>$content]);
    }
}
