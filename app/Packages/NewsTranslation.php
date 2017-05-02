<?php

namespace Packages;

use App\Extended\GalleryInserter;
use Illuminate\Database\Eloquent\Model;

class NewsTranslation extends Model
{

    /**
     *
     * @var string
     */
    public $table = 'news_translations';

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
        return $this->belongsTo('\Packages\News', 'news_id', 'id', 'news_translations');
    }

    /**
     *
     */
    public function tags()
    {
        return $this->morphToMany('Packages\Tag', 'taggable');
    }

}
