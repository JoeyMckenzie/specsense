<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Enums\DocumentAnalysisStatus;
use App\Http\Controllers\Documents\DocumentAnalysisController;
use App\Jobs\ProcessDocumentForAnalysis;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

covers(DocumentAnalysisController::class);

describe(DocumentAnalysisController::class, function (): void {
    beforeEach(function (): void {
        Queue::fake();
    });

    it('ensures guests are redirected to the login page when creating analysis', function (): void {
        // Arrange & Act & Assert
        $document = Document::factory()->create();
        $this->post("/documents/$document->id/analysis")->assertRedirect('/login');
    });

    it('creates a document analysis without work scopes', function (): void {
        // Arrange
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();

        expect($document->analysis)->toBeNull();

        // Act
        $response = $this->actingAs($user)->post("/documents/$document->id/analysis", [
            'document_id' => $document->id,
            'context' => 'Test context',
        ]);

        $document->refresh();

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Document analysis will begin shortly. You will receive a notification when it is complete.');

        expect($document->analysis)
            ->not->toBeNull()
            ->and($document->analysis->status)->toBe(DocumentAnalysisStatus::IN_PROGRESS->value)
            ->and($document->analysis->context)->toBe('Test context')
            ->and($document->analysis->document_id)->toBe($document->id);

        Queue::assertPushed(ProcessDocumentForAnalysis::class);
    });

    it('creates a document analysis with work scopes', function (): void {
        // Arrange
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();
        $workScopes = ['Scope 1', 'Scope 2'];

        expect($document->analysis)->toBeNull();

        // Act
        $response = $this->actingAs($user)->post("/documents/$document->id/analysis", [
            'document_id' => $document->id,
            'context' => 'Test context',
            'work_scopes' => $workScopes,
        ]);

        $document->refresh();

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Document analysis will begin shortly. You will receive a notification when it is complete.');

        $analysis = $document->analysis->load('workScopes');
        expect($analysis)
            ->not->toBeNull()
            ->getRawOriginal('status')->toBe(DocumentAnalysisStatus::IN_PROGRESS->value)
            ->document_id->toBe($document->id);

        expect($analysis->workScopes)
            ->toHaveCount(2);

        $scopeNames = $analysis->workScopes->pluck('name')->all();
        expect($scopeNames)->toEqualCanonicalizing($workScopes);

        Queue::assertPushed(ProcessDocumentForAnalysis::class);
    });

    it('prevents users from creating analysis for other users documents', function (): void {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $document = Document::factory()->for($user2)->create();

        // Act
        $response = $this->actingAs($user1)->post("/documents/$document->id/analysis", [
            'document_id' => $document->id,
            'context' => 'Test context',
        ]);

        // Assert
        $response->assertForbidden();
        Queue::assertNotPushed(ProcessDocumentForAnalysis::class);
    });

    it('validates work scopes array size', function (): void {
        // Arrange
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();
        $workScopes = array_fill(0, 6, 'Scope'); // Create array with 6 scopes

        // Act
        $response = $this->actingAs($user)->post("/documents/$document->id/analysis", [
            'document_id' => $document->id,
            'context' => 'Test context',
            'work_scopes' => $workScopes,
        ]);

        // Assert
        $response->assertSessionHasErrors(['work_scopes' => 'The work scopes field must not have more than 5 items.']);
        Queue::assertNotPushed(ProcessDocumentForAnalysis::class);
    });

    it('validates work scope string length', function (): void {
        // Arrange
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();
        $workScopes = [str_repeat('a', 31)]; // Create string longer than 30 characters

        // Act
        $response = $this->actingAs($user)->post("/documents/$document->id/analysis", [
            'document_id' => $document->id,
            'context' => 'Test context',
            'work_scopes' => $workScopes,
        ]);

        // Assert
        $response->assertSessionHasErrors(['work_scopes.0' => 'The work_scopes.0 field must not be greater than 30 characters.']);
        Queue::assertNotPushed(ProcessDocumentForAnalysis::class);
    });

    it('validates unique work scopes', function (): void {
        // Arrange
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();
        $workScopes = ['Scope 1', 'Scope 1'];

        // Act
        $response = $this->actingAs($user)
            ->post("/documents/$document->id/analysis", [
                'document_id' => $document->id,
                'context' => 'Test context',
                'work_scopes' => $workScopes,
            ]);

        // Assert
        $response->assertSessionHasErrors(['work_scopes.0' => 'The work_scopes.0 field has a duplicate value.']);
        $response->assertSessionHasErrors(['work_scopes.1' => 'The work_scopes.1 field has a duplicate value.']);
        Queue::assertNotPushed(ProcessDocumentForAnalysis::class);
    });
});
