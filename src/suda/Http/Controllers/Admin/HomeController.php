<?php

namespace Gtd\Suda\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;
use Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client as HttpClient;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;

use Gtd\Suda\Models\Page;
use Gtd\Suda\Models\Setting;
use Gtd\Suda\Models\Media;
use Gtd\Suda\Models\Mediatable;

use Gtd\Suda\Models\Operate;
use Gtd\Suda\Models\Organization;
use Gtd\Suda\Certificate;
use Gtd\Suda\Traits\SettingTrait;

class HomeController extends DashboardController
{
    use SettingTrait;
    public $view_in_suda = true;

    public function index(Request $request)
    {
        $this->title(__('suda_lang::press.dashboard'));
        $this->setMenu('dashboard');
        
        if(\Gtd\Suda\Auth\OperateCan::extension($this->user))
        {
            return redirect()->to(extadmin_url('entry/extensions'));
        }
        
        return $this->display('dashboard');
    }

    // 查看授权信息
    public function certificateInfo()
    {
        $this->title('产品授权');

        // $license = Certificate::getLicense();
        // $this->setData('license',$license);

        $sysinfo = \Gtd\Suda\Sudacore::sysInfo();
        $this->setData('sysinfo',$sysinfo);

        $this->setData('active_tab','certificate');

        $this->setData('extend_breadcrumbs',[
            [
                'slug'=>'certificate',
                'title'=>'授权信息',
                'url'=>'certificate',
                'route'=>'',
            ]
        ]);
        
        return $this->display('certificate');
    }
    
    // 查看更新信息
    public function updateInfo(){
        
        $this->title('产品版本信息');
        
        return $this->display('updateinfo');
    }
    

    // 暂时没用请求最新版本
    public function getUpdate(){
        
        $httpClient = new HttpClient();
        
        $update_url = Certificate::$product_url.'/version/json';
        
        $response = $httpClient->request('GET',$update_url);
        
        $status_code = $response->getStatusCode();
        if($status_code==200){
            
            $result =  $response->getBody();
            $version_result = (string) $result;
            $version_result = json_decode($version_result);
            
            if(isset($version_result->product) && isset($version_result->version)){
                
                return Response::make('<h5>最新版本 v'.$version_result->version.'</h5>', 200);
                
            }
        }
        
    }
    
    // 查看服务器信息
    public function serverInfo(){
        
        $this->title('服务器信息');
        
        return $this->display('serverinfo');
    }
    
    // phpinfo
    public function getPhpInfo(){
        
        exit(phpinfo());
    }
    
    // 站点设置项
    public function settings(){
        
        //policy 判断
        $this->gate('setting.setting_system',app(Setting::class));
        
        $this->title(__('suda_lang::press.basic_info'));
        
        
        
        $this->setMenu('setting','setting_system');
        
        return $this->display('home.settings');
    }
    
    // 保存站点设置项
    public function saveSettings(Request $request){
        
        if(!Auth::guard('operate')->check()){
            return redirect('home');
        }
        
        $rules = [
            'site_name'     => 'required|min:2|max:64',
            'company_name'  => 'required|min:2|max:64',
            'company_addr'  => 'required|min:2|max:64',
            'company_phone' => 'required|min:2|max:64',
            // 'icp_number' => 'required|min:2|max:64',
        ];
        $messages = [
            'site_name.required'        => '请输入站点名称',
            'company_name.required'     => '请输入公司名称',
            'company_addr.required'     => '请输入格式地址',
            'company_phone.required'    => '请输入公司电话',
            // 'icp_number.required'    => '请输入ICP备案号'
        ];
        $response_msg = '';
        $ajax_result = $this->ajaxValidator($request->all(),$rules,$messages,$response_msg);
        
        
        if(!$response_msg){
            
            $post = $request->all();
            unset($post['_token']);
            
            $data = [];
            foreach($post as $k=>$v){
                if($v)
                {
                    $this->saveSettingByKey($k,'site',$v);   
                }
            }
            
            return $this->responseAjax('success',__('suda_lang::press.msg.success'),'self.refresh');
        }
        
        $url = 'setting/site';
        return $this->responseAjax('fail',$response_msg,'self.refresh');
    }
    
