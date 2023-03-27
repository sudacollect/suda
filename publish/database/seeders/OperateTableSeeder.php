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
                'phone'          => '13012345678',
                'email'        => 'admin@suda.run',
                'password'         => '$2y$10$Sp4f60kQVzrJhetHhhvGiefVfVTzcUJPfmwjz1T4VD4m80lgDbzFK',
                'is_company'        => '0',
                'remember_token'        => 'nQPalCx6O9JOWhTFVhN7FcmokpofKOSNAVUsTax6u4B3994wGASA6FlhfcQa',
                'created_at'        => date('Y-m-d H:i:s'), //'2018-01-01 12:05:24'
                'updated_at'        => date('Y-m-d H:i:s'), //'2018-06-01 18:33:16'
                'superadmin'        => 1,
                'organization_id'        => 0,
                'salt'        => 'AAgb73',
                'enable'        => 1,
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
