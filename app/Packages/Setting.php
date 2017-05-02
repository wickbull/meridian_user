<?php namespace Packages;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

    /**
     *
     */
    public $timestamps = false;

    /**
     *
     * @var [type]
     */
    protected static $settings = null;


    /**
     *
     * @param  string $key
     * @return string|null
     */
    public static function getByKey($key, $locale = 'uk', $default = null)
    {
        if (is_null(static::$settings)) {
            static::$settings = self::whereLocale($locale)
                ->lists('value', 'name');
        }

        return array_get(static::$settings, $key, $default);
    }
}
