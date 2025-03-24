<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\Actions\GeneratesThumbnailAction;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;

final class GenerateThumbnailAction implements GeneratesThumbnailAction
{
    public function handle(string $path, string $filename): string
    {
        // Ensure the thumbnails directory exists
        Storage::disk('public')->makeDirectory('thumbnails');

        $pdf = new Pdf(Storage::path($path));
        $thumbnailFilename = pathinfo($filename, PATHINFO_FILENAME).'_thumb.jpg';
        $thumbnailPath = 'thumbnails/'.$thumbnailFilename;

        // Generate thumbnail at 300x300 pixels
        $pdf->selectPage(1)
            ->resolution(300)
            ->thumbnailSize(300, 300)
            ->save(Storage::disk('public')->path($thumbnailPath));

        return $thumbnailPath;
    }
}
