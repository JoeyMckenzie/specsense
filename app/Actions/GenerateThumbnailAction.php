<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\Actions\GeneratesThumbnailAction;
use Illuminate\Contracts\Filesystem\Factory as FactoryContract;
use Spatie\PdfToImage\Pdf;

final readonly class GenerateThumbnailAction implements GeneratesThumbnailAction
{
    public function __construct(
        private FactoryContract $storage
    ) {
        //
    }

    public function handle(string $path, string $filename): string
    {
        // Ensure the thumbnails directory exists
        $this->storage->disk('public')->makeDirectory('thumbnails');

        // Create PDF instance from the file
        $pdf = new Pdf($this->storage->disk('local')->path($path));

        // Generate thumbnail filename and path
        $thumbnailFilename = pathinfo($filename, PATHINFO_FILENAME).'_thumb.jpg';
        $thumbnailPath = 'thumbnails/'.$thumbnailFilename;

        // Generate thumbnail at 300x300 pixels
        $pdf->selectPage(1)
            ->resolution(300)
            ->thumbnailSize(300, 300)
            ->save($this->storage->disk('public')->path($thumbnailPath));

        return $thumbnailPath;
    }
}
