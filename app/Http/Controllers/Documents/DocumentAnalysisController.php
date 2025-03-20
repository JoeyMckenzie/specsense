<?php

declare(strict_types=1);

namespace App\Http\Controllers\Documents;

use App\Http\Requests\Documents\StoreDocumentAnalysisRequest;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;

final class DocumentAnalysisController
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
    public function store(): RedirectResponse
    {
        return redirect()
            ->back()
            ->with('success', 'Document analysis will begin shortly. You will receive a notification when it is complete.');
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
