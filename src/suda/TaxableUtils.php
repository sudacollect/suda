<?php

namespace Gtd\Suda;

use Gtd\Suda\Models\Taxonomy;
use Gtd\Suda\Models\Term;

/**
 * Class TaxableUtils
 */
class TaxableUtils
{
    /**
     * @param mixed    $terms
     * @param string   $taxonomy
     * @param integer  $parent
     * @param integer  $order
     */
    public function createTaxables($terms, $taxonomy, $parent = 0, $order = 0)
     {
          $terms = $this->makeTermsArray($terms);

          $this->createTerms($terms,$taxonomy);
          $this->createTaxonomies($terms, $taxonomy, $parent, $order);
     }

    /**
     * @param array $terms
     */
    public static function createTerms(array $terms,$taxonomy)
     {
          if (count($terms) > 0) {
               $found = Term::whereIn('name', $terms)->where('taxonomy',$taxonomy)->pluck('name')->all();

               if (! is_array($found)){
                   $found = [];
               }
               
               foreach (array_diff($terms, $found) as $name) {
                    
                    if (Term::where('name', $name)->where('taxonomy',$taxonomy)->first()){
                        continue;
                    }
                    
                    $term = new Term;
                    $term->name = $name;
                    $term->taxonomy = $taxonomy;
                    $term->save();
               }
          }
     }

    /**
     * @param array    $terms
     * @param string   $taxonomy
     * @param integer  $parent
     * @param integer  $order
     */
    public static function createTaxonomies(array $terms, $taxonomy, $parent = 0, $order = 0)
     {
          if (count($terms) > 0) {
               // only keep terms with existing entries in terms table
               $terms = Term::whereIn('name', $terms)->pluck('name')->all();

               // create taxonomy entries for given terms
               
               foreach ($terms as $term) {
                    $term_id = Term::where('name', $term)->where('taxonomy',$taxonomy)->first()->id;
                    
                    if (Taxonomy::withTrashed()->where('taxonomy', $taxonomy)->where('term_id', $term_id)->where('parent', $parent)->first()){
                        continue;
                    }
                    
                    $model = new Taxonomy;
                    $model->taxonomy = $taxonomy;
                    $model->term_id  = $term_id;
                    $model->parent   = $parent;
                    $model->sort     = $order;
                    $model->save();
               }
          }
     }

     /**
      * @param  string|array  $terms
      * @return array
      */
     public static function makeTermsArray($terms) {
          if (is_array($terms)) {
               return $terms;
          } else if (is_string($terms)) {
               return explode('|', $terms);
          }

          return (array) $terms;
     }
}