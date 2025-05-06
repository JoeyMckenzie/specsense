<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\GenerateThumbnailAction;
use App\Actions\ParseDocumentContent;
use App\Contracts\Actions\GeneratesThumbnailAction;
use App\Contracts\Actions\ParsesDocumentContent;
use App\Contracts\Services\DocumentAnalyzerContract;
use App\Contracts\Services\LlmConnectorContract;
use App\Contracts\Services\OcrAnalyzerContract;
use App\Services\OpenAIConnector;
use App\Services\OpenAIDocumentAnalyzer;
use App\Services\TesseractAnalyzer;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Override;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        self::configureServices();
    }

    public function boot(): void
    {
        self::configureModels();
        self::configureVite();
        self::configureSchema();
        self::configureActions();
        self::configureCommands();
        self::configureDates();
    }

    private function configureServices(): void
    {
        $this->app->bind(LlmConnectorContract::class, OpenAIConnector::class);
        $this->app->bind(DocumentAnalyzerContract::class, OpenAIDocumentAnalyzer::class);
        $this->app->bind(OcrAnalyzerContract::class, TesseractAnalyzer::class);
    }

    private function configureModels(): void
    {
        Model::automaticallyEagerLoadRelationships();
        Model::unguard();
        Model::shouldBeStrict();
    }

    private function configureVite(): void
    {
        Vite::useAggressivePrefetching();
    }

    private function configureSchema(): void
    {
        URL::forceScheme('https');
    }

    private function configureActions(): void
    {
        $this->app->bind(GeneratesThumbnailAction::class, GenerateThumbnailAction::class);
        $this->app->bind(ParsesDocumentContent::class, ParseDocumentContent::class);
    }

    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->isProduction(),
        );
    }

    private function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }
}
