<?php

namespace Packages;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Translatable;
use App\GlobalScopes\Translation\TranslationTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laboratory extends Model {

    use Translatable, TranslationTrait, SoftDeletes;

    /**
     *
     * @var string
     */
    public $translationModel = '\Packages\LaboratoryTranslation';


    /**
     * @var array
     */
    public $date_format = 'd.m.Y';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $translatedAttributes = [
        'title',
        'slug',
        'body',
        'is_active',
    ];

    protected $fillable = [
        'title',
        'slug',
        'body',
        'is_active',
        'foundation_at',
    ];

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'DESC');
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereTranslation('is_active', 1);
    }

    /**
     *
     * @return string
     */
    public function getViewUrl()
    {
        return route('laboratories.view', $this->slug);
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function mainLecturer()
    {
        return $this->morphToMany('\Packages\Lecturer', 'lecturerable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function news()
    {
        return $this->morphToMany('Packages\News', 'newsable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function publications()
    {
        return $this->morphToMany('Packages\Publication', 'publicationsable');
    }

    /**
     *
     * @return mixed
     */
    public function getOgDescription()
    {
        $body = strip_tags($this->body);

        if (mb_strlen($body) > 100) {
            return trim(mb_substr($body, 0, 150)) . '…';
        }

        return trim($body);
    }

    /**
     *
     * @return string
     */
    public function getSearchViewName()
    {
        return 'parts.articles.laboratory-list';
    }

}
