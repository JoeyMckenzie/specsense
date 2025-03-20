<?php

declare(strict_types=1);

namespace App\Http\Controllers\Documents;

use App\Enums\DocumentAnalysisStatus;
use App\Http\Concerns\HasVerifiedUser;
use App\Http\Requests\Documents\StoreDocumentAnalysisRequest;
use App\Jobs\ProcessDocumentForAnalysis;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;

final class DocumentAnalysisController
{
    use HasVerifiedUser;

    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentAnalysisRequest $request, Document $document): RedirectResponse
    {
        $analysis = $document->analysis()->create([
            'status' => DocumentAnalysisStatus::IN_PROGRESS,
            'document_id' => $document->id,
        ]);

        ProcessDocumentForAnalysis::dispatch($analysis);

        return redirect()
            ->back()
            ->with('success', 'Document analysis will begin shortly. You will receive a notification when it is complete.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(): void
    {
        //
    }
}
