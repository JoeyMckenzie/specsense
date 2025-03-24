<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Enums\DocumentAnalysisStatus;
use App\Http\Controllers\Documents\DocumentController;
use App\Jobs\GenerateDocumentThumbnail;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;

covers(DocumentController::class);

describe(DocumentController::class, function (): void {
    beforeEach(function (): void {
        Storage::fake('local');
        Queue::fake();
    });

    it('ensures guests are redirected to the login page when accessing documents', function (): void {
        // Arrange & Act & Assert
        $this->get('/documents')->assertRedirect('/login');
    });

    it('displays a listing of documents for authenticated users', function (): void {
        // Arrange
        $user = User::factory()->create();
        $documents = Document::factory()->count(3)->for($user)->create();

        // Act
        $response = $this->actingAs($user)->get('/documents');

        // Assert
        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page): \Illuminate\Testing\Fluent\AssertableJson => $page
                ->component('documents/index')
                ->has('documents', 3)
                ->where('documents.0.id', $documents[0]->id)
                ->where('documents.1.id', $documents[1]->id)
                ->where('documents.2.id', $documents[2]->id)
        );
    });

    it('displays the document creation form', function (): void {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->get('/documents/create');

        // Assert
        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page): \Inertia\Testing\AssertableInertia => $page
                ->component('documents/create')
        );
    });

    it('stores a new document', function (): void {
        // Arrange
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 100);

        // Act
        $response = $this->actingAs($user)->post('/documents', [
            'name' => 'Test Document',
            'description' => 'Test Description',
            'file' => $file,
        ]);

        // Assert
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

    it('shows a document without analysis', function (): void {
        // Arrange
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();

        // Act
        $response = $this->actingAs($user)->get("/documents/{$document->id}");

        // Assert
        $response->assertOk();
        $response->assertInertia(
            fn ($page) => $page
                ->component('documents/show')
                ->has('document')
                ->where('document.id', $document->id)
                ->where('document.analysis', null)
        );
    });

    it('shows a document with analysis but without nested relationships', function (): void {
        // Arrange
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();
        $document->analysis()->create(['status' => DocumentAnalysisStatus::NOT_STARTED->value]);

        // Act
        $response = $this->actingAs($user)->get("/documents/$document->id");

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page): \Illuminate\Testing\Fluent\AssertableJson => $page
                ->component('documents/show')
                ->has('document')
                ->where('document.id', $document->id)
                ->has('document.analysis')
                ->where('document.analysis.status', DocumentAnalysisStatus::NOT_STARTED->value)
                ->where('document.analysis.workScopes', [])
                ->where('document.analysis.bidItems', [])
        );
    });

    it('prevents users from editing other users documents', function (): void {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $document = Document::factory()->for($user2)->create();

        // Act
        $response = $this->actingAs($user1)->get("/documents/$document->id/edit");

        // Assert
        $response->assertForbidden();
    });

    it('deletes document thumbnail when deleting a document', function (): void {
        // Arrange
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'thumbnail' => 'thumbnails/test.jpg',
        ]);
        Storage::put('thumbnails/test.jpg', 'fake thumbnail content');

        // Act
        $response = $this->actingAs($user)->delete("/documents/$document->id");

        // Assert
        $response->assertRedirect(route('documents.index'));
        $response->assertSessionHas('success', 'Document deleted successfully.');

        expect(Document::find($document->id))->toBeNull();
        expect(Storage::disk('local')->exists($document->path))->toBeFalse();
        expect(Storage::disk('local')->exists('thumbnails/test.jpg'))->toBeFalse();
    });

    it('prevents unauthorized users from viewing documents', function (): void {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $document = Document::factory()->for($user2)->create();

        // Act
        $response = $this->actingAs($user1)->get("/documents/$document->id");

        // Assert
        $response->assertForbidden();
    });

    it('shows the document edit form', function (): void {
        // Arrange
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();
        $document->analysis()->create(['status' => DocumentAnalysisStatus::NOT_STARTED->value]);

        // Act
        $response = $this->actingAs($user)->get("/documents/$document->id/edit");

        // Assert
        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page): \Illuminate\Testing\Fluent\AssertableJson => $page
                ->component('documents/edit')
                ->has('document')
                ->where('document.id', $document->id)
                ->has('document.analysis')
                ->where('document.analysis.status', DocumentAnalysisStatus::NOT_STARTED->value)
        );
    });

    it('updates a document', function (): void {
        // Arrange
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();

        // Act
        $response = $this->actingAs($user)->put("/documents/$document->id", [
            'name' => 'Updated Document',
            'description' => 'Updated Description',
        ]);

        // Assert
        $response->assertRedirect(route('documents.show', $document));
        $response->assertSessionHas('success', 'Document updated successfully.');

        $document->refresh();
        expect($document)
            ->name->toBe('Updated Document')
            ->description->toBe('Updated Description');
    });

    it('prevents unauthorized users from updating documents', function (): void {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $document = Document::factory()->for($user2)->create();

        // Act
        $response = $this->actingAs($user1)->put("/documents/$document->id", [
            'name' => 'Updated Document',
            'description' => 'Updated Description',
        ]);

        // Assert
        $response->assertForbidden();
    });

    it('prevents unauthorized users from deleting documents', function (): void {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $document = Document::factory()->for($user2)->create();

        // Act
        $response = $this->actingAs($user1)->delete("/documents/$document->id");

        // Assert
        $response->assertForbidden();
    });

    it('shows a document with complete analysis relationships', function (): void {
        // Arrange
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();
        $analysis = $document->analysis()->create(['status' => DocumentAnalysisStatus::COMPLETED->value]);

        // Act
        $response = $this->actingAs($user)->get("/documents/$document->id");

        // Assert
        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page): \Illuminate\Testing\Fluent\AssertableJson => $page
                ->component('documents/show')
                ->has('document')
                ->where('document.id', $document->id)
                ->has('document.analysis')
                ->where('document.analysis.id', $analysis->id)
        );
    });
});
