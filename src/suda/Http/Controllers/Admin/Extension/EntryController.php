<?php

namespace Gtd\Suda\Http\Controllers\Admin\Extension;


use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Response;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;
use Gtd\Suda\Models\Extension;
use Gtd\Suda\Models\Page;

use Gtd\Suda\Services\ExtensionService;

class EntryController extends DashboardController
{
    public $view_in_suda = true;
    
    
    public function index(Request $request,$ext_slug)
    {
        $slug = $ext_slug;
        
        $this->title(ucfirst($slug));
        $extension = (new ExtensionService)->getExtension($slug);
        
        //强制进入应用的菜单模式
        $this->setData('single_extension_menu',true);
        
        $this->data['sdcore']['extension'] = arrayObject($extension);

        $menus = Extension::getExtMenuBySlug($slug);
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
