<?php

namespace Gtd\Suda\Http\Controllers\Admin\Media;


use Illuminate\Http\Request;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;


use Gtd\Suda\Models\Article;
use Gtd\Suda\Traits\TagTrait;


class TagController extends DashboardController
{
    use TagTrait;

    public $redirect_url = 'mediatags';
    public $taxonomy_name = 'media_tag';

    function __construct(){
        parent::__construct();

        $this->title('媒体标签');
        $this->setMenu('media');

        $this->setData('media_tab','tag');
    }
    

    public function viewConfig(){

        return [

            'list'=>'view_suda::admin.media.tag.list',
            'create'=>'view_suda::taxonomy.tag.add',
            'update'=>'view_suda::taxonomy.tag.edit',
        ];

    }

    //设置自定义的链接
    public function buttonConfig(){

        $buttons = [];

        $buttons['create']  = 'mediatags/add';
        $buttons['update']  = 'mediatags/update';
        $buttons['save']    = 'mediatags/save';
        $buttons['delete']  = 'mediatags/delete';
        $buttons['sort']    = 'mediatags/editsort';
        
        return $buttons;
    }
}