<?php

namespace Gtd\Suda\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use Gtd\Suda\Http\Controllers\Admin\DashboardController;

use Gtd\Suda\Models\Operate;
use Gtd\Suda\Models\Taxonomy;
use Gtd\Suda\Traits\TaxonomyTrait;


class OrganizationController extends DashboardController
{
    use TaxonomyTrait;

    public $redirect_url = 'user/organization';
    public $taxonomy_name = 'org_category';
    public $taxonomy_title = '部门';
    
    function __construct(){
        parent::__construct();
        
        $this->title('部门管理');
        $this->setMenu('setting','setting_operate_org');

        
        //增加自定义的权限控制
        $this->middleware(function (Request $request, $next) {
            $this->gate('role.setting_operate_org',app(Operate::class));
            
            return $next($request);
        });
        
    }


    public function getList(Request $request,$taxonomy_name='')
    {
        if(!$this->taxonomy_name && empty($taxonomy_name)){
            return redirect('error');
        }
        if(!empty($taxonomy_name)){
            $this->taxonomy_name = $taxonomy_name;
        }
        
        $taxonomyObj = new Taxonomy;
        $categories = $taxonomyObj->lists($this->taxonomy_name);

        $this->setData('categories',$categories);
        
        $this->getButtonConfig();
        return $this->display($this->getViewConfig('list'));
    }

    public function viewConfig(){

        return [

            'list'=>'view_suda::taxonomy.category.list',
            'create'=>'view_suda::taxonomy.category.add',
            'update'=>'view_suda::taxonomy.category.edit',
        ];

    }

    public function buttonConfig(){

        $buttons = [];
    
        $buttons['create']  = 'user/organization/add';
        $buttons['update']  = 'user/organization/edit';
        $buttons['save']    = 'user/organization/save';
        $buttons['delete']  = 'user/organization/delete';
        $buttons['sort']    = 'user/organization/editsort';
    
        return $buttons;
    }
    

}
