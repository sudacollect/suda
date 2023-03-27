<?php

namespace Gtd\Suda\Components;

use Livewire\Component;
use Gtd\Suda\Models\Setting;
use Illuminate\Support\Facades\Validator;

class SettingComponent extends Component
{
    public $settings;
    public $error_msg = '';

    protected $rules = [
        'settings.site_name'     => 'required|min:2|max:64',
        'settings.company_name'  => 'required|min:2|max:64',
        'settings.company_addr'  => 'required|min:2|max:64',
        'settings.company_phone' => 'required|min:2|max:64',
        // 'icp_number' => 'required|min:2|max:64',
    ];
    protected $messages = [
        'settings.site_name.required'        => '请输入站点名称',
        'settings.company_name.required'     => '请输入公司名称',
        'settings.company_addr.required'     => '请输入格式地址',
        'settings.company_phone.required'    => '请输入公司电话',
        // 'icp_number.required'    => '请输入ICP备案号'
    ];
  
    public function mount()
    {


        $settings = Setting::where(['group'=>'site'])->get();
        
        $settings_data = [];
        
        foreach((array)$settings as $k=>$v){
            if($v){
                foreach($v as $key=>$values){
                    $settings_data[$values->key] = $values->values;
                }
            }
        }
        
        $this->settings = $settings_data;
        $this->error_msg = '';
    }

    public function closeBox()
    {
        $this->error_msg = '';
    }

    public function submit()
    {

        $this->validate();

        $settingModel = new Setting;
        
        foreach($this->settings as $k=>$v){
            if($v)
            {
                if($k=='site_name'){
                    $site_name = $v;
                }
                $data = [
                    'group'=>'site',
                    'key'=>$k,
                    'values'=>$v,
                    'type'=>'text'
                ];
                
                if($first = $settingModel->where(['key'=>$k])->first()){
                    $settingModel->where(['key'=>$k])->update($data);
                }else{
                    $settingModel->insert($data);
                }
            }
        }
        
        Setting::updateSettings();

        $this->error_msg = '保存成功';
        $this->dispatchBrowserEvent('errorBox',['msg'=>'保存成功']);
    }
    
    public function render()
    {
        $view = 'view_suda::admin.component.livewire.setting';

        return view($view);
        
    }
}
