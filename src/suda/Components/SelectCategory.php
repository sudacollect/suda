<?php
namespace Gtd\Suda\Components;

use Illuminate\View\Component;

use Gtd\Suda\Models\Taxonomy;

class SelectCategory extends Component
{
    public string $app;
    public string $multi;
    public string $taxonomy;
    public string $placeholder;
    public array $selected;
 
    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct(
        string $multi = 'multiple',
        string $taxonomy = '',
        array $selected = [],
        string $placeholder = '- 选择分类 -',
        string $app = 'admin'
    )
    {
        $this->multi = $multi;
        $this->selected = $selected;
        $this->taxonomy = $taxonomy;
        $this->app = $app;
        $this->placeholder = $placeholder;
    }
 
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
            'multi'     => $this->multi,
        ];
        
        $view = 'view_suda::components.select_category';
        return app('theme')->render($this->app, $view, $view, $data);
    }
}