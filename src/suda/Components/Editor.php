<?php
namespace Gtd\Suda\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Editor extends Component
{
    public string $id;
    public string $name;
    public int $height;
    public string $content;
    public string $app;
    
    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct(string $id, string $name, int $height=200,string $content = '',$app='admin')
    {
        $this->content  = $content;
        $this->height   = $height;
        $this->name     = $name;
        $this->id       = $id;
        $this->app      = $app;

    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): View
    {
        
        
        $view = 'view_suda::components.editor';
        $data = [
            'height'    => $this->height,
            'id'        => $this->id,
            'name'      => $this->name,
            'editor_content'   => $this->content,
        ];
        return view($view)->with($data);
        // return app('theme')->render($this->app, $view, $view,$data);
    }
}