<?php
/**
 * OrganizationController.php
 * description
 * date 2017-11-06 10:23:31
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */

namespace Gtd\Suda\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;
use Response;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;
use Gtd\Suda\Models\User;
use Gtd\Suda\Models\Organization;

use Gtd\Suda\Models\Setting;

class OrganizationOldController extends DashboardController
{
    public $view_in_suda = true;
    
    function __construct(){
        parent::__construct();
        
        $this->setMenu('setting','setting_operate_org');
        
        $this->middleware(function (Request $request, $next) {
            $this->gate('setting.setting_operate_org',app(Setting::class));
            
            return $next($request);
        });
    }
    
    //$view = ['list','gallery']
    public function index(Request $request,$view='list')
    {   
        
        
        
        $this->title('部门管理');
        $page_no = 0;
        if($request->get('page')){
            $page_no = $request->get('page');
        }
        
        $orgs = Organization::where([])->orderBy('id','desc')->paginate(20,['*'],'page',$page_no);
        
        
        $this->setData('orgs',$orgs);
        
        $view = 'user.organization.list';
        
        return $this->display($view);
        
    }
    
    
    public function add(){
        $this->title('增加部门');
        return $this->display('user.organization.add');
    }
    
    public function edit($id=0){
        $this->title('编辑部门');
        
        $id = intval($id);
        $org = Organization::where('id','=',$id)->first();
        
        if(!$org){
            return redirect(admin_url('error'))->withErrors(['errcode'=>404,'errmsg'=>'没找到信息']);
        }
        $this->setData('org',$org);
        
        return $this->display('user.organization.edit');
    }
    
    
    public function saveOrg(Request $request){
        
        $error = '';
        $result = $this->orgValidator($request->all(),$error);
        if(!$result){
            return $this->responseAjax('fail',$error,'');
        }
        
        $id = intval($request->id);
        $org_exist = Organization::where('id','<>',$id)->where('name','=',$request->name)->first();
        $orgModel = new Organization;
        
        $disable = 1;
        if($request->has('disable') && $request->disable=='0'){
            $disable = 0;
        }
        
        if($id){
            if($org_exist){
                
                $url = '';
                return $this->responseAjax('fail','部门名称重复',$url);
                
            }else{
                
                $orgModel->where('id',$id)->update([
                    'name'=>$request->name,
                    'disable'=>$disable,
                ]);
                
            }
        }else{
            if($org_exist){
                $url = '';
                return $this->responseAjax('fail','部门名称重复',$url);
            }
            $orgModel->name = $request->name;
            $orgModel->remarks = $request->name;
            $orgModel->disable = $disable;
            
            $orgModel->save();
            
        }
        
        $url = 'user/organization';
        return $this->responseAjax('success',__('suda_lang::press.msg.success'),$url);
        
        //return redirect(admin_url('user/organization'));
    }
    
    protected function orgValidator(array $data,&$error='')
    {
        $orgs_add = ['name' => 'required|unique:roles|min:2|max:64'];
        $orgs_save = ['name' => 'required|min:2|max:64'];
        
        if(array_key_exists('id',$data)){
            $roles = $orgs_save;
        }else{
            $roles = $orgs_add;
        }
        
        $messages = [
            'required'=>'请填写部门名称',
            'unique'=>'部门名称重复，请更换一个名字'
        ];
        $ajax_result = $this->ajaxValidator($data, $roles,$messages,$response_msg);
        if(!$ajax_result){
            $error = $response_msg;
            return false;
        }
        return true;
    }
    
    
    public function delete(Request $request){
        
        if($request->id && !empty($request->id)){
            
            $screen = Organization::where('id',$request->id)->first();
            
            if($screen){
                
                Organization::where('id',$request->id)->delete();
                $url = '';
                return $this->responseAjax('success','删除成功',$url);
                
            }else{
                $url = 'user/organization';
                return $this->responseAjax('error','数据不存在,请重试',$url);
            }
            
        }else{
            $url = 'user/organization';
            return $this->responseAjax('error','数据不存在,请重试',$url);
        }
        
    }
}
