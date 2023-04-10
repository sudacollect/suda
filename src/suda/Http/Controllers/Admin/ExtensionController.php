<?php

namespace Gtd\Suda\Http\Controllers\Admin;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Validator;
use Response;
use URL;

use Session;
use Redirect;
use View;

use Gtd\Suda\Contracts\Extension as ExtensionContracts;
use Gtd\Suda\Traits\Extension;
use Gtd\Suda\Models\Setting;


class ExtensionController extends DashboardController implements ExtensionContracts
{
    use Extension;
    
    public $auth_setting;
    public $auth_data = [];
    //设置当前view所在目录
    public $view_in_suda = false;
    
    //应用设置独立的菜单
    public $single_extension_menu = true;
    
    public function __construct(){
        
        $this->getExtensionInfo();
        $this->auth_setting = $this->getExtensionFile('auth_setting.php');
        
        if(!is_array($this->extension_info))
        {
            exit('extension config wrong.');
        }

        if(!array_key_exists('setting',$this->extension_info))
        {
            $this->extension_info['setting'] = [];
        }
        parent::__construct();

        //#TODO 配置路由，可根据路由的名字进行权限识别,会更简单
        $this->middleware(function (Request $request, $next) {

            //$this->auth_setting
            $ext_slug = isset($this->extension_info['slug'])?$this->extension_info['slug']:'';
            $auth_keys = [];
            if($this->auth_setting && is_array($this->auth_setting)){
                $auth_keys = array_keys($this->auth_setting);
            }

            if($ext_slug && count($auth_keys)>0 && \Gtd\Suda\Auth\OperateCan::extension($this->user)){
                $permissions = $this->user->role->permissions;

                //suda-exts
                if(array_key_exists('exts',$permissions)){
                    if(array_key_exists($ext_slug,$permissions['exts'])){
                        if(array_key_exists('#auth',$permissions['exts'][$ext_slug])){
                            foreach($auth_keys as $auth_key)
                            {
                                if(array_key_exists($auth_key,$permissions['exts'][$ext_slug]['#auth'])){
                                    
                                    $auth_ids = $permissions['exts'][$ext_slug]['#auth'][$auth_key];
                                    $this->auth_data[$auth_key] = $auth_ids;

                                }
                            }
                            

                        }
                    }
                }
            }

            return $next($request);

        });
    }


    //加入权限判断
    public function gate($action,$isAjax=false,$model='')
    {
        $action = 'extension#'.$this->extension_info['slug'].'.'.$action;
        if(!$model)
        {
            $model = app(Setting::class);
        }
        parent::gate($action,$model,$isAjax);
    }
    
    public function display($view)
    {

        if(strpos($view,'::') === false)
        {
            $view = 'view_extension::'.ucfirst($this->extension_info['slug']).'/resources/views/admin/'.$view;
        }
        
        View::addNamespace('extension', $this->extension_info['path'].'/resources/views/');
        
        if($this->single_extension_menu)
        {    
            $this->setData('single_extension_menu',true);
        }
        
        //塞入应用信息
        $sdcore = $this->data('sdcore');
        $sdcore['extension'] = $this->extension_info;
        $this->setData('sdcore',$sdcore);
        
        return parent::display($view);
    }
    
}
