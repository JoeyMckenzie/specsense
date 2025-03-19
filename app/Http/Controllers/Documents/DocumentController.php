<?php

declare(strict_types=1);

namespace App\Http\Controllers\Documents;

use App\Data\DocumentSummaryData;
use App\Http\Concerns\HasVerifiedUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Documents\StoreDocumentRequest;
use App\Http\Requests\Documents\UpdateDocumentRequest;
use App\Models\Document;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Log;
use Spatie\PdfToImage\Pdf;

final class DocumentController extends Controller
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

        // Generate thumbnail from first page of PDF
        $thumbnailPath = null;
        try {
            $pdf = new Pdf(Storage::path($path));
            $thumbnailFilename = pathinfo($filename, PATHINFO_FILENAME).'_thumb.jpg';
            $thumbnailPath = 'thumbnails/'.$thumbnailFilename;

            // Generate thumbnail at 300x300 pixels
            $pdf->selectPage(1)
                ->resolution(300)
                ->thumbnailSize(300, 300)
                ->save(Storage::path($thumbnailPath));
        } catch (Exception $e) {
            // Log the error but don't fail the upload
            Log::error('Failed to generate thumbnail: '.$e->getMessage());
        }

        $document = Document::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'original_filename' => $file->getClientOriginalName(),
            'filename' => $filename,
            'path' => $path,
            'thumbnail' => $thumbnailPath,
            'size' => $file->getSize(),
            'type' => 'Special Provisions',
            'user_id' => $this->verifiedUser()->id,
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
        return Inertia::render('documents/create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document): Response
    {
        return Inertia::render('documents/show', [
            'document' => DocumentSummaryData::from($document),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document): Response
    {
        return Inertia::render('Documents/Edit', [
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
        if ($document->thumbnail_path) {
            Storage::delete($document->thumbnail_path);
        }
        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document deleted successfully.');
    }
}
