<?php

use Illuminate\Database\Seeder;
use Gtd\Suda\Models\Page;

class PagesTableSeeder extends Seeder
{
    public function run()
    {
        $page = Page::firstOrNew([
            'title'=>__('suda_lang::seeds.page.example_title'),
        ]);
        if (!$page->exists) {
            $page->title = __('suda_lang::seeds.page.example_title');
            $page->hero_image = 1;
            $page->operate_id = 1;
            $page->sort = 0;
            $page->need_login = 0;
            $page->content = __('suda_lang::seeds.page.example_content');
            $page->save();
        }
    }
}
