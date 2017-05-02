<?php

namespace Packages;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Translatable;
use App\GlobalScopes\Translation\TranslationTrait;
use Carbon\Carbon;
use App\Helpers\Month;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model {

    use Translatable, TranslationTrait, SoftDeletes;

    /**
     *
     * @var string
     */
    public $translationModel = '\Packages\NewsTranslation';

    /**
     * @var array
     */
    public $date_format = 'd.m.Y';

    /**
     * @var array
     */
    public $date_format_each = ['publish_at' => 'd.m.Y H:i'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['publish_at'];

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
        'publish_at',
    ];

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('news.publish_at', 'DESC');
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
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->active()
            ->where(\DB::raw('`news`.publish_at'), '<', Carbon::now())
            ->translatedIn('uk');
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAnnouncements($query)
    {
        return $query->whereHas('categories', function ($query) {
            $query->whereTranslation('slug', 'announcements');
        });
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|Collection $exclude
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExclude($query, $exclude)
    {
        $ids = $this->getIds($exclude);

        return $query->whereNotIn($this->getKeyName(), $ids->all());
    }

    /**
     *
     * @param  array|Collection $exclude
     * @return Collection
     */
    protected function getIds($exclude)
    {
        if (is_array($exclude)) {
            $exclude = collect($exclude);
        }

        return $exclude->map(function ($item) {
            if ($item instanceof Model) {
                return $item->getKey();
            }

            return $item;
        });
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
        return route('news.view', $this->slug);
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
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function statuses()
    {
        return $this->morphToMany('Packages\Status', 'statusable');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function categories()
    {
        return $this->belongsToMany('Packages\NewsCategory', 'news_news_category');
    }

    /**
     *
     * @param  string $lang
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function categoriesInLang($lang)
    {
        return $this->belongsToMany('Packages\NewsCategory', 'news_news_category')
            ->translatedIn($lang)->active()->get();
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
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function news()
    {
        return $this->morphToMany('Packages\News', 'newsable');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function publications()
    {
        return $this->morphToMany('Packages\Publication', 'publicationsable');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function lecturers()
    {
        return $this->morphToMany('Packages\Lecturer', 'lecturerable');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function chairs()
    {
        return $this->morphToMany('Packages\Chair', 'chairable');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function laboratories()
    {
        return $this->morphToMany('Packages\Laboratory', 'laboratoryable');
    }

    /**
     * @return string Date
     */
    public function getPublishDate()
    {
        if (! $this->publish_at) {
            return null;
        }

        $publishAt = $this->publish_at;

        $mount = Month::create($publishAt->format('m'));

        return $publishAt->format('d') . " " . $mount->getInGenitiveCase() . " " . $publishAt->format('Y');
    }

    /**
     * @return string Date
     */
    public function getSimplePublishDate()
    {
        if (! $this->publish_at) {
            return null;
        }

        return $this->publish_at->format('d.m.Y');
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
        return GenericFileAttachment::makePrimaryKey('Packages\News', $this->id, 'image_storage_id');
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
    public function getImage($size = false)
    {
        if ($attachment_item = $this->image)
            return $attachment_item->file->getThumb($size, $attachment_item->image);
    }

    /**
     *
     * @return string|null
     */
    public function getIAImage()
    {
        if ($attachment_item = $this->image)
            return $attachment_item->file->url();

        return null;
    }

    /**
     * instant article kicker atribute
     * @return string|null
     */
    public function getIAKicker()
    {
        return 'Новини';
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

    /**
     *
     * @return string
     */
    public function getSearchViewName()
    {
        return 'parts.articles.media-extended';
    }

}
