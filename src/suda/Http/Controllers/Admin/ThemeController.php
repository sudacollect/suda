<?php
/**
 * ThemeController.php
 * description
 * date 2017-11-06 12:22:45
 * author suda <dev@panel.cc>
 * @copyright Suda. All Rights Reserved.
 */

namespace Gtd\Suda\Http\Controllers\Admin;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;
use Response;
use Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;

use Gtd\Suda\Models\Media;
use Gtd\Suda\Models\Setting;

class ThemeController extends DashboardController
{
    public $self_url = 'theme';
    public $view_in_suda = true;
    
    public function index(Request $request,$app = 'site')
    {
        if($app=='admin'){
            exit('403');
        }

        $this->gate('appearance.appearance_theme',app(Setting::class));
        $this->title('模板管理');
        
        $this->setData('app_name',$app);

        $current_theme = app('theme')->getTheme($app);
        $this->setData('current_theme',$current_theme);
        
        $themes = app('theme')->availableThemes($app);
        if($themes && count($themes)>0){
            $this->setData('themes',$themes);
        }

        if(array_key_exists($current_theme,$themes)){
            $this->setData('theme_info',$themes[$current_theme]);
        }

        //显示可切换的应用
        $apps = config('sudaconf.apps',['site','admin']);
        $this->setData('apps',$apps);
        
        $this->setMenu('appearance','appearance_theme');
        
        return $this->display('theme.index');
    }
    
    // set theme
    public function setTheme(Request $request){
        
        $roles = [];
        $messages = [];
        
        $response_msg = '';
        if(!$request->app_name || !$request->theme_name){
            
            $roles['theme'] = 'required';
            $messages['theme.required'] = '设置异常，请刷新后重试';
            $ajax_result = $this->ajaxValidator($request->all(),$roles,$messages,$response_msg);
        }
        
        if(!$response_msg){
            $return = app('theme')->setTheme($request->app_name,$request->theme_name,true);
            if($return){
                return $this->responseAjax('success',__('suda_lang::press.msg.success'),'theme');
            }
        }

        $url = 'theme';
        return $this->responseAjax('fail',$response_msg,$url);
        
    }
    
    // update-cache
    public function updateCache(Request $request,$app){
        
        app('theme')->updateCache($app);
        $url = 'theme';

        if($request->app && $request->app=='admin'){
            $url = 'style/dashboard';
        }

        return $this->responseAjax('success',__('suda_lang::press.msg.success'),$url);
    }
    
    // screenshot
    public function getScreenshot(Filesystem $files,Request $request,$app_path,$theme_path){
        
        $screenshot = theme_path($app_path.'/'.$theme_path.'/screenshot.png');
        
        if($files->exists($screenshot)){
            $file = $files->get($screenshot);
            $type = $files->mimeType($screenshot);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        }
        
    }
    
}
