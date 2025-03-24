<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\Actions\GeneratesThumbnailAction;
use App\Models\Document;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

final class GenerateDocumentThumbnail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Document $document,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(GeneratesThumbnailAction $action): void
    {
        $path = $this->document->path;
        $filename = $this->document->filename;
        $this->document->thumbnail = $action->handle($path, $filename);
        $this->document->save();
    }

    public function fail(?Throwable $exception = null): void
    {
        $message = $exception?->getMessage() ?? 'Unknown error';
        Log::error('Failed to generate thumbnail: '.$message);
    }
}
