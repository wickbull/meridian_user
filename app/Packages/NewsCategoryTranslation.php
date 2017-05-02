<?php

namespace Packages;

use Illuminate\Database\Eloquent\Model;

class NewsCategoryTranslation extends Model
{

    /**
     *
     * @var string
     */
    public $table = 'news_categories_translations';

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
