<?php

namespace Packages;

use App\Extended\GalleryInserter;
use Illuminate\Database\Eloquent\Model;

class PublicationTranslation extends Model
{

    /**
     *
     * @var string
     */
    public $table = 'publications_translations';

    /**
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'slugsubtitle',
        'body',
        'is_active',
    ];

    /**
     *
     * @param  string  $value
     * @return string
     */
    public function getBodyAttribute($value)
    {
        return GalleryInserter::make($value);
    }

    public function original()
    {
        return $this->belongsTo('\Packages\Publication', 'publication_id', 'id', 'publications_translations');
    }

    /**
     *
     */
    public function tags()
    {
        return $this->morphToMany('Packages\Tag', 'taggable');
    }

}
