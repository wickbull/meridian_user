<?php namespace Packages;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Translatable;
use App\GlobalScopes\Translation\TranslationTrait;

class PublicationsCategory extends Model {

    use Translatable, TranslationTrait;

    public $table = 'publications_categories';

    /**
     *
     * @var string
     */
    public $translationModel = '\Packages\PublicationsCategoryTranslation';

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
     * @return string URL
     */
    public function getUrl()
    {
        return route('publications.category', ['publicationCategory' => $this->slug]);
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function publications()
    {
        return $this->belongsToMany('Packages\Publication', 'publication_publications_category');
    }
}
