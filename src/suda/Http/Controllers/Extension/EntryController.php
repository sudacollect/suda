<?php

namespace Gtd\Suda\Http\Controllers\Extension;


use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Response;

use Gtd\Suda\Http\Controllers\Extension\DashboardController;
use Gtd\Suda\Models\Extension;
use Gtd\Suda\Models\Page;

use Gtd\Suda\Services\ExtensionService;

class EntryController extends DashboardController
{
    public $view_in_suda = true;
    
    
    public function detail(Request $request,$ext_slug)
    {
        $slug = $ext_slug;
        
        $this->title(ucfirst($slug));
        $extension = (new ExtensionService)->getExtension($slug);
        
        //强制进入应用的菜单模式
        $this->setData('single_extension_menu',true);
        
        $this->data['sdcore']['extension'] = arrayObject($extension);

        $menus = Extension::getExtMenuBySlug($slug);
        $this->setData('extension_menus',$menus);
        
        $view = 'view_suda::extension.entry';
        return $this->display($view);
    }

    public function getLogo(Filesystem $files, Request $request,$extension_name){
        
        $path = extension_path(ucfirst($extension_name) . '/' . 'icon.png');
        
        if (!$files->exists($path)) {
            $path = public_path(config('sudaconf.core_assets_path').'/images/empty_extension_icon.png');
        }
        
        $file = $files->get($path);
        $type = $files->mimeType($path);
        
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }

    public function index(Request $request)
    {   
        $this->title('运营中心');
        
        return $this->display('view_suda::extension.index');
    }
    
}
