<?php

namespace BetterFly\Skeleton\App\Http\Transformers;

use \League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\App;

abstract class BaseTransformerAbstract extends TransformerAbstract
{
    // Ability to override Transformer functions

    public static function transfromFilledData($data, $translatable, $translatableFields = [])
    {
        $locale = App::getLocale();
        $locales = config('translatable.locales');
        $filledData = [];
        $multiLang = isset($data['multilang']) && ($data['multilang'] == 'true' || $data['multilang'] == '1') ? true : false;
        unset($data['multilang']);
        unset($data['_token']);
        unset($data['_method']);

        if ($translatable) {
            if (!$multiLang) {
                $filledData = self::setDataToLocale($locale, $data, $translatableFields);
            } else if ($multiLang) {
                foreach ($locales as $locale) {
                    $filledData = self::setDataToLocale($locale, $data, $translatableFields);
                }
            }
        } else {
            $filledData = $data;
        }

        return $filledData;
    }

    public static function setDataToLocale($locale, $data, $translatableFields)
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $translatableFields))
                $filledData[$locale][$key] = $value;
            else
                $filledData[$key] = $value;
        }

        return $filledData;
    }

}