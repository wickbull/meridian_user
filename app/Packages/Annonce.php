<?php

namespace Packages;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Translatable;
use App\GlobalScopes\Translation\TranslationTrait;
use Carbon\Carbon;
use App\Helpers\Month;
use Illuminate\Database\Eloquent\SoftDeletes;

class Annonce extends Model {

    use Translatable, TranslationTrait, SoftDeletes;

    /**
     *
     * @var string
     */
    public $translationModel = '\Packages\AnnoncesTranslation';

    /**
     * @var array
     */
    public $date_format = 'd.m.Y';

    /**
     * @var array
     */
    public $date_format_each = ['event_at' => 'd.m.Y H:i'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['event_at'];

    /**
     *
     * @var array
     */
    public $translatedAttributes = [
        'title',
        'slug',
        'subtitle',
        'body',
        'is_active',
    ];

    /**
     *
     * @var array
     */
    public $fillable = [
        'title',
        'slug',
        'image_storage_id',
        'subtitle',
        'body',
        'is_active',
        'is_top',
        'creator_id',
        'editor_id',
        'event_at',
    ];

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('event_at', 'DESC');
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereTranslation('is_active', true);
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTop($query)
    {
        return $query->whereIsTop(true);
    }


    /**
     *
     * @param  integer $limit
     * @return News
     */
    static public function getTop($limit = 5)
    {
        return self::active()->top()->ordered()->limit($limit)->get();
    }

    /**
     *
     * @return string
     */
    public function getViewUrl()
    {
        return route('annonce.view', $this->slug);
    }

    /**
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getViewUrl();
    }

    /**
     *
     * mixed
     */
    public function tags()
    {
        return $this->getTranslation()->tags;
    }

    /**
     *
     * mixed
     */
    public function getTagsAttribute()
    {
        return $this->tags();
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
    public function getGenericImageAttribute()
    {
        return GenericFileAttachment::makePrimaryKey('Packages\Annonce', $this->id, 'image_storage_id');
    }

    /**
     *
     * @return Packages\GenericFileAttachment
     */
    public function image()
    {
        return $this->hasOne('Packages\GenericFileAttachment', 'key', 'generic_image')->with('file');
    }

    /**
     *
     * @return mixed
     */
    public function getImage($size = false, $placeholder = 'default')
    {
        if ($attachment_item = $this->image)
            return $attachment_item->file->getThumb($size, $attachment_item->image);

    }



    /**
     *
     * @param  $locale ex: 'ru'
     * @return string
     */
    public function getLocalizedUrl($locale)
    {
        return \LaravelLocalization::getLocalizedURL($locale, $this->getViewUrl());
    }


}
