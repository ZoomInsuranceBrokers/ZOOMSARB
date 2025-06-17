<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::get('/quotes/create', [AdminController::class, 'createQuote'])->name('quotes.create');

    Route::post('/quotes/store', [AdminController::class, 'storeQuote'])->name('quotes.store');

    Route::get('/quotes/list', [AdminController::class, 'quotesList'])->name('quotes.list');

    Route::get('/quotes/{id}', [AdminController::class, 'showQuotes'])->name('quotes.show');

    Route::get('/quotes/{id}/edit', [AdminController::class, 'editQuotes'])->name('quotes.edit');

    Route::put('/quotes/{id}', [AdminController::class, 'updateQuotes'])->name('quotes.update');

    Route::get('/delete-quotes/{id}', [AdminController::class, 'destroyQuotes'])->name('quotes.destroy');

    Route::get('/quotes/{id}/policywording', [AdminController::class, 'policyWording'])->name('quotes.policywording');

    Route::post('/quotes/{id}/policywording', [AdminController::class, 'savePolicyWording'])->name('quotes.policywording.save');

    Route::get('/quotes/{id}/download', [AdminController::class, 'downloadPdf'])->name('quotes.download');

    Route::get('/quotes/{id}/finalsubmit-download', [AdminController::class, 'finalSubmitAndDownload'])->name('quotes.finalsubmit.download');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
