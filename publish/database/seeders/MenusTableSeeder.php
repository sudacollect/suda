<?php

use Illuminate\Database\Seeder;
use Gtd\Suda\Models\Menu;

class MenusTableSeeder extends Seeder
{
    public function run()
    {
        Menu::firstOrCreate([
            'name' => 'suda',
        ]);
    }
}
