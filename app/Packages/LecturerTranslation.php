<?php

namespace Packages;

use App\Extended\GalleryInserter;
use Illuminate\Database\Eloquent\Model;

class LecturerTranslation extends Model {

    /**
     *
     * @var string
     */
    public $table = 'lecturers_translations';

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
        'position',
        'degree',
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

}
