<?php

declare(strict_types=1);

use App\Models\Embedding;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
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
        Embedding::query()
            ->create([
                'embedding' => $result->embeddings[$key]->embedding,
                'content' => $saying,
            ]);
    }
});

Artisan::command('search', function (): void {
    $result = OpenAI::embeddings()->create([
        'model' => 'text-embedding-3-small',
        'input' => 'What do dogs say?',
    ]);

    $embedding = new Vector($result->embeddings[0]->embedding);

    $this->table(
        ['saying'],
        Embedding::query()
            ->orderByRaw('embedding <-> ?', [$embedding])
            ->take(2)
            ->get('content')
    );
});
