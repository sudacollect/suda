<?php
namespace Gtd\Suda\Models;

use Illuminate\Database\Eloquent\Model;
use Gtd\Suda\Models\Media;

class Mediatable extends Model
{
    protected $table = 'mediatables';
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'media_id',
        'mediatable_id',
        'mediatable_type',
        'position',
    ];

    /**
     * @inheritdoc
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = 'mediatables';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function mediatable()
    {
        return $this->morphTo();
    }
    
    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id', 'id');
    }
}