<?php

namespace Gtd\Suda\Http\Controllers\Admin\Article;


use Illuminate\Http\Request;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;


use Gtd\Suda\Models\Article;
use Gtd\Suda\Traits\TagTrait;


class TagController extends DashboardController
{
    use TagTrait;
    
    public $view_in_suda = true;
    
    public $taxonomy_name = 'post_tag';
    
    function __construct(){
        parent::__construct();
        
        $this->title('标签管理');
        $this->setMenu('article','article_tag');
        
        $this->middleware(function (Request $request, $next) {
            $this->gate('article.update',app(Article::class));
            
            return $next($request);
        });
        
    }
    

}
