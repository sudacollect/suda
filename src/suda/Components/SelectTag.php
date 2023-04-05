<?php
namespace Gtd\Suda\Components;

use Illuminate\View\Component;
use \Illuminate\Database\Eloquent\Collection;

use Gtd\Suda\Models\Taxonomy;

class SelectTag extends Component
{
    
    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct(
        public ?Collection $tags,
        public string $taxonomy = '',
        public int $max = 5,
        public string $placeholder = '- tag -',
        public string $name = 'tag[]',
        public array $exclude = [],
        public string $link = '',
    ){}
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        $tags = $this->tags;
        if(!$tags)
        {
            $tags = Taxonomy::where('taxonomy',$this->taxonomy)->limit(10)->get();
        }
        
        $data = [
            'tags'          => $tags,
            'taxonomy'      => $this->taxonomy,
            'placeholder'   => $this->placeholder,
            'name'          => $this->name,
            'exclude'       => $this->exclude,
            'max'           => $this->max,
            'link'          => $this->link,
        ];
        
        $view = 'view_suda::components.select_tag';
        return view($view)->with($data);
    }
}