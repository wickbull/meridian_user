<?php

namespace Packages;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Translatable;
use App\GlobalScopes\Translation\TranslationTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fragment extends Model {

    use Translatable, TranslationTrait, SoftDeletes;

    /**
     *
     * @var string
     */
    public $translationModel = '\Packages\FragmentTranslation';


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
        'body',
        'is_active',
    ];

    protected $fillable = [
        'title',
        'slug',
        'body',
        'is_active',
    ];
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
}
