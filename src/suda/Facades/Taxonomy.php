<?php namespace Gtd\Suda\Facades;

use Illuminate\Support\Facades\Facade;

class Taxonomy extends Facade
{
     /**
     * @inheritdoc
      */
     protected static function getFacadeAccessor(): string
     {
          return 'taxonomies';
     }
}