<?php

namespace Gtd\Suda\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface TaxonomyInterface
{
     public function taxonomies(): MorphToMany;
}