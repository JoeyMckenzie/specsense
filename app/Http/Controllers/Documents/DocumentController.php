<?php

declare(strict_types=1);

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\Documents\StoreDocumentRequest;
use App\Http\Requests\Documents\UpdateDocumentRequest;
use App\Models\Document;

final class DocumentController extends Controller
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
    public function store(StoreDocumentRequest $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document): void
    {
        //
    }
}
