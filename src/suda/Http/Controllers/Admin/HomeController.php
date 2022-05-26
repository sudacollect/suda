<?php

namespace Gtd\Suda\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;
use Response;
use Illuminate\Support\Facades\Cache;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;

use Gtd\Suda\Models\Page;
use Gtd\Suda\Models\Setting;
use Gtd\Suda\Models\Media;
use Gtd\Suda\Models\Mediatable;

use Gtd\Suda\Models\Operate;
use Gtd\Suda\Models\Organization;
use Gtd\Suda\Certificate;

use GuzzleHttp\Client as HttpClient;


class HomeController extends DashboardController
{
    public $view_in_suda = true;
       
    public function index(Request $request)
    {
        $this->title(__('suda_lang::press.dashboard'));
        $this->setMenu('dashboard');
        // //读取服务器信息
        // $servers = [];
        // $servers['remote_addr'] = $request->server('REMOTE_ADDR');
        // $servers['server_software'] = $request->server('SERVER_SOFTWARE');
        
        // $this->setData('servers',$servers);
        if($this->user->user_role==2)
        {
            return redirect()->to(admin_url('entry/extensions'));
        }

        // $this->setData('current_navi','商品');
        
        // return view('view_suda::widgets.news');
        return $this->display('dashboard');
    }

    public function getCgiinfo()
    {
        phpinfo();
        exit;
    }

    //查看授权信息
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
    
    //查看更新信息
    public function updateInfo(){
        
        $this->title('产品版本信息');
        
        return $this->display('updateinfo');
    }
    

    //暂时没用请求最新版本
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
                
