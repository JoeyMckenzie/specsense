<?php

declare(strict_types=1);

use App\Models\DocumentEmbedding;
use App\Services\OpenAIConnector;
use App\Services\OpenAIDocumentAnalyzer;
use App\Support\PromptParser;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;
use Pgvector\Laravel\Vector;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('insert', function (): void {
    $sayings = [
        'Felines say meow',
        'Canines say woof',
        'Birds say tweet',
        'Humans say hello',
    ];

    $result = OpenAI::embeddings()->create([
        'model' => 'text-embedding-3-small',
        'input' => $sayings,
    ]);

    foreach ($sayings as $key => $saying) {
        DocumentEmbedding::query()
            ->create([
                'embedding' => $result->embeddings[$key]->embedding,
                'content' => $saying,
            ]);
    }
});

Artisan::command('search', function (): void {
    $result = OpenAI::embeddings()->create([
        'model' => 'text-embedding-3-small',
        'input' => 'Is there any work involving drainage?',
    ]);

    $embedding = new Vector($result->embeddings[0]->embedding);

    $this->table(
        ['scope of work'],
        DocumentEmbedding::query()
            ->orderByRaw('embedding <-> ?', [$embedding])
            ->take(2)
            ->get(['page'])
    );
});

Artisan::command('image-test', function (): void {
    $systemPrompt = PromptParser::getPrompt(base_path('prompts/system.md'));
    $userPrompt = PromptParser::getPrompt(base_path('prompts/user.md'));

    $image = Storage::disk('local')->get('documents/test.png');
    $encodedImage = 'data:image/png;base64,'.base64_encode((string) $image);
    $response = Http::withHeaders([
        'Authorization' => 'Bearer '.Config::string('openai.api_key'),
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ])->post(Config::string('openai.base_uri').'/chat/completions', [
        'model' => Config::string('openai.model'),
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are a construction management expert with particular expertise in construction blueprints.',
            ],
            [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => 'This is a blueprint image of a construction job. Can you gather any relevant information from it in terms of material quantities and measurements?',
                    ],
                    [
                        'type' => 'image_url',
                        'image_url' => [
                            'url' => $encodedImage,
                        ],
                    ],
                ],
            ],
        ],
    ]);

    $json = $response->json();

    dd($response);
});

Artisan::command('spec-plans-test', function (): void {
    $userPrompt = PromptParser::getPrompt(base_path('prompts/user.md'));
    $connector = new OpenAIConnector;
    $path = Storage::disk('local')->path('documents/test_spec_list.pdf');
    $content = new OpenAIDocumentAnalyzer($connector)->parsePdfContent($path);

    $response = $connector->getParsedDocumentMetadata($content, $userPrompt);

    dd($response);
});
