<?php

if ( ! function_exists('static_asset_www'))
{
    /**
     * Get the path to static www file
     *
     * @param  string  $path
     * @return string
     */
    function static_asset_www($path)
    {
        return rtrim(config('app.url'), '/') . '/' . ltrim($path, '/');
    }
}

if ( ! function_exists('normalize_phone'))
{
    /**
     * Get the normal phone number
     *
     * @param  string  $phone
     * @return string
     */
    function normalize_phone($phone)
    {
        return str_replace(' ', '', $phone);
    }
}

if ( ! function_exists('getCurrentLocaleInStandart'))
{
    /**
     * Get the normal phone number
     *
     * @param  string  $phone
     * @return string
     */
    function getCurrentLocaleInStandart()
    {
        $locale = LaravelLocalization::getCurrentLocale();

        switch ($locale) {
            case 'ru':
                return 'ru_RU';
            case 'en':
                return 'en_US';
            default:
                return 'uk_UA';
        }

    }
}

if ( ! function_exists('setting'))
{
    /**
     * Get setting
     *
     * @param  string $key
     * @param  string $default
     * @return string
     */
    function setting($key, $default = '') {
        $locale = \LaravelLocalization::getCurrentLocale();

        return Packages\Setting::getByKey($key, $locale, $default);
    }
}
