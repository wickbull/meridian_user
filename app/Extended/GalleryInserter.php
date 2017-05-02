<?php

namespace App\Extended;

use Packages\Slider;

class GalleryInserter
{
    public static function make($body)
    {
        $sliderIds = self::getIdTag($body);
        $sliderViews = array_flip($sliderIds);
        $sliders = Slider::whereIn('id', $sliderIds)->with('galleryImages')->active()->get();

        foreach ($sliderViews as $sliderId => &$sliderBody) {
            $sliderBody = '';
            $slider = $sliders->find($sliderId);

            if ($slider) {
                if ($slider->galleryImages->count()) {
                    $sliderBody = view('parts.gallery', ['photos' => $slider->galleryImages])->render();
                }
            }
        }

        $body = preg_replace_callback(
            '/\[galleryId=(\d+)\]/',
            function ($preg) use ($sliderViews) {
                return isset($sliderViews[$preg[1]]) ? $sliderViews[$preg[1]] : '';
            },
            $body
        );

        return $body;
    }

    private static function getIdTag($body)
    {
        preg_match_all('/\[galleryId=(\d+)\]/', $body, $matches);

        if (isset($matches[1])) {
            return $matches[1];
        }

        return null;
    }

}
