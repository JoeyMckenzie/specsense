<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Document;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;

final class GenerateDocumentThumbnail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Document $document
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $path = $this->document->path;
        $filename = $this->document->filename;

        $pdf = new Pdf(Storage::path($path));
        $thumbnailFilename = pathinfo($filename, PATHINFO_FILENAME).'_thumb.jpg';
        $thumbnailPath = 'thumbnails/'.$thumbnailFilename;

        // Ensure the thumbnails directory exists
        Storage::disk('public')->makeDirectory('thumbnails');

        // Generate thumbnail at 300x300 pixels
        $pdf->selectPage(1)
            ->resolution(300)
            ->thumbnailSize(300, 300)
            ->save(Storage::disk('public')->path($thumbnailPath));

        $this->document->thumbnail = $thumbnailPath;
        $this->document->save();
    }

    public function fail($exception = null): void
    {
        Log::error('Failed to generate thumbnail: '.$exception?->getMessage());
    }
}
