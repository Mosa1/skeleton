<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application control panel path
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application control panel path. This value is used when the
    | contnet manager or any type of users needs to change something on website.
    |
    */

    'admin_path' => env('ADMIN_PATH', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Application default language
    |--------------------------------------------------------------------------
    |
    | This value is of your application default language. This value is used when language is not set
    | in session.
    |
    */

    'default_language' => env('DEFAULT_LANGUAGE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Yandex Translation API_KEY For Auto Translate Static Texts
    |--------------------------------------------------------------------------
    |
    | This value is yandex translation api key. This value is used in Texts Module
    | for auto translate static texts.
    |
    */

    'yandex_api_key' => env('YANDEX_API_KEY', 'YANDEX_API_KEY'),
];