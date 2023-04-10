<?php

namespace Gtd\Suda\Http\Controllers\Admin\Extension;


use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Response;
use Illuminate\Support\Str;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;
use Gtd\Suda\Models\Page;


class ManageController extends DashboardController
{
    public $view_in_suda = true;
    
    public $self_url = 'manage/extension/list';
    
    
    public function index(Request $request,$status='enabled')
    {
        $this->gate('tool.tool_extend',app(\Gtd\Suda\Models\Setting::class));

        $this->title(__('suda_lang::press.menu_items.tool_extend'));
        $page_no = 0;
        $page_size = 20;
        if($request->get('page')){
            $page_no = $request->get('page');
        }
        
        $func = 'get'.ucfirst($status).'data';
        if(method_exists($this,$func))
        {
            $this->$func();
        }else{
            return $this->redirect('404','extensions not found');
        }
        
        $quickins = app('suda_extension')->getQuickins();
        $this->setData('quickins',$quickins);

        $this->setData('active',$status);
        
        $this->setMenu('tool','tool_extend');
        return $this->display('extension.list');
    }

    protected function getEnabledData()
    {
        $available_data = app('suda_extension')->installedExtensions();
        $this->setData('ext_list',$available_data);
        $this->setData('data_count',count($available_data));
    }

    protected function getAvailableData()
    {
        $data = app('suda_extension')->localExtensions();
        $this->setData('ext_list',$data);
        $this->setData('data_count',count($data));
    }

    protected function getPackageData()
    {
        $data = app('suda_extension')->composerExtensions();
        $this->setData('ext_list',$data);
        $this->setData('data_count',count($data));
    }
    
    
    public function flushExtensions(Request $request)
    {

        if(!\Gtd\Suda\Auth\OperateCan::superadmin($this->user))
        {
            return $this->responseAjax('fail',$msg?$msg:'无权限');
        }
        
        $msg = '';
        
        app('suda_extension')->updateLocalCache($msg);
        app('suda_extension')->updateComposerCache($msg);
        
        return $this->responseAjax('info',$msg?$msg:__('suda_lang::press.msg.success'),'self.refresh');
        
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
                return $this->responseAjax('info',__('suda_lang::press.msg.success'),'self.refresh');
            }
            
        }
        return $this->responseAjax('fail',$msg?$msg:__('suda_lang::press.msg.fail'));
    }
    
    public function extLogo(Filesystem $files, Request $request,$slug)
    {
        $extension = app('suda_extension')->use($slug)->extension;
        
        $file = $files->get($extension['logo']);
        $type = $files->mimeType($extension['logo']);
        
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }

    // install extension
    public function toInstall(Request $request,$slug)
    {

        if(!\Gtd\Suda\Auth\OperateCan::superadmin($this->user))
        {
            return $this->responseAjax('fail',$msg?$msg:'无权限');
        }

        if($slug != $request->id)
        {
            return $this->responseAjax('fail',$msg?$msg:__('suda_lang::press.msg.fail'));
        }
        
        $msg = '';
        $result = app('suda_extension')->install($slug,false,$msg);
        
        if(!$result){
            return $this->responseAjax('fail',$msg?$msg:__('suda_lang::press.msg.fail'));
        }
        
        $msg = '';
        return $this->responseAjax('info',$msg?$msg:__('suda_lang::press.msg.success'),'manage/extension');
        
    }


    // uninstall confirm
    public function uninstallConfirm(Request $request,$extension_slug)
    {
        $extension = app('suda_extension')->use($extension_slug)->extension;
        if(!$extension)
        {
            return $this->responseAjax('fail',__('suda_lang::press.msg.fail'));
        }
        $this->setData('item',$extension);

        $this->setData('modal_title','Uninstall');
        return $this->display('extension.uninstall');
        
    }
    
    // uninstall extension
    public function toUninstall(Request $request,$extension_slug)
    {
        if($request->extension_slug != $extension_slug)
        {
            return $this->responseAjax('fail',__('suda_lang::press.msg.fail'));
        }

        if(!\Gtd\Suda\Auth\OperateCan::superadmin($this->user))
        {
            return $this->responseAjax('fail',$msg?$msg:'无权限');
        }

        $drop_table = $request->drop_table;
        
        $msg = '';
        $result = app('suda_extension')->uninstall($extension_slug, $drop_table, $msg);
        
        if(!$result){
            return $this->responseAjax('fail',$msg?$msg:__('suda_lang::press.msg.fail'));
        }
        
        $msg = '';
        return $this->responseAjax('info',$msg?$msg:__('suda_lang::press.msg.success'),'self.refresh');
        
    }

    // extension quickin 
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
                return $this->responseAjax('info',__('suda_lang::press.msg.success'),'self.refresh');
            }
            return $this->responseAjax('fail',$msg?$msg:__('suda_lang::press.msg.fail'));
        }
    }

    // extension sort
    public function resort(Request $request)
    {
        
        if($request->order){
            $msg = '';
            $result = app('suda_extension')->resort($request->order,$msg);
            if($result){
                return $this->responseAjax('info',__('suda_lang::press.msg.success'),'self.refresh');
            }
            return $this->responseAjax('fail',$msg?$msg:__('suda_lang::press.msg.fail'));
        }
    }
    
}
