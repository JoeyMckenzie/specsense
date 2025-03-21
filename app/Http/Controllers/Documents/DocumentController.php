<?php

declare(strict_types=1);

namespace App\Http\Controllers\Documents;

use App\Data\DocumentSummaryData;
use App\Http\Concerns\HasVerifiedUser;
use App\Http\Requests\Documents\StoreDocumentRequest;
use App\Http\Requests\Documents\UpdateDocumentRequest;
use App\Jobs\GenerateDocumentThumbnail;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

final class DocumentController
{
    use HasVerifiedUser;

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $documents = Document::query()
            ->where('user_id', $this->verifiedUser()->id)
            ->latest()
            ->with('analysis')
            ->get();

        return Inertia::render('documents/index', [
            'documents' => DocumentSummaryData::collect($documents),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        /** @var UploadedFile $file */
        $file = $request->file('file');
        $filename = $file->hashName();
        $path = $file->storeAs('documents', $filename);

        $document = Document::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'original_filename' => $file->getClientOriginalName(),
            'filename' => $filename,
            'path' => $path,
            'size' => $file->getSize(),
            'type' => 'Special Provisions',
            'user_id' => $this->verifiedUser()->id,
        ]);

        GenerateDocumentThumbnail::dispatch($document);

        return redirect()
            ->route('documents.show', $document)
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('documents/create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document): Response
    {
        $documentData = $document
            ->load([
                'analysis',
                'analysis.workScopes',
                'analysis.bidItems',
            ]);

        return Inertia::render('documents/show', [
            'document' => DocumentSummaryData::from($documentData),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document): Response
    {
        return Inertia::render('documents/edit', [
            'document' => DocumentSummaryData::from($document->load('analysis')),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        $document->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        return redirect()
            ->route('documents.show', $document)
            ->with('success', 'Document updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document): RedirectResponse
    {
        Storage::delete($document->path);

        if ($document->thumbnail !== null) {
            Storage::delete($document->thumbnail);
        }

        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document deleted successfully.');
    }
}
