<?php

namespace Gtd\Suda\Http\Controllers\Admin\Article;

use Illuminate\Http\Request;
use Gtd\Suda\Http\Controllers\Admin\DashboardController;

use Gtd\Suda\Models\Article;
use Gtd\Suda\Traits\TaxonomyTrait;


class CategoryController extends DashboardController
{
    use TaxonomyTrait;
    
    public $taxonomy_name = 'post_category';
    
    function __construct(){
        parent::__construct();
        
        $this->title(__('suda_lang::press.category'));
        $this->setMenu('article','article_category');
        
        //增加自定义的权限控制
        $this->middleware(function (Request $request, $next) {
            $this->gate('article.article_category',app(Article::class));
            
            return $next($request);
        });
        
    }
    

}
