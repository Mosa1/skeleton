<?php

namespace BetterFly\Skeleton\App\Http\Transformers;

use \League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\App;

abstract class BaseTransformerAbstract extends TransformerAbstract
{
    // Ability to override Transformer functions

    public static function transfromFilledData($data, $translatable,$moduleCfg, $translatableFields = [])
    {
        $locale = App::getLocale();
        $locales = config('translatable.locales');
        $filledData = [];
        $multiLang = isset($data['multilang']) && ($data['multilang'] == 'true' || $data['multilang'] == '1') ? true : false;
        unset($data['multilang']);
        unset($data['_token']);
        unset($data['_method']);
        unset($data['request_name_space']);

        if ($translatable) {
            if (!$multiLang) {
                $filledData = self::setDataToLocale($locale, $data);
            } else if ($multiLang) {
                foreach ($locales as $locale) {
                    $filledData = self::setDataToLocale($locale, $data);
                }
            }
        } else {
            $filledData = $data;
        }

        return $filledData;
    }

    public static function setDataToLocale($locale, $data)
    {
        foreach ($data as $key => $value) {
            $filledData[$key] = $value;
        }

        return $filledData;
    }

}