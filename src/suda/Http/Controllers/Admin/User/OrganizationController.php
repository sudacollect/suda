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

    public $taxonomy_name = 'org_category';
    public $taxonomy_title = '部门';
    
    function __construct(){
        parent::__construct();
        
        $this->title(__('suda_lang::press.menu_items.setting_operate_org'));
        $this->setMenu('setting','setting_operate_org');

        $this->taxonomy_title = __('suda_lang::press.organization');
        
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
        $page_no = $request->page?$request->page:1;
        $categories = $taxonomyObj->lists($this->taxonomy_name, 30, $page_no);
        

        $this->setData('categories',$categories);
        
        $this->getActions();
        return $this->display($this->getViews('list'));
    }

    public function viewConfig(){

        return [

            'list'      => 'view_suda::taxonomy.category.list',
            'create'    => 'view_suda::taxonomy.category.add',
            'update'    => 'view_suda::taxonomy.category.edit',
        ];

    }

    public function actionConfig(){

        $buttons = [];
    
        $buttons['create']  = admin_url('user/organization/add');
        $buttons['update']  = admin_url('user/organization/edit');
        $buttons['save']    = admin_url('user/organization/save');
        $buttons['delete']  = admin_url('user/organization/delete');
        $buttons['sort']    = admin_url('user/organization/editsort');
    
        return $buttons;
    }
    

}
