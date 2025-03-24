<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\DocumentAnalyzerContract;
use App\Contracts\LlmConnectorContract;
use App\Models\Document;
use App\Policies\DocumentPolicy;
use App\Services\OpenAIConnector;
use App\Services\OpenAIDocumentAnalyzer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
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
        $this->app->bind(DocumentAnalyzerContract::class, OpenAIDocumentAnalyzer::class);
        $this->app->bind(LlmConnectorContract::class, OpenAIConnector::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        self::configureModels();
        self::configureVite();
        self::configureSchema();
        self::configurePolicies();
    }

    private function configureModels(): void
    {
        Model::unguard();
        Model::shouldBeStrict();
    }

    private function configureVite(): void
    {
        Vite::useAggressivePrefetching();
    }

    private function configureSchema(): void
    {
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }
    }

    private function configurePolicies(): void
    {
        Gate::policy(Document::class, DocumentPolicy::class);
    }
}
