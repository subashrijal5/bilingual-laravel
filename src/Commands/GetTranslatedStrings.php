<?php

namespace Subashrijal5\Bilingual\Commands;

use Illuminate\Console\Command;
use Subashrijal5\Bilingual\Services\ApiService;
use Subashrijal5\Bilingual\Services\GenerateService;

class GetTranslatedStrings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bilingual:get-translated';

    /**
     * The console Get translated string from the project dashboard.
     *
     * @var string
     */
    protected $description = 'Get translated string from the project dashboard';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        try {
            $this->info("Checking validity of configuration");
            // Check validity of configuration
            GenerateService::checkValidity();

            // Get the languages to generate
            $this->info("Getting languages to generate translations");
            $languages = ApiService::getProjectLanguages();
            throw_if(empty($languages), \Exception::class, "No languages found");

            // Start to generate translations
            $this->info("Getting translations");
            $this->withProgressBar($languages, function ($language) {
                $this->info("Generating translations for {$language['name']}");
                $translationStrings = GenerateService::generate($language);
                $this->info("Generated {$translationStrings} translation strings");
            });

            return Command::SUCCESS;
        } catch (\Throwable $th) {
            // $this->error("Error occurred");
            $this->error($th->getMessage());
            return Command::FAILURE;
        }
    }
}
