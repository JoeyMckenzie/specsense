<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Http\Controllers\Documents\DocumentController;
use App\Jobs\GenerateDocumentThumbnail;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

covers(DocumentController::class);

describe(DocumentController::class, function (): void {
    beforeEach(function (): void {
        Storage::fake('local');
        Queue::fake();
    });

    it('ensures guests are redirected to the login page when accessing documents', function (): void {
        $this->get('/documents')->assertRedirect('/login');
    });

    it('displays a listing of documents for authenticated users', function (): void {
        $user = User::factory()->create();
        $documents = Document::factory()->count(3)->for($user)->create();

        $response = $this->actingAs($user)->get('/documents');

        $response->assertOk();
        $response->assertInertia(
            fn ($page) => $page
                ->component('documents/index')
                ->has('documents', 3)
                ->where('documents.0.id', $documents[0]->id)
                ->where('documents.1.id', $documents[1]->id)
                ->where('documents.2.id', $documents[2]->id)
        );
    });

    it('displays the document creation form', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/documents/create');

        $response->assertOk();
        $response->assertInertia(
            fn ($page) => $page
                ->component('documents/create')
        );
    });

    it('stores a new document', function (): void {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($user)->post('/documents', [
            'name' => 'Test Document',
            'description' => 'Test Description',
            'file' => $file,
        ]);

        $response->assertRedirect(route('documents.show', Document::first()));
        $response->assertSessionHas('success', 'Document uploaded successfully.');

        $document = Document::first();
        expect($document)
            ->name->toBe('Test Document')
            ->description->toBe('Test Description')
            ->original_filename->toBe('document.pdf')
            ->type->toBe('Special Provisions')
            ->user_id->toBe($user->id);

        expect(Storage::disk('local')->exists($document->path))->toBeTrue();
        Queue::assertPushed(GenerateDocumentThumbnail::class);
    });

    it('displays a specific document', function (): void {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();

        $response = $this->actingAs($user)->get("/documents/{$document->id}");

        $response->assertOk();
        $response->assertInertia(
            fn ($page) => $page
                ->component('documents/show')
                ->has('document')
                ->where('document.id', $document->id)
        );
    });

    it('displays the document edit form', function (): void {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();

        $response = $this->actingAs($user)->get("/documents/{$document->id}/edit");

        $response->assertOk();
        $response->assertInertia(
            fn ($page) => $page
                ->component('documents/edit')
                ->has('document')
                ->where('document.id', $document->id)
        );
    });

    it('updates a document', function (): void {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();

        $response = $this->actingAs($user)->put("/documents/{$document->id}", [
            'name' => 'Updated Document',
            'description' => 'Updated Description',
        ]);

        $response->assertRedirect(route('documents.show', $document));
        $response->assertSessionHas('success', 'Document updated successfully.');

        $document->refresh();
        expect($document)
            ->name->toBe('Updated Document')
            ->description->toBe('Updated Description');
    });

    it('deletes a document', function (): void {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();
        $documentPath = $document->path;
        $documentThumbnail = $document->thumbnail;

        $response = $this->actingAs($user)->delete("/documents/{$document->id}");

        $response->assertRedirect(route('documents.index'));
        $response->assertSessionHas('success', 'Document deleted successfully.');

        expect(Document::find($document->id))->toBeNull();
        expect(Storage::disk('local')->exists($documentPath))->toBeFalse();
        if ($documentThumbnail) {
            expect(Storage::disk('local')->exists($documentThumbnail))->toBeFalse();
        }
    });

    it('prevents users from accessing other users documents', function (): void {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $document = Document::factory()->for($user2)->create();

        $response = $this->actingAs($user1)->get("/documents/{$document->id}");

        $response->assertNotFound();
    });

    it('prevents users from updating other users documents', function (): void {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $document = Document::factory()->for($user2)->create();

        $response = $this->actingAs($user1)->put("/documents/{$document->id}", [
            'name' => 'Updated Document',
            'description' => 'Updated Description',
        ]);

        $response->assertNotFound();
    });

    it('prevents users from deleting other users documents', function (): void {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $document = Document::factory()->for($user2)->create();

        $response = $this->actingAs($user1)->delete("/documents/{$document->id}");

        $response->assertNotFound();
    });
});
