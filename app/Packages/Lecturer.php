<?php

namespace Packages;

use Illuminate\Database\Eloquent\Model;
use Packages\GenericFileAttachment;
use App\Traits\Translatable;
use App\GlobalScopes\Translation\TranslationTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lecturer extends Model {

    use Translatable, TranslationTrait, SoftDeletes;

    /**
     *
     * @var string
     */
    public $translationModel = '\Packages\LecturerTranslation';


    /**
     * @var array
     */
    public $date_format = 'd.m.Y';

    /**
     * @var array
     */
    public $date_format_each = ['birth' => 'd.m.Y'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['birth', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $translatedAttributes = [
        'title',
        'slug',
        'position',
        'degree',
        'body',
        'is_active',
    ];

    protected $fillable = [
        'title',
        'slug',
        'birth',
        'image_storage_id',
        'position',
        'degree',
        'email',
        'telephone',
        'body',
        'is_active',
        'is_dean',
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
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDean($query)
    {
        return $query->where('is_dean', 1);
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
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function news()
    {
        return $this->morphedByMany('Packages\News', 'lecturerable');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function publications()
    {
        return $this->morphedByMany('Packages\Publication', 'lecturerable');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function subjects()
    {
        return $this->morphedByMany('Packages\Subject', 'lecturerable');
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
     * @return string
     */
    public function getViewUrl()
    {
        return route('lecturers.view', $this->slug);
    }

    /**
     *
     * @return string
     */
    public function getGenericImageAttribute()
    {
        return GenericFileAttachment::makePrimaryKey('Packages\Lecturer', $this->id, 'image_storage_id');
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

        return null;
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
    public function getFirstName()
    {
        $parts = explode(' ', $this->title);
        $firstName = array_shift($parts);
        $lastName = array_pop($parts);
        $firstName = trim(implode(' ', $parts));

        return $firstName;
    }

    /**
     *
     * @return string
     */
    public function getLastName()
    {
        $parts = explode(' ', $this->title);

        return array_shift($parts);
    }

    /**
     *
     * @return string
     */
    public function getSearchViewName()
    {
        return 'parts.articles.main-lecturer';
    }

}
