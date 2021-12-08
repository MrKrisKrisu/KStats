<?php

/**
 * Translate the given message.
 * Overloads the laravel own helper method
 *
 * @param string|null $key
 * @param array       $replace
 * @param string|null $locale
 *
 * @return string|array|null
 */
function __(string $key = null, array $replace = [], string $locale = null) {
    if(is_null($key)) {
        return $key;
    }

    $translation = trans($key, $replace, $locale);
    if($translation != $key) {
        return $translation;
    }

    //if no translation for the current language is found try english...
    $translation = trans($key, $replace, 'en');
    if($translation != $key) {
        return $translation;
    }

    //if no translation for the current language is found try german...
    $translation = trans($key, $replace, 'de');
    if($translation != $key) {
        return $translation;
    }

    //This return should never be reached...
    return trans($key, $replace, $locale);
}
