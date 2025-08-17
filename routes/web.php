<?php

use App\Http\Controllers\ColorController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\MasterlistController;
use App\Http\Controllers\RecentsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    //masterlist routes
    Route::get('/masterlist', [MasterlistController::class, 'index'])->name('masterlist.index');
    Route::get('/masterlist.create', [MasterlistController::class, 'create'])->name('masterlist.create');
    Route::post('/masterlist', [MasterlistController::class, 'store'])->name(name: 'masterlist.store');
    Route::get('/masterlist/{masterlist}/edit', [MasterlistController::class, 'edit'])->name(name: 'masterlist.edit');
    Route::put('/masterlist/{masterlist}', [MasterlistController::class, 'update'])->name('masterlist.update');
    Route::delete('/masterlist/{masterlist}/', [MasterlistController::class, 'destroy'])->name(name: 'masterlist.destroy');

    //document routes
    Route::get('/documents', [DocumentsController::class, 'index'])->name(name: 'documents.index');
    Route::get('/documents?doc_code={CodeStore}', [DocumentsController::class, 'index'])->name(name: 'documents.index');
    Route::get('/documents/departments/{department}', [DocumentsController::class, 'departments'])->name(name: 'documents.departments');
    
    //recently added routes
    Route::get('/recents', [RecentsController::class, 'index'])->name('recents.index');

    //departments routes
    Route::get('/departments', [DepartmentsController::class, 'index'])->name('departments.index');


    //color routes
     Route::get('/color', [ColorController::class, 'update'])->name('color.index');

     
Route::get('/gd-check', function () {
    $path = public_path('Dsource-logo.png');
    if (!file_exists($path)) {
        return "File not found: $path";
    }

    $img = imagecreatefrompng($path); // if PNG
    // or: $img = imagecreatefromjpeg($path); // if JPEG

    return $img ? 'GD can open ✅' : 'GD failed ❌';
});
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
