<?php

namespace Gtd\Suda\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;
use Response;
use Illuminate\Support\Facades\Cache;

use Gtd\Suda\Models\Setting;
use Gtd\Suda\Http\Controllers\Admin\DashboardController;
use Gtd\Suda\Models\Operate;
use Gtd\Suda\Models\Organization;

use Gtd\Suda\Models\Media;

class StyleController extends DashboardController
{
    public $view_in_suda = true;
       
    //控制面板风格
    public function dashboardStyle(){
        $this->title('控制面板风格');
        $this->gate('role.setting_dashboard',app(Setting::class));
        
        
        $themes = app('theme')->availableThemes('admin');
        if($themes && count($themes)>0){
            $this->setData('themes',$themes);
        }
        
        $current_theme = $this->user_theme;
        if(empty($current_theme)){
            $current_theme = 'default';
        }
        $this->setData('current_theme',$current_theme);

        if(array_key_exists($current_theme,$themes)){
            $this->setData('theme',$themes[$current_theme]);
        }
        
        $this->setMenu('setting','setting_dashboard');
        return $this->display('home.style.dashboard');
    }
    
    //设置控制面板风格
    public function saveDashboardStyle(Request $request){
        
        if(!Auth::guard('operate')->check()){
            return redirect('home');
        }
        
        $settingModel = new Setting;
        $post = $request->all();
        unset($post['_token']);
        if(!array_key_exists('sidebar_style',$post)){
            return $this->responseAjax('fail','数据异常，请刷新页面重试');
        }
        
        if($first = $settingModel->where(['key'=>'sidebar_style'])->first()){
            $settingModel->where(['key'=>'sidebar_style'])->update(['values'=>$post['sidebar_style']]);
        }else{
            $settingModel->insert(['key'=>'sidebar_style','type'=>'site','values'=>$post['sidebar_style']]);
        }
        
        Setting::updateSettings();
        
        //更新成功
        $url = 'setting/sidebarstyle';
        return $this->responseAjax('success','保存成功',$url);
    }
    
    //设置模板
    public function setStyle(Request $request){
        $url = 'style/dashboard';
        $roles = [];
        $messages = [];
        
        $response_msg = '';
        if(!$request->app_name || !$request->theme_name){
            
            $roles['theme'] = 'required';
            $messages['theme.required'] = '设置异常，请刷新后重试';
            $ajax_result = $this->ajaxValidator($request->all(),$roles,$messages,$response_msg);
        }
        
        if(!$response_msg){
            
            $new_permission = $this->user->permission;
            $new_permission['style']['dashboard'] = $request->theme_name;

            $this->user->update([
                'permission'=>serialize($new_permission),
            ]);
            
            return $this->responseAjax('success','保存成功',$url);
        }
        
        
        return $this->responseAjax('fail',$response_msg,$url);
        
    }

    //细节控制
    public function dashboardLayout(Request $request)
    {
        //导航通栏
        //导航配色
        //两栏菜单

        if(isset($this->user->permission['style']) && isset($this->user->permission['style']['dashboard_layout']))
        {
            $this->setData('setting',$this->user->permission['style']['dashboard_layout']);
        }else{
            $this->setData('setting',[
                'navbar_layout'=>'flat',
                'navbar_color'=>'white',
            ]);
        }
        
        $this->setData('modal_title','设置控制面板');

        return $this->display('home.dashboard_layout');
    }

    //保存细节设置
    public function saveDashboardLayout(Request $request)
    {
        
        if(!Auth::guard('operate')->check()){
            return $this->responseAjax('fail','请先登录');
        }

        $new_permission = $this->user->permission;
        if(!isset($new_permission['style']))
        {
            $new_permission = ['style'=>[]];
        }

        $new_permission['style']['dashboard_layout'] = [
            'navbar_layout'=>$request->navbar_layout,
            'navbar_color'=>$request->navbar_color,
        ];

        $this->user->update([
            'permission'=>serialize($new_permission),
        ]);

        //更新成功
        return $this->responseAjax('success','保存成功','self.refresh');
        
    }
    
    //设置菜单显示样式
    public function sidemenu(Request $request,$style="flat"){
        
        if(in_array($style,['flat','icon'])){
            
            $user_id = $this->user->id;
            Cache::store(config('sudaconf.admin_cache','file'))->forever('sidemenu#'.$user_id, ['style'=>$style]);
            
        }
        
    }
    
    
    //预览模板
    
    public function previewStyle(Request $request,$theme_name){
        
        if(!$theme_name){
            
            return $this->dispatchError(404);
            
        }
        
        $this->setData('preview_theme',$theme_name);
        
        
        //====以下为dashboard index content===
        
        $this->title(__('suda_lang::press.dashboard').'预览');
        $this->setMenu('dashboard');
        
        //读取服务器信息
        $servers = [];
        $servers['remote_addr'] = $request->server('REMOTE_ADDR');
        $servers['server_software'] = $request->server('SERVER_SOFTWARE');
        
        $this->setData('servers',$servers);
        
        // return view('view_suda::widgets.news');
        return $this->display('dashboard');
        
    }
    
}
