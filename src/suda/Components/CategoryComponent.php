<?php

namespace Gtd\Suda\Components;

use Livewire\Component;

class CategoryComponent extends Component
{
    public $title;
    public $content;
  
    public function mount()
    {
        $this->title = 123;
        $this->content = 456;
    }

    public function delete()
    {
        // $this->title = '4566';
        // $this->emit('showDelete','delete');
    }

    public function render()
    {
        $view = 'view_suda::admin.component.livewire.category';

        return view($view);
        
    }
}
