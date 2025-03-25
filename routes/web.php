<?php

declare(strict_types=1);

use App\Http\Controllers\BidItemController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('welcome'))->name('home');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::post('/document-analyses/{analysis}/bid-items', [BidItemController::class, 'store'])->name('bid-items.store');
    Route::put('/bid-items/{bidItem}', [BidItemController::class, 'update'])->name('bid-items.update');
    Route::delete('/bid-items/{bidItem}', [BidItemController::class, 'destroy'])->name('bid-items.destroy');
});

require __DIR__.'/documents.php';
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
