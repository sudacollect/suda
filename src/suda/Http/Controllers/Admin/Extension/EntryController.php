<?php

namespace Gtd\Suda\Http\Controllers\Admin\Extension;


use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Response;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;
use Gtd\Suda\Models\Page;

class EntryController extends DashboardController
{
    public $view_in_suda = true;
    
    
    public function index(Request $request,$ext_slug)
    {
        $slug = $ext_slug;
        
        $this->title(ucfirst($slug));
        $extService = app('suda_extension')->use($slug);
        $extension = $extService->extension;
        
        //强制进入应用的菜单模式
        $this->setData('single_extension_menu',true);
        
        $this->data['sdcore']['extension'] = arrayObject($extension);

        $menus = $extService->getMenu();
        $this->setData('extension_menus',$menus);
        
        $view = 'extension.entry';
        return $this->display($view);
    }


    public function showExtensions(Request $request)
    {   
        $this->title('控制面板');
        
        return $this->display('extension.entry_extensions');
    }
    
}
