<?php

declare(strict_types=1);

namespace App\Http\Controllers\Documents;

use App\Enums\DocumentAnalysisStatus;
use App\Http\Concerns\HasVerifiedUser;
use App\Http\Requests\Documents\CreateDocumentAnalysisRequest;
use App\Jobs\ProcessDocumentForAnalysis;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;

final class DocumentAnalysisController
{
    use HasVerifiedUser;

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateDocumentAnalysisRequest $request, Document $document): RedirectResponse
    {
        $analysis = $document->analysis()->create([
            'status' => DocumentAnalysisStatus::IN_PROGRESS,
            'context' => $request->input('context'),
            'document_id' => $document->id,
        ]);

        if ($request->has('work_scopes')) {
            /** @var string[] $scopes */
            $scopes = $request->input('work_scopes');
            $mappedWorkScopes = array_map(fn (string $scope): array => [
                'document_analysis_id' => $analysis->id,
                'name' => $scope,
            ], $scopes);

            $createdWorkScopeAnalyses = $analysis
                ->workScopes()
                ->createMany($mappedWorkScopes);

            $analysis
                ->workScopes()
                ->saveMany($createdWorkScopeAnalyses);
        }

        ProcessDocumentForAnalysis::dispatch($analysis);

        return redirect()
            ->back()
            ->with('success', 'Document analysis will begin shortly. You will receive a notification when it is complete.');
    }
}
