<?php

namespace Subashrijal5\Bilingual\Services;

use Illuminate\Support\Facades\Http;

class ApiService
{

    public static function getProjectLanguages()
    {
        $response = Http::withHeaders([
            'X-PROJECT-KEY' => config('bilingual.project_api_key'),
            'Accept' => 'application/json',
        ])->withBasicAuth('subash', 'subash')->get(config('bilingual.api_url') . '/api/projects/languages');
        if ($response->failed()) {
            throw new \Exception($response->json()['message']);
        }

        $languages = $response->json();

        return $languages;
    }

    public static function getLanguageStrings($language)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-PROJECT-KEY' => config('bilingual.project_api_key'),
        ])->withBasicAuth('subash', 'subash')->get(config('bilingual.api_url') . '/api/projects/languages/' . $language . '/strings', [
            'grouped'=>config('bilingual.grouped')
        ]);
        if ($response->failed()) {
            throw new \Exception($response->json()['message']);
        }

        return $response->json();
    }

    public static function getProject()
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-PROJECT-KEY' => config('bilingual.project_api_key'),

        ])->withBasicAuth('subash', 'subash')->get(config('bilingual.api_url') . '/api/projects');
        if ($response->failed()) {
            throw new \Exception($response->json()['message']);
        }
        return $response->json();
        // throw_if($response->failed(), new \Exception('Invalid Api key. Please verify your api key. try to clear cache and try again'));
    }

    public static function syncProjectLanguages($languages)
    {
        $languageCodeMap = config('bilingual.language_code_map');

        foreach ($languages as $key => $language) {
            if(array_key_exists($language, $languageCodeMap)){
                $languages[$key] = $languageCodeMap[$language];
            }
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-PROJECT-KEY' => config('bilingual.project_api_key'),
        ])->withBasicAuth('subash', 'subash')->post(config('bilingual.api_url') . '/api/projects/languages', [
            'language_codes' => $languages
        ]);
        if ($response->failed()) {
            throw new \Exception($response->json()['message']);
        }

        return $response->json();
    }

    public static function syncProjectStrings(string $languageCode, array $strings){
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-PROJECT-KEY' => config('bilingual.project_api_key'),
        ])->withBasicAuth('subash', 'subash')->post(config('bilingual.api_url') . '/api/projects/languages/' . $languageCode . '/sync-strings', [
            'strings' => $strings
        ]);
        if ($response->failed()) {
            throw new \Exception($response->json()['message']);
        }

        return $response->json();
    }
}


