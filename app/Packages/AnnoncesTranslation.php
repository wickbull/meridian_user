<?php

namespace Packages;

use App\Extended\GalleryInserter;
use Illuminate\Database\Eloquent\Model;

class AnnoncesTranslation extends Model
{

    /**
     *
     * @var string
     */
    public $table = 'annonces_translations';

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
        'subtitle',
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
        return $this->belongsTo('\Packages\Annonce', 'news_id', 'id', 'annonces_translations');
    }

    /**
     *
     */
    public function tags()
    {
        return $this->morphToMany('Packages\Tag', 'taggable');
    }

}
