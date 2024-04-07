<?php

namespace Subashrijal5\Bilingual\Services;

class GenerateService
{
    public static function checkValidity()
    {
        self::checkApiKey();
        self::checkLocalePath();
    }

    public static function generate($language)
    {
        $strings = ApiService::getLanguageStrings($language['short_code']);
        if (count($strings) > 0) {
            FileWriteService::writeFile($language['short_code'], $strings);
        }
    }


    private static function checkApiKey()
    {
        throw_if(empty(config('bilingual.project_api_key')), new \Exception('Project API Key is not set. Please check your dashboard and set on env or config file'));
        ApiService::getProject();
    }

    private static function checkLocalePath()
    {
        throw_if(empty(config('bilingual.locales_path')), new \Exception('Please set locales_path in config'));
        throw_if(!is_dir(config('bilingual.locales_path')), new \Exception("Locales path is not valid. Please check the path in config or create the directory mentioned.
         \n you might need to run `php artisan lang:publish` to publish your default lang directory"));
    }

    public static function prepareStrings($fileStrings)
    {
        $result = [];
        foreach ($fileStrings as $filename => $data) {
            $group = str_replace('.php', '', $filename);
            $response =  self::flattenArray($data, $group);
            $result = array_merge($result, $response);
        }
        return $result;
    }



    private static function flattenArray($array, $group, $parentKey = '',)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = empty($parentKey) ? $key : $parentKey . '.' . $key;
            if (is_array($value)) {
                $result = array_merge($result, self::flattenArray($value, $group, $newKey));
            } else {
                $result[] = ['key' => $newKey, 'value' => $value, 'group' => $group];
            }
        }
        return $result;
    }
}
