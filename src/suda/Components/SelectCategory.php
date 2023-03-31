<?php
namespace Gtd\Suda\Components;

use Illuminate\View\Component;

use Gtd\Suda\Models\Taxonomy;

class SelectCategory extends Component
{
    
    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct(
        public string $taxonomy = '',
        public array $selected = [],
        public string $type = 'single',
        public string $placeholder = '- 选择分类 -',
        public string $app = 'admin',
        public string $name = 'category[]',
        public array $exclude = [],
    ){}
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        
        $taxonomyObj = new Taxonomy;
        $categories = $taxonomyObj->where('parent',0)->where('taxonomy',$this->taxonomy)->get();

        $data = [
            'cates'     => $categories,
            'child'     => 0,
            'taxonomy'  => $this->taxonomy,
            'selected'  => $this->selected,
            'placeholder'  => $this->placeholder,
            'type'      => $this->type,
            'name'      => $this->name,
            'exclude'   => $this->exclude,
        ];
        
        $view = 'view_suda::components.select_category';
        return view($view)->with($data);
        // return app('theme')->render($this->app, $view, $view, $data);
    }
}