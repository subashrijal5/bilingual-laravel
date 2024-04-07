<?php

namespace Subashrijal5\Bilingual\Services;

class ReadLocalFile
{

    public static function getCurrentLanguageCodes()
    {
        $basePath = config('bilingual.locales_path');
        // read the folders in the base path
        $folders = scandir($basePath);
        // remove the . and .. folders
        $folders = array_values(array_diff($folders, ['.', '..']));


        return $folders;
    }

    public static function getStrings($language){
        $basePath = config('bilingual.locales_path');
        $languageCodeMap = config('bilingual.language_code_map');
        $get = array_search($language, $languageCodeMap);
        $language = $get ? $get : $language;
        $filePath = $basePath . '/' . $language;
        $filenames = array_values(array_diff(scandir($filePath), ['.', '..']));
        $strings = [];
        foreach ($filenames as $filename) {
            $strings[$filename] = require $filePath . '/' . $filename;
        }
        return $strings;
    }
}
