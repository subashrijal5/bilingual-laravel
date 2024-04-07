<?php

namespace Subashrijal5\Bilingual\Services;

class FileWriteService
{

    public static function writeFile($langCode, $strings)
    {
        $languageCodeMap = config('bilingual.language_code_map');

        if (in_array($langCode, $languageCodeMap)) {
            $langCode = array_search($langCode, $languageCodeMap);
        }

        $fileName = config('bilingual.default_file_name');

        $basePath = config('bilingual.locales_path') . "/$langCode";

        $isGrouped = config('bilingual.grouped');

        self::createDirectory($basePath);
        if ($isGrouped === "false") {
            $content = self::getUngroupedContent($strings);
            self::createFile($basePath . '/' . $fileName . '.php', $content);
        }

        if ($isGrouped === "true") {
            $content = self::getGroupedContent($strings, $basePath);
        }
    }



    private static function getGroupedContent($strings, $basePath)
    {
        foreach ($strings as $page => $stringList) {
            $content = self::getUngroupedContent($stringList);
            self::createFile($basePath . '/' . $page . '.php', $content);
        }
    }


    private static function createDirectory($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return true;
    }

    private static function createFile($path, $content)
    {
        $directory = dirname($path);
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0777, true)) {
                return false; // Failed to create the directory
            }
        }
        // Attempt to create the file and write the content
        if (file_put_contents($path, $content) !== false) {
            return true; // File created successfully
        } else {
            return false; // Failed to create the file or write content
        }
    }

    private static function getUngroupedContent($strings)
    {
        $actualArray = self::createActualArray($strings);
        $content = "<?php\n\n";
        $content .= "return \n";
        $content .= var_export($actualArray, true);
        $content .= ";";
        return $content;
    }


    private static function createActualArray(array $strings)
    {
        $returnArrays = [];
        foreach ($strings as $string) {
            $keys = explode('.', $string['key']);
            $newArray = &$returnArrays;
            foreach ($keys as $singleKeys) {
                if (!isset($newArray[$singleKeys])) {
                    $newArray[$singleKeys] = [];
                }
                $newArray = &$newArray[$singleKeys];
            }
            $newArray = $string['value'];
        }
        return $returnArrays;
    }
}
