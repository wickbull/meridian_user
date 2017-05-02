<?php

namespace Packages;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Packages\GenericFileAttachment;

class Slider extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sliders';

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
    protected $fillable = [
        'title',
        'slug',
        'is_active',
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
        return $query->whereIsActive(true);
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function galleryImages()
    {
        return $this->morphMany('Packages\GalleryAttachableItem', 'galleriable')
            ->orderBy('position', 'ASC');
    }

    public function getSlider($sliderId)
    {
        return $this->whereId($sliderId)->first();
    }

}
