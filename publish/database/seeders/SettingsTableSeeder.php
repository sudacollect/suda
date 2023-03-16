<?php

use Illuminate\Database\Seeder;
use Gtd\Suda\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $setting = $this->findSetting('site_name');
        
        if (!$setting->exists) {
            $setting->fill([
                'values'        => 'Suda',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'site',
            ])->save();
        }
        
        $setting = $this->findSetting('site_domain');
        if (!$setting->exists) {
            $setting->fill([
                'values'        => 'yourdomain.com',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'site',
            ])->save();
        }

        $setting = $this->findSetting('site_close');
        if (!$setting->exists) {
            $setting->fill([
                'values'        => '0',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'site',
            ])->save();
        }

        $setting = $this->findSetting('share_image');
        if (!$setting->exists) {
            $setting->fill([
                'values'        => '',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'site',
            ])->save();
        }

        $setting = $this->findSetting('logo');
        if (!$setting->exists) {
            $setting->fill([
                'values'        => '',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'site',
            ])->save();
        }

        // $setting = $this->findSetting('icp_number');
        // if (!$setting->exists) {
        //     $setting->fill([
        //         'values'        => '沪ICP-2018123456',
        //         'type'         => 'text',
        //         'order'        => 1,
        //         'group'        => 'site',
        //     ])->save();
        // }

        $setting = $this->findSetting('company_phone');
        if (!$setting->exists) {
            $setting->fill([
                'values'        => '021-88888888',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'site',
            ])->save();
        }

        $setting = $this->findSetting('company_name');
        if (!$setting->exists) {
            $setting->fill([
                'values'        => 'Company',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'site',
            ])->save();
        }

        $setting = $this->findSetting('company_addr');
        if (!$setting->exists) {
            $setting->fill([
                'values'        => 'ShangHai',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'site',
            ])->save();
        }
    }

    /**
     * [setting description].
     *
     * @param [type] $key [description]
     *
     * @return [type] [description]
     */
    protected function findSetting($key)
    {
        return Setting::firstOrNew(['key' => $key]);
    }
}
