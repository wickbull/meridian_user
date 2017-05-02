<?php namespace Packages;

use Illuminate\Database\Eloquent\Model;
use Packages\GenericFileAttachment;
use App\Traits\Translatable;
use App\GlobalScopes\Translation\TranslationTrait;
use App\Helpers\ImagePlaceholder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model {

    use Translatable, TranslationTrait, SoftDeletes;

    /**
     *
     * @var string
     */
    public $translationModel = '\Packages\PageTranslation';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     *
     * @var array
     */
    public $translatedAttributes = [
        'title',
        'body',
        'is_active'
    ];

    /**
     *
     */
    public function getUrl()
    {
        return route('pages', ['page' => $this->slug]);
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
