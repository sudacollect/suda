<?php

namespace Gtd\Suda\Http\Controllers\Admin\Extension;


use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Response;
use Illuminate\Support\Str;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;
use Gtd\Suda\Models\Extension;
use Gtd\Suda\Models\Page;


class ExtensionController extends DashboardController
{
    public $view_in_suda = true;
    
    public $self_url = 'manage/extension/list';
    
    
    public function index(Request $request,$status='enabled')
    {
        
        $this->gate('setting.update',app(\Gtd\Suda\Models\Setting::class));

        $this->title(__('suda_lang::press.menu_items.tool_extend'));
        $page_no = 0;
        $page_size = 20;
        if($request->get('page')){
            $page_no = $request->get('page');
        }
        
        $data = app('suda_extension')->allExtensions();
        
        $available_data = app('suda_extension')->availableExtensions();
        
        $this->setData('ext_list',$data);
        if($status=='disabled'){
            $not_avaliables = array_diff_key($data,$available_data);
            $this->setData('ext_list',$not_avaliables);
            $this->setData('data_count',count($not_avaliables));
        }else{
            $this->setData('ext_list',$available_data);
            $this->setData('data_count',count($available_data));
        }
        
        
        $this->setData('available_ext_list',$available_data);
        
        $quickins = app('suda_extension')->getQuickins();
        $this->setData('quickins',$quickins);

        $this->setData('active',$status);
        
        $this->setMenu('tool','tool_extend');
        return $this->display('extension.list');
    }
    
    
    public function flushExtensions(Request $request)
    {

        if(!\Gtd\Suda\Auth\OperateCan::superadmin($this->user))
        {
            return $this->responseAjax('fail',$msg?$msg:'无权限');
        }
        
        $msg = '';
        
        app('suda_extension')->updateCache($msg);
        
        return $this->responseAjax('info',$msg?$msg:'更新成功','self.refresh');
        
    }
    
    public function flushExtension(Request $request,$extension_slug)
    {
        if(!\Gtd\Suda\Auth\OperateCan::superadmin($this->user))
        {
            return $this->responseAjax('fail',$msg?$msg:'无权限');
        }
        
        if($extension_slug==$request->id){
            $msg = '';
            $result = app('suda_extension')->updateExtensionCache($extension_slug,$msg);
            if($result){
                return $this->responseAjax('info','应用已刷新','self.refresh');
            }
            
        }
        return $this->responseAjax('fail',$msg?$msg:'应用刷新失败');
    }
    
    public function getExtensionLogo(Filesystem $files, Request $request,$extension_name){
        
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

    //install extension
    public function toInstall(Request $request,$extension_slug)
    {

        if(!\Gtd\Suda\Auth\OperateCan::superadmin($this->user))
        {
            return $this->responseAjax('fail',$msg?$msg:'无权限');
        }

        if($extension_slug!=$request->id)
        {
            return $this->responseAjax('fail',$msg?$msg:'应用安装失败');
        }
        
        $msg = '';
        $result = app('suda_extension')->enableExtension($extension_slug,false,$msg);
        
        if(!$result){
            return $this->responseAjax('fail',$msg?$msg:'应用安装异常');
        }
        
        $msg = '';
        return $this->responseAjax('info',$msg?$msg:'应用安装成功','manage/extension');
        
    }
    
    //uninstall extension
    public function toUninstall(Request $request,$extension_slug)
    {

        if(!\Gtd\Suda\Auth\OperateCan::superadmin($this->user))
        {
            return $this->responseAjax('fail',$msg?$msg:'无权限');
        }

        if($extension_slug!=$request->id)
        {
            return $this->responseAjax('fail',$msg?$msg:'应用卸载失败');
        }
        
        $msg = '';
        $result = app('suda_extension')->disableExtension($extension_slug,$msg);
        
        if(!$result){
            return $this->responseAjax('fail',$msg?$msg:'应用卸载异常');
        }
        
        $msg = '';
        return $this->responseAjax('info',$msg?$msg:'应用卸载成功','self.refresh');
        
    }


    public function setQuickin(Request $request,$extension_slug){

        
        if($extension_slug==$request->id){
            $msg = '';
            $status = 1;
            if($request->content=='checked')
            {
                $status = 0;
            }
            
            $result = app('suda_extension')->setQuickin($extension_slug,$status,$msg);
            if($result){
                return $this->responseAjax('info','设置成功');
            }
            return $this->responseAjax('fail',$msg?$msg:'设置失败');
        }
    }


    //应用排序
    public function resort(Request $request)
    {
        
        if($request->order){
            $msg = '';
            $result = app('suda_extension')->resort($request->order,$msg);
            if($result){
                return $this->responseAjax('info','设置成功');
            }
            return $this->responseAjax('fail',$msg?$msg:'设置失败');
        }
    }
    
}
