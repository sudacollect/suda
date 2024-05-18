<?php

namespace Gtd\Suda\Components\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Gtd\Suda\Models\Setting;
use Gtd\Suda\Services\SettingService;

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
        'settings.site_name.required'        => '请填写站点名称',
        'settings.company_name.required'     => '请填写公司名称',
        'settings.company_addr.required'     => '请填写格式地址',
        'settings.company_phone.required'    => '请填写公司电话',
        // 'icp_number.required'    => '请填写ICP备案号'
    ];
  
    public function mount()
    {
        $keys = [
            'site_name',
            'site_domain',
            'site_status',
            'share_image',
            'logo',
            'company_phone',
            'company_name',
            'company_addr',
            'icp_number',
        ];

        $settings = Setting::where(['group'=>'site'])->whereIn('key',$keys)->get();
        
        $settings_data = [];
        
        foreach($settings as $k=>$v){
            $settings_data[$v->key] = $v->values;
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
            if($k=='site_name'){
                $site_name = $v;
            }
            $data = [
                'group'     => 'site',
                'key'       => $k,
                'values'    => $v,
                'type'      => 'text'
            ];
            
            if($first = $settingModel->where(['key'=>$k,'group'=>'site'])->first()){
                $settingModel->where(['key'=>$k,'group'=>'site'])->update($data);
            }else{
                $settingModel->insert($data);
            }
        }
        
        (new SettingService)->updateCache();

        $this->error_msg = __('suda_lang::press.msg.success');
        $this->dispatch('errorBox',['msg'=>__('suda_lang::press.msg.success')]);
    }
    
    public function render()
    {
        $view = 'view_suda::admin.component.livewire.setting';

        return view($view);
        
    }
}
