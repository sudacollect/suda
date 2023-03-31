<?php
namespace Gtd\Suda\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class EmptyBlock extends Component
{
    public string $app;
    public string $type;
    public string $empty;
    public bool $card;
    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct(string $empty = '',bool $card = true,string $type='content',string $app = 'admin')
    {
        $this->empty = $empty;
        $this->card = $card;
        $this->type = $type;
        $this->app = $app;
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): View
    {
        
        
        $view = 'view_suda::components.empty';
        $data = [
            'card'          => $this->card,
            'type'          => $this->type,
            'empty'         => $this->empty,
        ];

        return view($view)->with($data);
        // return app('theme')->render($this->app, $view, $view,$data);
    }
}