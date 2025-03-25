<?php

declare(strict_types=1);

use App\Http\Controllers\BidItemController;
use App\Http\Controllers\Documents\DocumentAnalysisController;
use App\Http\Controllers\Documents\DocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::resource('documents', DocumentController::class);

    Route::post('documents/{document}/analysis', [DocumentAnalysisController::class, 'store'])
        ->name('document-analysis.store')
        ->middleware('can:create,App\\Models\\DocumentAnalysis,document');

    Route::post('document/{document}/analysis/{documentAnalysis}/bid-items', [BidItemController::class, 'store'])->name('bid-items.store');
    Route::put('document/{document}/analysis/{documentAnalysis}/bid-items/{bidItem}', [BidItemController::class, 'update'])->name('bid-items.update');
    Route::delete('document/{document}/analysis/{documentAnalysis}/bid-items/{bidItem}', [BidItemController::class, 'destroy'])->name('bid-items.destroy');
});
