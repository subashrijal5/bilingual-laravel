<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'project_api_key' => env('PROJECT_API_KEY'),

    'locales_path' => base_path('lang'),

    'api_url' => env("PROJECT_API_URL", "https://bilingualapp.vuvusha.com"),

    // If you want to get all strings translation on single file, you can set it to false.
    'grouped' => "true",

    // Only available if grouped is false
    'default_file_name' => "bilingual",

    // locale_code => server_code
    'language_code_map' => [
        'en' => 'en-us'
    ]
];
