<?php namespace Packages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model {

    use SoftDeletes;

    /**
     *
     * @var array
     */
    public $fillable = [
        'name',
    ];

    public $timestamps = false;

    /**
     * @return string
     */
    public function getTitle()
    {
        return '#' . $this->name;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return route('search', ['q' => $this->title]);
    }

    /**
     * @return Illuminate\Database\Eloquent\Collection|null
     */
    public function publications()
    {
        return $this->morphedByMany('Packages\PublicationTranslation', 'taggable');
    }

    /**
     * @return Illuminate\Database\Eloquent\Collection|null
     */
    public function news()
    {
        return $this->morphedByMany('Packages\NewsTranslation', 'taggable');
    }


}
