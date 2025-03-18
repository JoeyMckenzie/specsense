<?php

declare(strict_types=1);

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\Documents\StoreDocumentAnalysisRequest;
use App\Http\Requests\Documents\UpdateDocumentAnalysisRequest;
use App\Models\DocumentAnalysis;

final class DocumentAnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentAnalysisRequest $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentAnalysis $documentAnalysis): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentAnalysis $documentAnalysis): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentAnalysisRequest $request, DocumentAnalysis $documentAnalysis): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentAnalysis $documentAnalysis): void
    {
        //
    }
}
