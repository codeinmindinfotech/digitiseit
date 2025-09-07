<?php

use App\Http\Controllers\ClientDocumentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function() {
    Route::resource('companies', CompanyController::class);

    // Admin Document upload
    Route::get('documents/upload', [DocumentController::class, 'uploadForm'])->name('documents.uploadForm');
    Route::post('documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');

    // Company-wise document view
    Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
});
Route::get('client/documents', [DocumentController::class, 'clientView'])->name('client.documents');
Route::get('documents/view/{document}', [DocumentController::class, 'view'])->name('documents.view');

// Route::middleware('auth')->group(function () {
//     Route::resource('companies', CompanyController::class);

//     Route::get('admin/docs', [DocumentAdminController::class, 'show']);
//     Route::post('admin/docs/excel', [DocumentAdminController::class, 'uploadExcel']);
//     Route::post('admin/docs/upload', [DocumentAdminController::class, 'uploadDocs']);
// });

// Route::get('documents', [ClientDocumentController::class, 'index']);
