<?php

namespace Gtd\Suda\Models;

use Cviebrock\EloquentSluggable\Sluggable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Class Term
 */
class Term extends Model
{
    use Sluggable;
    use SoftDeletes;

    protected $table = 'terms';
    
    protected $guarded = [];
    
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'name',
        'slug',
        'taxonomy',
    ];

    /**
     * @inheritdoc
     */
    protected $dates = ['deleted_at'];

    /**
     * @inheritdoc
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = 'terms';
    }

    /**
     * @inheritdoc
     */
    public function sluggable():array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function taxable() {
        return $this->morphMany(Taxable::class, 'taxable');
    }

    /**
     * Get the taxonomies this term belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taxonomies() {
        return $this->hasMany(Taxonomy::class);
    }

    /**
     * Get display name.
     *
     * @param  string  $locale
     * @param  int     $limit
     * @return mixed
     */
    public function getDisplayName($locale = '', $limit = 0)
    {
        $locale = $locale ?$locale: app()->getLocale();

        $property_with_locale = $locale === 'en' ? "name" : "name_$locale";

        $name = property_exists($this, $property_with_locale) ? $this->{$property_with_locale} : $this->name;

        return $limit > 0 ? Str::limit($name, $limit) : $name;
    }

    /**
     * Get route parameters.
     *
     * @param  string  $taxonomy
     * @return mixed
     */
    public function getRouteParameters($taxonomy)
    {
        $taxonomy = Taxonomy::taxonomy($taxonomy)
                            ->term($this->name)
                            ->with('parent')
                            ->first();

        $parameters = $this->getParentSlugs($taxonomy);

        array_push($parameters, $taxonomy->taxonomy);

        return array_reverse($parameters);
    }

    /**
     * Get slugs of parent terms.
     *
     * @param  Taxonomy  $taxonomy
     * @param  array     $parameters
     * @return array
     */
    function getParentSlugs(Taxonomy $taxonomy, $parameters = [])
    {
        array_push($parameters, $taxonomy->term->slug);

        if (($parents = $taxonomy->parent()) && ($parent = $parents->first()))
            return $this->getParentSlugs($parent, $parameters);

        return $parameters;
    }
}
