<?php

use Illuminate\Database\Seeder;
use Gtd\Suda\Models\Taxonomy;
use Gtd\Suda\Models\Term;

class TaxonomyTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $post_tag = Term::firstOrNew(['name' => 'suda','taxonomy'=>'post_tag']);
        
        if (!$post_tag->exists) {
            $post_tag->fill([
                'name'        => 'suda',
                'slug'        => 'suda',
                'taxonomy'         => 'post_tag',
            ])->save();
        }
        
        $post_tag = Term::firstOrNew(['name' => 'suda','taxonomy'=>'post_tag']);
        $post_tag_taxonomy = Taxonomy::firstOrNew(['term_id' => $post_tag->id,'taxonomy'=>'post_tag']);
        if (!$post_tag_taxonomy->exists) {
            $post_tag_taxonomy->fill([
                'term_id'        => $post_tag->id,
                'taxonomy'         => 'post_tag',
            ])->save();
        }
        
        $post_category = Term::firstOrNew(['name' => '默认分类','taxonomy'=>'post_category']);
        
        if (!$post_category->exists) {
            $post_category->fill([
                'name'        => '默认分类',
                'slug'        => 'default',
                'taxonomy'         => 'post_category',
            ])->save();
        }
        
        $post_category = Term::firstOrNew(['name' => '默认分类','taxonomy'=>'post_category']);
        $post_category_taxonomy = Taxonomy::firstOrNew(['term_id' => $post_category->id,'taxonomy'=>'post_category']);
        if (!$post_category_taxonomy->exists) {
            $post_category_taxonomy->fill([
                'term_id'        => $post_category->id,
                'taxonomy'         => 'post_category',
            ])->save();
        }

    }

}
