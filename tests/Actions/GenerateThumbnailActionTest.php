<?php

declare(strict_types=1);

namespace Tests\Actions;

use App\Actions\GenerateThumbnailAction;
use Illuminate\Contracts\Filesystem\Factory as StorageContract;
use Illuminate\Support\Facades\Storage;

covers(GenerateThumbnailAction::class);

describe(GenerateThumbnailAction::class, function (): void {
    it('creates thumbnails directory if it does not exist', function (): void {
        // Arrange
        Storage::fake('public');

        $storage = app(StorageContract::class);
        $action = new GenerateThumbnailAction($storage);
        $path = 'documents/test.pdf';
        $filename = 'test.pdf';

        // Act
        $action->handle($path, $filename);

        // Assert
        expect(Storage::disk('public')->exists('thumbnails'))->toBeTrue();
    });

    it('generates a thumbnail from the first page of a PDF', function (): void {
        // Arrange
        $storage = app(StorageContract::class);
        $action = new GenerateThumbnailAction($storage);
        $path = 'documents/test.pdf';
        $filename = 'test.pdf';

        // Create a test PDF file
        Storage::disk('local')->put($path, file_get_contents(base_path('tests/Fixtures/Files/04-0K8004sp.pdf')));

        // Act
        $thumbnailPath = $action->handle($path, $filename);

        // Assert
        expect($thumbnailPath)->toBe('thumbnails/test_thumb.jpg');
        expect(Storage::disk('public')->exists($thumbnailPath))->toBeTrue();
    });

    it('generates thumbnail with correct dimensions', function (): void {
        // Arrange
        $storage = app(StorageContract::class);
        $action = new GenerateThumbnailAction($storage);
        $path = 'documents/test.pdf';
        $filename = 'test.pdf';

        // Create a test PDF file
        Storage::disk('local')->put($path, file_get_contents(base_path('tests/Fixtures/Files/04-0K8004sp.pdf')));

        // Act
        $thumbnailPath = $action->handle($path, $filename);

        // Assert
        $image = imagecreatefromjpeg(Storage::disk('public')->path($thumbnailPath));
        expect(imagesx($image))->toBe(300);
        expect(imagesy($image))->toBe(300);
        imagedestroy($image);
    });
});
