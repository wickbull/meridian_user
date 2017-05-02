<?php

namespace Packages;

use Illuminate\Database\Eloquent\Model;

class PublicationsCategoryTranslation extends Model
{

    /**
     *
     * @var string
     */
    public $table = 'publications_categories_translations';

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
        'is_active',
        'is_top',
    ];

}
