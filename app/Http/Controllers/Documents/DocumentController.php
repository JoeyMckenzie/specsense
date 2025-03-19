<?php

declare(strict_types=1);

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\Documents\StoreDocumentRequest;
use App\Http\Requests\Documents\UpdateDocumentRequest;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;

final class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return inertia('documents/index', [
            'documents' => Document::query()
                ->where('user_id', auth()->id())
                ->latest()
                ->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $file = $request->file('file');
        $filename = $file->hashName();
        $path = $file->storeAs('documents', $filename);

        $document = Document::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'original_filename' => $file->getClientOriginalName(),
            'filename' => $filename,
            'path' => $path,
            'size' => (string) $file->getSize(),
            'type' => 'Special Provisions',
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('documents.show', $document)
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return inertia('documents/create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document): Response
    {
        return inertia('Documents/Show', [
            'document' => $document,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document): Response
    {
        return inertia('Documents/Edit', [
            'document' => $document,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        $document->update($request->validated());

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
        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document deleted successfully.');
    }
}
