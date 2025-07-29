<?php

use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\MasterlistController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/masterlist', [MasterlistController::class, 'index'])->name('masterlist.index');
    Route::get('/masterlist.create', [MasterlistController::class, 'create'])->name('masterlist.create');
    Route::post('/masterlist', [MasterlistController::class, 'store'])->name(name: 'masterlist.store');
    Route::get('/masterlist/{masterlist}/edit', [MasterlistController::class, 'edit'])->name(name: 'masterlist.edit');
    Route::put('/masterlist/{masterlist}', [MasterlistController::class, 'update'])->name('masterlist.update');
    Route::delete('/masterlist/{masterlist}/', [MasterlistController::class, 'destroy'])->name(name: 'masterlist.destroy');


    Route::get('/documents', [DocumentsController::class, 'index'])->name(name: 'documents.index');
    Route::get('/documents/departments/{department}', [DocumentsController::class, 'departments'])->name(name: 'documents.departments');

});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