                return Response::make('<h5><i class="zly-gift-card" style="color:#FF6C6C;"></i>&nbsp;最新版本 v'.$version_result->version.'</h5>', 200);
                
            }
        }
        
    }
    
    //查看服务器信息
    public function serverInfo(){
        
        $this->title('服务器信息');
        
        return $this->display('serverinfo');
    }
    
    //输出phpinfo
    public function getPhpInfo(){
        
        exit(phpinfo());
    }
    
    //站点设置项
    public function settings(){
        
        //policy 判断
        $this->gate('setting.view',app(Setting::class));
        
        $this->title(__('suda_lang::press.basic_info'));
        
        $settings = Setting::where([])->get();
        
        $settings_data = [];
        
        foreach((array)$settings as $k=>$v){
            if($v){
                foreach($v as $key=>$values){
                    $settings_data[$values->key] = $values->values;
                }
            }
        }
        
        $this->setData('settings',(object)$settings_data);
        
        $this->setMenu('setting','setting_system');
        
        return $this->display('home.settings');
    }
    
    //保存站点设置项
    public function saveSettings(Request $request){
        
        if(!Auth::guard('operate')->check()){
            return redirect('home');
        }
        
        $roles = [
            'site_name' => 'required|min:2|max:64',
            'company_name' => 'required|min:2|max:64',
            'company_addr' => 'required|min:2|max:64',
            'company_phone' => 'required|min:2|max:64',
            // 'icp_number' => 'required|min:2|max:64',
        ];
        $messages = [
            'site_name.required'=>'请输入站点名称',
            'company_name.required'=>'请输入公司名称',
            'company_addr.required'=>'请输入格式地址',
            'company_phone.required'=>'请输入公司电话',
            // 'icp_number.required'=>'请输入ICP备案号'
        ];
        $response_msg = '';
        $ajax_result = $this->ajaxValidator($request->all(),$roles,$messages,$response_msg);
        
        
        if(!$response_msg){
            
            $settingModel = new Setting;
            $post = $request->all();
            unset($post['_token']);
            
            $data = [];
            foreach($post as $k=>$v){
                if($v)
                {
                    if($k=='site_name'){
                        $site_name = $v;
                    }
                    $data = [
                        'key'=>$k,
                        'values'=>$v,
                        'type'=>'site'
                    ];
                    
                    if($first = $settingModel->where(['key'=>$k])->first()){
                        $settingModel->where(['key'=>$k])->update($data);
                    }else{
                        $settingModel->insert($data);
                    }
                }
                
            }
            // $settingModel->fill($data);
            
            Setting::updateSettings();
            //更新成功
            $url = 'setting/site';
            return $this->responseAjax('success','保存成功',$url);
        }
        
        $url = 'setting/site';
        return $this->responseAjax('fail',$response_msg,$url);
    }
    
    public function logo(){
        
        $this->title('Logo设置');
        
        $logos = Setting::whereIn('key',['logo','favicon','share_image'])->get();
        
        $logos_data = [];
        
        foreach((array)$logos as $k=>$v){
            if($v){
                foreach($v as $key=>$values){
                    $logos_data[$values->key] = Mediatable::where('mediatable_type','Gtd\Suda\Models\Setting')->where(['position'=>$values->key,'media_id'=>$values->values])->with('media')->first();
                }
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
            $logos = array_merge(['favicon'=>'','logo'=>'','share_image'=>''],$logos);
            $settingModel = new Setting;
            $data = [];
            
            foreach($logos as $k=>$v){

                if(!$v){
                    $settingModel->where(['key'=>$k])->delete();
                    continue;
                }
                
                $data = [
                    'group'=>'site',
                    'key'=>$k,
                    'values'=>$v,
                    'type'=>'image'
                ];
                
                if($first = $settingModel->where(['group'=>'site'])->where(['key'=>$k])->first()){
                    $settingModel->where(['key'=>$k])->update($data);
                }else{
                    $settingModel->insert($data);
                }

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

            Setting::updateSettings();
            
            //更新成功
            $url = 'setting/logo';
            return $this->responseAjax('success','保存成功',$url);
        }
        
        $url = 'setting/logo';
        return $this->responseAjax('fail',$response_msg,$url);
    }
    
    //设置控制台登录
    public function dashaboardLogin(){

        $options = [
            'login_page',
            'dashboard_apps', 'show_breadcrumb',
            'dashboard_logo', 'dashboard_login_logo_select',
            'dashboard_login_logo', 'loginbox', 'login_color'
        ];
        //policy 判断
        $this->gate('setting.view',app(Setting::class));
        
        $this->title(trans('suda_lang::press.dashboard_info'));
        
        $settings = Setting::where(['group'=>'dashboard'])->whereIn('key',$options)->get();
        
        $datas = [];
        foreach($settings as $k=>$setting){
            
            $datas[$setting->key] = $setting;
            if($setting->key=='dashboard_apps'){
                $datas[$setting->key] = unserialize($setting->values);
            }
            if($setting->key=='show_breadcrumb'){
                $datas[$setting->key] = $setting->values;
            }
            if($setting->key=='dashboard_login_logo_select'){
                $datas[$setting->key] = $setting->values;
            }
            if($setting->key=='dashboard_login_logo' && !empty($setting->values)){
                $datas[$setting->key] = Media::where('id',$setting->values)->first();
            }
            if($setting->key=='dashboard_logo'){
                
                $datas[$setting->key] = Mediatable::where('mediatable_type','Gtd\Suda\Models\Setting')->where(['position'=>'dashboard_logo','media_id'=>$setting->values])->with('media')->first();
                
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
        
        $data = [
            'key'=>'login_page',
            'group'=>'dashboard',
            'type'=>'text',
            'values'=>$login_page,
        ];
        
        $settingModel = new Setting;
        
        if($first = Setting::where(['key'=>'login_page','group'=>'dashboard'])->first())
        {
            Setting::where(['key'=>'login_page','group'=>'dashboard'])->update($data);
        }
        else
        {
            $settingModel->fill($data)->save();
        }

        //颜色配置
        $login_color = $request->login_color?$request->login_color:'';
        
        $data = [
            'key'=>'login_color',
            'group'=>'dashboard',
            'type'=>'text',
            'values'=>$login_color,
        ];
        
        $settingModel = new Setting;
        
        if($first = Setting::where(['key'=>'login_color','group'=>'dashboard'])->first())
        {
            Setting::where(['key'=>'login_color','group'=>'dashboard'])->update($data);
        }
        else
        {
            $settingModel->fill($data)->save();
        }

        
        
        
        
        
        $dashboard_apps = $request->dashboard_apps?$request->dashboard_apps:[];
        
        $data = [
            'key'=>'dashboard_apps',
            'group'=>'dashboard',
            'type'=>'text',
            'values'=>serialize($dashboard_apps),
        ];
        
        $settingModel = new Setting;
        
        if($first = Setting::where(['key'=>'dashboard_apps','group'=>'dashboard'])->first())
        {
            Setting::where(['key'=>'dashboard_apps','group'=>'dashboard'])->update($data);
        }
        else
        {
            $settingModel->fill($data)->save();
        }


        $show_breadcrumb = $request->show_breadcrumb?$request->show_breadcrumb:0;
        
        $data = [
            'key'=>'show_breadcrumb',
            'group'=>'dashboard',
            'type'=>'text',
            'values'=>$show_breadcrumb,
        ];
        
        $settingModel = new Setting;
        
        if($first = Setting::where(['key'=>'show_breadcrumb','group'=>'dashboard'])->first())
        {
            Setting::where(['key'=>'show_breadcrumb','group'=>'dashboard'])->update($data);
        }
        else
        {
            $settingModel->fill($data)->save();
        }


        $loginbox = $request->loginbox?$request->loginbox:'default';
        $data = [
            'key'=>'loginbox',
            'group'=>'dashboard',
            'type'=>'text',
            'values'=>$loginbox,
        ];
        
        $settingModel = new Setting;
        
        if($first = Setting::where(['key'=>'loginbox','group'=>'dashboard'])->first())
        {
            Setting::where(['key'=>'loginbox','group'=>'dashboard'])->update($data);
        }
        else
        {
            $settingModel->fill($data)->save();
        }
        
        
        $dashboard_logos = false;
        if($request->images && isset($request->images['dashboard_logo'])){
            $dashboard_logos = $request->images['dashboard_logo'];
        }
        
        
        if($dashboard_logos)
        {
            
            
            Mediatable::where('mediatable_type','Gtd\Suda\Models\Setting')->where('position','dashboard_logo')->whereNotIn('media_id',[$dashboard_logos])->delete();
        
            $mediatableModel = new Mediatable;
        
            $data = [];
            
            $data = [
                'group'=>'dashboard',
                'key'=>'dashboard_logo',
                'values'=>$dashboard_logos,
                'type'=>'image'
            ];
        
            if($first = $settingModel->where(['key'=>'dashboard_logo'])->first()){
                $settingModel->where(['key'=>'dashboard_logo'])->update($data);
            }else{
                $settingModel->insert($data);
            }
        
            $mediatableModel->mediatable_type = 'Gtd\Suda\Models\Setting';
            $mediatableModel->mediatable_id = 1;
            $mediatableModel->media_id = $dashboard_logos;
            $mediatableModel->position = 'dashboard_logo';
            $mediatableModel->save();
            
        } else {
            $data = [
                'group'=>'dashboard',
                'key'=>'dashboard_logo',
                'values'=>'',
                'type'=>'image'
            ];
        
            if($first = $settingModel->where(['key'=>'dashboard_logo'])->first()){
                $settingModel->where(['key'=>'dashboard_logo'])->update($data);
            }else{
                $settingModel->insert($data);
            }
            
            Mediatable::where('mediatable_type','Gtd\Suda\Models\Setting')->where('position','dashboard_logo')->delete();
        }

        //设置登录图片

        $dashboard_login_logo_select = $request->dashboard_login_logo_select;

        $data = [
            'group'=>'dashboard',
            'key'=>'dashboard_login_logo_select',
            'values'=>$dashboard_login_logo_select,
            'type'=>'text'
        ];
        
        $settingModel = new Setting;

        if($first = $settingModel->where(['group'=>'dashboard','key'=>'dashboard_login_logo_select'])->first()){
            $settingModel->where(['group'=>'dashboard','key'=>'dashboard_login_logo_select'])->update($data);
        }else{
            $settingModel->insert($data);
        }

        $dashboard_login_logo = false;
        if($request->images && isset($request->images['dashboard_login_logo'])){
            $dashboard_login_logo = $request->images['dashboard_login_logo'];
        }

        if($dashboard_login_logo){
            $data = [
                'group'=>'dashboard',
                'key'=>'dashboard_login_logo',
                'values'=>$dashboard_login_logo,
                'type'=>'image'
            ];
            
            $settingModel = new Setting;
    
            if($first = $settingModel->where(['group'=>'dashboard','key'=>'dashboard_login_logo'])->first()){
                $settingModel->where(['group'=>'dashboard','key'=>'dashboard_login_logo'])->update($data);
            }else{
                $settingModel->insert($data);
            }
        }
        
        
        Setting::updateSettings();
        //更新成功
        $url = 'setting/dashboard_info';
        return $this->responseAjax('success','保存成功',$url);
        
    }
    
    //浏览设置
    public function setBrowser(){
        //policy 判断
        $this->gate('setting.view',app(Setting::class));
        
        $this->title('浏览设置');
        
        $settings = Setting::where(['key'=>'default_page','group'=>'site'])->first();
        
        if($settings){
            if(!$settings->values){
                $settings->values = serialize(['page_type'=>'default_page','page_value'=>'']);
            }
            $settings->values = unserialize($settings->values);
        
            if($settings->values['page_type']=='single_page'){
            
                $page_id = intval($settings->values['page_value']);
                $page = Page::where(['id'=>$page_id])->first();
                $this->setData('page',$page);
            
            }
        }else{
            $settings = new \stdClass();
            $settings->values = ['page_type'=>'default_page','page_value'=>''];
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
                $page_value = serialize(['page_type'=>'default_page','page_value'=>'']);
            break;
            case 'single_page':
                if(!$request->default_page_id){
                    return $this->responseAjax('fail','请选择一个页面作为默认访问页面');
                }
                $page_value = serialize(['page_type'=>'single_page','page_value'=>$request->default_page_id]);
            break;
            
            case 'link_page':
                if(!$request->default_page_url){
                    return $this->responseAjax('fail','请选择一个页面作为默认访问页面');
                }
                $page_value = serialize(['page_type'=>'link_page','page_value'=>$request->default_page_url]);
            break;
            
        }
        
        $data = [
            'key'=>'default_page',
            'group'=>'site',
            'type'=>'text',
            'values'=>$page_value,
        ];
        
        $settingModel = new Setting;
        
        if($first = Setting::where(['key'=>'default_page','group'=>'site'])->first()){
            Setting::where(['key'=>'default_page','group'=>'site'])->update($data);
        }else{
            $settingModel->fill($data)->save();
        }
        
        Setting::updateSettings();
        //更新成功
        $url = 'setting/browser';
        return $this->responseAjax('success','保存成功',$url);
        
    }
    
    
    public function setSeo(){
        //policy 判断
        $this->gate('setting.view',app(Setting::class));
        
        $this->title('SEO设置');
        
        $settings = Setting::where(['key'=>'seo','group'=>'site'])->first();
        
        if($settings){
            if(!$settings->values){
                $settings->values = serialize(['title'=>'','keywords'=>'','description'=>'']);
            }
            $settings->values = unserialize($settings->values);
            
        }else{
            $settings = new \stdClass();
            $settings->values = ['title'=>'','keywords'=>'','description'=>''];
        }
        
        $this->setData('settings',$settings);
        
        $this->setMenu('setting','setting_system');
        
        return $this->display('home.setting_seo');
    }
    
    
    public function saveSeo(Request $request){
        
        $data_value = serialize(['title'=>$request->title,'keywords'=>$request->keywords,'description'=>$request->description]);
        
        $data = [
            'key'=>'seo',
            'group'=>'site',
            'type'=>'text',
            'values'=>$data_value,
        ];
        
        $settingModel = new Setting;
        
        if($first = Setting::where(['key'=>'seo','group'=>'site'])->first()){
            Setting::where(['key'=>'seo','group'=>'site'])->update($data);
        }else{
            $settingModel->fill($data)->save();
        }
        
        Setting::updateSettings();
        
        //更新成功
        $url = 'setting/seo';
        return $this->responseAjax('success','保存成功',$url);
        
    }
    
    
    public function startTheme(){
        
        // $this->setData('modal_title','设置主题');
        $this->setData('modal_icon_class','zly-medal');
        
        return $this->display('home.start_theme');
        
    }
    
}
