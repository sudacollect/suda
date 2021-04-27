<?php
namespace Gtd\Suda\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taxonomy extends Model
{
    use SoftDeletes;
    
    protected $table = 'taxonomies';
    
    protected $guarded = [];
    
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'term_id',
        'taxonomy',
        'taxonomy_title',
        'desc',
        'parent',
        'sort',
        'toggle',
        'logo',
        'color',
    ];

    /**
     * @inheritdoc
     */
    protected $dates = ['deleted_at'];


    protected $append = ['taxables_count'];

    /**
     * @inheritdoc
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = 'taxonomies';
    }

    /**
     * Get the term this taxonomy belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function term() {
        return $this->belongsTo(Term::class)->withTrashed();
    }
    
    /**
     * Get the parent taxonomy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Taxonomy::class, 'parent');
    }
    

    /**
     * Get the children taxonomies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Taxonomy::class, 'parent')->orderBy('sort');
    }

    /**
     * Get the parent taxonomy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parents()
    {
        return $this->belongsTo(Taxonomy::class, 'parent')->with(['parents' => function ($q) {
                $q->with('term')->orderBy('sort');
            }]);
    }
    

    /**
     * Get the children taxonomies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childrens()
    {
        return $this->hasMany(Taxonomy::class, 'parent')->with(['childrens' => function ($q) {
                $q->with('term');
            }]);
    }

    public function getTaxablesCountAttribute()
    {
        return \Gtd\Suda\Models\Taxable::where(['taxonomy_id'=>$this->id])->count();
    }
    
    
    public function lists($taxonomy){
        
        $taxonomies = static::where('parent',0)
            ->where('taxonomy',$taxonomy)
            ->with(['childrens' => function ($q) {
                $q->with('term');
            }])
            ->with('term')
            ->orderBy('sort')
            ->get();
            
            return $taxonomies;
        
    }

    /**
     * An example for a related posts model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function posts()
    {
        return $this->morphedByMany('App\Models\Posts\Post', 'taxable', 'taxables');
    }
    
    public function pages()
    {
        return $this->morphedByMany('Gtd\Suda\Models\Page', 'taxable', 'taxables');
    }
    
    public function articles()
    {
        return $this->morphedByMany('Gtd\Suda\Models\Article', 'taxable', 'taxables');
    }

    /**
     * Scope taxonomies.
     *
     * @param  object  $query
     * @param  string  $taxonomy
     * @return mixed
     */
    public function scopeTaxonomy($query, $taxonomy)
    {
        return $query->where('taxonomy', $taxonomy);
    }

    /**
     * Scope terms.
     *
     * @param  object  $query
     * @param  string  $term
     * @param  string  $taxonomy
     * @return mixed
     */
    public function scopeTerm($query, $term, $taxonomy = 'major')
    {
        return $query->whereHas('term', function($q) use($term, $taxonomy) {
            $q->where('name', $term);
        });
    }

    /**
     * A simple search scope.
     *
     * @param  object  $query
     * @param  string  $searchTerm
     * @param  string  $taxonomy
     * @return mixed
     */
    public function scopeSearch($query, $searchTerm, $taxonomy = 'major')
    {
        return $query->whereHas('term', function($q) use($searchTerm, $taxonomy) {
            $q->where('name', 'like', '%'. $searchTerm .'%');
        });
    }

    public function getLogoAttribute($value){

        if($value)
        {
            return Media::where(['id'=>$value])->first();
        }
        return false;
    }
}