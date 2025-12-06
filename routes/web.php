<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\ClientLoginController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;

// Admin routes
Route::prefix('admin')->name('admin.')->group(function() {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('loginPost');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    // Forgot Password
    Route::get('password/reset', [AdminLoginController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [AdminLoginController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [AdminLoginController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [AdminLoginController::class, 'reset'])->name('password.update');
});

Route::middleware('auth:admin')->group(function() {
    Route::resource('companies', CompanyController::class);
    Route::resource('users', UserController::class);

    // Document routes
    Route::get('documents/upload', [DocumentController::class, 'uploadForm'])->name('documents.uploadForm');
    Route::post('documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::get('documents/excelupload', [DocumentController::class, 'exceluploadForm'])->name('documents.exceluploadForm');
    Route::post('documents/excelupload', [DocumentController::class, 'excelUpload'])->name('documents.excelupload');
    Route::get('documents/list', [DocumentController::class, 'mainIndex'])->name('documents.main.index');
    Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('documents/view/{document}', [DocumentController::class, 'view'])->name('documents.view');
});
// Client routes
Route::middleware('auth')->group(function() {
    Route::get('client/documents', [DocumentController::class, 'clientView'])->name('client.documents');
    Route::get('documents/view/{document}', [DocumentController::class, 'view'])->name('documents.view');
    Route::post('/logout', [ClientLoginController::class, 'logout'])->name('logout');
});

// Client login routes (no guard needed)
Route::get('/login', [ClientLoginController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login', [ClientLoginController::class, 'login'])->name('auth.loginPost');

Route::get('/', function () {
    return redirect()->route('auth.login');
});