<?php

use Illuminate\Database\Seeder;
use Gtd\Suda\Traits\Seedable;

class SudaDatabaseSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = __DIR__.'/';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seed('SettingsTableSeeder');
        $this->seed('MenusTableSeeder');
        $this->seed('MenuItemsTableSeeder');
        $this->seed('TaxonomyTableSeeder');
        
        //默认文章的封面图
        $this->seed('MediasTableSeeder');
        $this->seed('PagesTableSeeder');
    }
}
