<?php

namespace App\GlobalScopes\Translation;

trait TranslationTrait {

    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootTranslationTrait()
    {
        static::addGlobalScope(new TranslationScope);
    }

}
