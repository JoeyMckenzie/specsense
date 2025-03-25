<?php

declare(strict_types=1);

namespace App\Http\Controllers\Documents;

use App\Enums\DocumentAnalysisStatus;
use App\Http\Concerns\HasVerifiedUser;
use App\Http\Requests\Documents\CreateDocumentAnalysisRequest;
use App\Jobs\ProcessDocumentForAnalysis;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

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
        ]);

        if ($request->has('work_scopes')) {
            /** @var string[] $scopes */
            $scopes = $request->input('work_scopes');

            foreach ($scopes as $scope) {
                $analysis->workScopes()->create([
                    'name' => Str::trim($scope),
                ]);
            }
        }

        ProcessDocumentForAnalysis::dispatch($analysis);

        return redirect()
            ->back()
            ->with('success', 'Document analysis will begin shortly. You will receive a notification when it is complete.');
    }
}
