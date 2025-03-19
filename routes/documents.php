<?php

declare(strict_types=1);

use App\Http\Controllers\Documents\DocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::resource('documents', DocumentController::class);
});
