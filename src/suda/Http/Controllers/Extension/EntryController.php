<?php

namespace Gtd\Suda\Http\Controllers\Extension;


use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Response;

use Gtd\Suda\Http\Controllers\Extension\DashboardController;
use Gtd\Suda\Models\Page;

class EntryController extends DashboardController
{
    public $view_in_suda = true;
    
    
    public function detail(Request $request,$ext_slug)
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
        
        $view = 'view_suda::extension.entry';
        return $this->display($view);
    }

    public function getLogo(Filesystem $files, Request $request,$slug)
    {
        $extension = app('suda_extension')->use($slug)->extension;
        $path = $extension['logo'];
        
        $file = $files->get($path);
        $type = $files->mimeType($path);
        
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }

    public function index(Request $request)
    {   
        $this->title('Extensions');
        
        return $this->display('view_suda::extension.index');
    }
    
}
