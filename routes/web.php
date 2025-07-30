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

    Route::get('/note/list/{id}', [AdminController::class, 'noteList'])->name('note.list');

    Route::get('/note/debit/create/{id}', [AdminController::class, 'createDebitNote'])->name('note.debit.create');

    Route::get('/note/credit/create/{id}', [AdminController::class, 'createCreditNote'])->name('note.credit.create');

    Route::post('/note/store', [AdminController::class, 'storeNote'])->name('note.store');

    Route::post('/debit/store', [AdminController::class, 'storeDebitNote'])->name('debit.store');

    Route::post('/credit/store', [AdminController::class, 'storeCreditNote'])->name('credit.store');

    Route::get('/notes/{id}/edit', [App\Http\Controllers\AdminController::class, 'editNote'])->name('note.edit');

    Route::post('/notes/{id}/update', [App\Http\Controllers\AdminController::class, 'updateNote'])->name('note.update');

    Route::get('/notes/{id}/pdf', [AdminController::class, 'downloadNotePdf'])->name('note.pdf');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
