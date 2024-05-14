<?php

use Illuminate\Database\Seeder;
use Gtd\Suda\Models\Operate;

class OperateTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $setting = $this->findUsername('admin');
        if (!$setting->exists) {
            $setting->fill([
                'phone'             => '13012345678',//China
                'email'             => 'admin@suda.run',
                'password'          => '$2y$12$K.T.t9gd6IcDtKfT8fPspOqfcnGJGBkZGe1iobeTlVD0VQhZtufrW',
                'is_company'        => '0',
                'remember_token'    => '',
                'created_at'        => date('Y-m-d H:i:s'), //'2018-01-01 12:05:24'
                'updated_at'        => date('Y-m-d H:i:s'), //'2018-06-01 18:33:16'
                'superadmin'        => 1,
                'organization_id'   => 0,
                'salt'              => 'AAgb73',
                'enable'            => 1,
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
    protected function findUsername($key)
    {
        return Operate::firstOrNew(['username' => $key]);
    }
}
