<?php namespace Packages;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Translatable;
use App\GlobalScopes\Translation\TranslationTrait;

class NewsCategory extends Model {

    use Translatable, TranslationTrait;

    public $table = 'news_categories';

    /**
     *
     * @var string
     */
    public $translationModel = '\Packages\NewsCategoryTranslation';

    /**
     *
     * @var array
     */
    public $translatedAttributes = [
        'title',
        'slug',
        'is_active',
        'is_top',
    ];

    /**
     *
     * @var array
     */
    public $fillable = [
        'title',
        'slug',
        'is_active',
        'is_top',
    ];

    public $timestamps = false;

    /**
     *
     */
    public function news()
    {
        return $this->belongsToMany('\Packages\News')->ordered();
    }

    /**
     *
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('news.publish_at', 'DESC');
    }

    /**
     * @return string URL
     */
    public function getUrl()
    {
        return route('news', ['category' => $this->slug]);
    }

    /**
     *
     */
    public function scopeActive($query)
    {
        return $query->whereTranslation('is_active', 1);
    }

    /**
     *
     */
    public function scopeTop($query)
    {
        return $query->whereTranslation('is_top', 1);
    }
}
