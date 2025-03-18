<?php

declare(strict_types=1);

use App\Http\Controllers\Documents\DocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
});
