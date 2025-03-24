<?php

declare(strict_types=1);

use App\Http\Controllers\Documents\DocumentAnalysisController;
use App\Http\Controllers\Documents\DocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::resource('documents', DocumentController::class);
    Route::post('documents/{document}/analysis', [DocumentAnalysisController::class, 'store'])
        ->name('document-analysis.store')
        ->middleware('can:create,App\\Models\\DocumentAnalysis,document');
});
