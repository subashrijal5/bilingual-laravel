<?php

namespace Subashrijal5\Bilingual\Commands;

use Illuminate\Console\Command;
use Subashrijal5\Bilingual\Services\ApiService;
use Subashrijal5\Bilingual\Services\GenerateService;
use Subashrijal5\Bilingual\Services\ReadLocalFile;

class PushStringsToTranslate extends Command
{
    /**
     * It syncs the strings to be translated from your base language
     *
     * @var string
     */
    protected $signature = 'bilingual:push-translation';

    /**
     * The console Syncs the strings need to translated from your code to the server.
     * Note: it doesn't translate automatically if you doesn't have enough credit
     *
     * @var string
     */
    protected $description = 'The console Syncs the strings need to translated from your code to the server.Note: it doesn\'t translate automatically if you doesn\'t have enough credit';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ReadLocalFile $readLocalFile, ApiService $apiService)
    {

        try {
            $this->info("Checking validity of configuration");
            // Check validity of configuration
            GenerateService::checkValidity();

            // get languages from folder structure
            $this->info("Reading local files");
            $languageCodes = $readLocalFile->getCurrentLanguageCodes();
            // Sync Language codes with server languages. throw error if user doesn't have enough credit
            $this->info("Syncing language codes");
            $apiService->syncProjectLanguages($languageCodes);

            // get project base language
            $project = $apiService->getProject();
            $baseLanguage = $project['data']['base_language'];

            // get baseLanguage strings from local files
            $this->info("Reading local files for base language {$baseLanguage}");
            $baseLanguageStrings = $readLocalFile->getStrings($baseLanguage);
            // Prepare baseLanguage strings to be sent to server
            $this->info("Preparing strings to be sent to server");
            $requestData = GenerateService::prepareStrings($baseLanguageStrings);

            // Now sync the translations with the server.
            $this->info("Sending strings to server");
            $data = $apiService->syncProjectStrings($baseLanguage ,$requestData);
            $this->info($data["message"]);
            // // Get the languages to generate
            // $this->info("Getting languages to generate translations");
            // $languages = ApiService::getProjectLanguages();
            // throw_if(empty($languages), \Exception::class, "No languages found");

            // // Start to generate translations
            // $this->info("Generating translations");
            // $this->withProgressBar($languages, function ($language) {
            //     $this->info("Generating translations for {$language['name']}");
            //     $translationStrings = GenerateService::generate($language);
            //     $this->info("Generated {$translationStrings} translation strings");
            // });

            return Command::SUCCESS;
        } catch (\Throwable $th) {
            // $this->error("Error occurred");
            $this->error($th->getMessage());
            return Command::FAILURE;
        }
    }
}