    // Logo
    public function logo(){
        
        $this->title('Logo设置');

        $logos_data = $this->getSettingByKey(['logo','favicon','share_image'],'site');
        
        foreach($logos_data as $k=>$v){
            if($v){
                $logos_data[$k] = Mediatable::where('mediatable_type','Gtd\Suda\Models\Setting')->where(['position'=>$k,'media_id'=>$v])->with('media')->first();
            }
        }
        
        $this->setData('logos',(object)$logos_data);
        
        $this->setMenu('setting','setting_system');
        return $this->display('home.logo');
    }
    
    public function saveLogo(Request $request){
        
        $roles = [];
        $messages = [];
        
        if($request->images){
            
            if(!array_key_exists('images',$request->images)){
                $roles['images'] = 'required';
                $messages['images.required'] = '请上传Logo';
            }
            
        }else{
            $roles['images'] = 'required';
            $messages['images.required'] = '请上传Logo';
        }
        
        $response_msg = '';
        $ajax_result = $this->ajaxValidator($request->all(),$roles,$messages,$response_msg);
        
        
        if(!$response_msg){
            
            $logos = $request->images;
            
            $logos = Arr::where($logos, function (int $value, string $key) {
                return in_array($key,['favicon','logo','share_image']);
            });
            
            $data = [];
            
            foreach($logos as $k=>$v){

                if(!$v){
                    $this->deleteSettingByKey($k,'site');
                    continue;
                }

                $this->saveSettingByKey($k,'site',$v,'image');

                //删除相应的数据
                Mediatable::where('mediatable_type','Gtd\Suda\Models\Setting')->whereIn('position',[$k])->delete();
                
                //保存新的数据
                $mediatableModel = new Mediatable;
                $mediatableModel->mediatable_type = 'Gtd\Suda\Models\Setting';
                $mediatableModel->mediatable_id = $v;
                $mediatableModel->media_id = $v;
                $mediatableModel->position = $k;
                $mediatableModel->save();
                
            }
            
            return $this->responseAjax('success',__('suda_lang::press.msg.success'),'self.refresh');
        }
        
        return $this->responseAjax('fail',$response_msg,'self.refresh');
    }
    
    // 设置控制台登录
    public function dashaboardLogin(){

        $options = [
            'login_page',
            'dashboard_apps', 'show_breadcrumb',
            'dashboard_logo', 'dashboard_login_logo_select',
            'dashboard_login_logo', 'loginbox', 'login_color'
        ];
        //policy 判断
        $this->gate('setting.setting_system',app(Setting::class));
        
        $this->title(trans('suda_lang::press.dashboard_info'));
        
        

        $datas = $this->getSettingByKey($options,'dashboard');
        
        
        if(!isset($datas['dashboard_login_logo']) || empty($datas['dashboard_login_logo']))
        {
            $datas['dashboard_login_logo'] = null;
        }

        if(!isset($datas['dashboard_login_logo_select']) || empty($datas['dashboard_login_logo_select']))
        {
            if(!$datas['dashboard_login_logo'])
            {
                $datas['dashboard_login_logo_select'] = 'boat';
            }else{
                $datas['dashboard_login_logo_select'] = 'customize';
            }
            
        }
        
        if(!array_key_exists('dashboard_apps',$datas)){
            $datas['dashboard_apps'] = ['start'=>'on','welcome'=>'on','quickin'=>'on'];
        }
        
        $this->setData('settings',$datas);
        
        $this->setMenu('setting','setting_system');
        
        return $this->display('home.dashboard_login');
    }
    
