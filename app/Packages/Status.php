<?php namespace Packages;

use Illuminate\Database\Eloquent\Model;

class Status extends Model {

    /**
     *
     */
    public function publications()
    {
        return $this->morphedByMany('Packages\Publication', 'statusable')
            ->translatedIn()
            ->active()
            ->ordered();
    }

    /**
     *
     */
    public function news()
    {
        return $this->morphedByMany('Packages\News', 'statusable')
            ->translatedIn()
            ->active()
            ->ordered();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        switch ($this->slug) {
            case 'i-video':
                return route('status-news', 'videos');

            case 'i-photo':
                return route('status-news', 'photos');
        }

        return route('home');
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        switch ($this->slug) {
            case 'i-video':
                return _('Відео');

            case 'i-photo':
                return _('Фото');
        }

        return '';
    }
}
