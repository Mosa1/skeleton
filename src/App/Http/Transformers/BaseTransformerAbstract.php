<?php

namespace BetterFly\Skeleton\App\Http\Transformers;

use \League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\App;

abstract class BaseTransformerAbstract extends TransformerAbstract
{
    // Ability to override Transformer functions

    public static function transfromFilledData($data, $translatable, $moduleCfg, $translatableFields = [])
    {
        $locales = isset($data['multilang']) && ($data['multilang'] == 'true' || $data['multilang'] == '1') ? config('translatable.locales') : [App::getLocale()];
        unset($data['multilang']);
        unset($data['_token']);
        unset($data['_method']);
        unset($data['request_name_space']);
        $filledData = $data;

        foreach ($translatableFields as $key => $value) {
            unset($filledData[$value]);
        }

        if ($translatable) {
            foreach ($locales as $locale) {
                $filledData[$locale] = self::setDataToLocale($data, $translatableFields);
            }
        } else {
            $filledData = $data;
        }

        return $filledData;
    }

    public static function setDataToLocale($data, $translatableFields)
    {
        $filledData = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $translatableFields))
                $filledData[$key] = $value;
        }

        return $filledData;
    }

}