    public function saveDashaboardLogin(Request $request){
        
        $login_page = $request->login_page?$request->login_page:'';

        $this->saveSettingByKey('login_page','dashboard',$login_page);

        //颜色配置
        $login_color = $request->login_color?$request->login_color:'';

        $this->saveSettingByKey('login_color','dashboard',$login_color);

        
        $dashboard_apps = $request->dashboard_apps?$request->dashboard_apps:[];

        $this->saveSettingByKey('dashboard_apps','dashboard',$dashboard_apps,'serialize');


        $show_breadcrumb = $request->show_breadcrumb?$request->show_breadcrumb:0;

        $this->saveSettingByKey('show_breadcrumb','dashboard',$show_breadcrumb);


        $loginbox = $request->loginbox?$request->loginbox:'light';

        $this->saveSettingByKey('loginbox','dashboard',$loginbox);
        
        
        $dashboard_logos = false;
        if($request->images && isset($request->images['dashboard_logo'])){
            $dashboard_logos = $request->images['dashboard_logo'];
        }
        
        if($dashboard_logos)
        {

            Mediatable::where('mediatable_type','Gtd\Suda\Models\Setting')->where('position','dashboard_logo')->whereNotIn('media_id',[$dashboard_logos])->delete();
            
            $this->saveSettingByKey('dashboard_logo','dashboard',$dashboard_logos,'image');

            $mediatableModel = new Mediatable;
        
            $mediatableModel->mediatable_type = 'Gtd\Suda\Models\Setting';
            $mediatableModel->mediatable_id = 1;
            $mediatableModel->media_id = $dashboard_logos;
            $mediatableModel->position = 'dashboard_logo';
            $mediatableModel->save();
            
        } else {
            $this->saveSettingByKey('dashboard_logo','dashboard',0,'image');
            
            Mediatable::where('mediatable_type','Gtd\Suda\Models\Setting')->where('position','dashboard_logo')->delete();
        }

        //设置登录图片

        $select = $request->dashboard_login_logo_select?$request->dashboard_login_logo_select:'boat';
        if($request->custom_dashboard_login_logo)
        {
            $select = 'customize';
        }else{
            if($select == 'customize')
            {
                $select = 'boat';
            }
        }

        $this->saveSettingByKey('dashboard_login_logo_select','dashboard',$select);

        $dashboard_login_logo = false;
        if($request->images && isset($request->images['dashboard_login_logo'])){
            $dashboard_login_logo = $request->images['dashboard_login_logo'];
        }

        if($dashboard_login_logo){
            $this->saveSettingByKey('dashboard_login_logo','dashboard',$dashboard_login_logo,'image');
        }
        
        $url = 'setting/dashboardinfo';
        return $this->responseAjax('success',__('suda_lang::press.msg.success'),$url);
        
    }
    
    // 浏览设置
    public function setBrowser(){
        //policy 判断
        $this->gate('setting.setting_system',app(Setting::class));
        
        $this->title('浏览设置');
        
        $settings = $this->getSettingByKey('default_page','site');

        
        
        if($settings){
            
            if($settings['page_type'] == 'single_page'){
            
                $page_id = intval($settings['page_value']);
                $page = Page::where(['id'=>$page_id])->first();
                $this->setData('page',$page);
            
            }
        }else{
            $settings = ['page_type'=>'default_page','page_value'=>''];
        }

        $this->setData('settings',$settings);
        
        $this->setMenu('setting','setting_system');
        
        return $this->display('home.setting_browser');
    }
    
    public function saveBrowser(Request $request){
        
        $default_page = $request->default_page;
        
        $page_value = '';
        switch($default_page){
            
            case 'default_page':
                $page_value = ['page_type'=>'default_page','page_value'=>''];
            break;
            case 'single_page':
                if(!$request->default_page_id){
                    return $this->responseAjax('fail','请选择一个页面作为默认访问页面');
                }
                $page_value = ['page_type'=>'single_page','page_value'=>$request->default_page_id];
            break;
            
            case 'link_page':
                if(!$request->default_page_url){
                    return $this->responseAjax('fail','请选择一个页面作为默认访问页面');
                }
                $page_value = ['page_type'=>'link_page','page_value'=>$request->default_page_url];
            break;
            
        }

        $this->saveSettingByKey('default_page','site',$page_value,'serialize');
        $url = 'setting/browser';
        return $this->responseAjax('success',__('suda_lang::press.msg.success'),$url);
        
    }
    
    // SEO
    public function setSeo()
    {
        $this->setMenu('setting','setting_system');
        $this->gate('setting.setting_system',app(Setting::class));
        
        $this->title('SEO设置');

        $settings = $this->getSettingByKey('seo','site');
        
        if(!$settings){
            $settings = ['title'=>'','keywords'=>'','description'=>''];
        }
        
        $this->setData('settings',$settings);
        
        return $this->display('home.setting_seo');
    }
    
    
    public function saveSeo(Request $request){
        
        $data_value = [
            'title'         => $request->title,
            'keywords'      => $request->keywords,
            'description'   => $request->description
        ];

        $this->saveSettingByKey('seo','site',$data_value,'serialize');
        return $this->responseAjax('success',__('suda_lang::press.msg.success'),'self.refresh');
        
    }
    
}
