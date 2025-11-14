<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\NewsController;

Route::get('/', function () {
    return view('welcome');
});

// Xəbər route'ları
Route::prefix('news')->name('news.')->group(function () {
    Route::get('/', [NewsController::class, 'news'])->name('index');
    Route::post('/store', [NewsController::class, 'store'])->name('store');
    Route::delete('/{id}', [NewsController::class, 'destroy'])->name('destroy'); // YENİ
});

// Form route'ları - PageController istifadə edir
Route::prefix('forms')->name('forms.')->group(function () {
    Route::get('general/{id}', [NewsController::class, 'show'])->name('general');
    Route::put('general/{id}', [NewsController::class, 'update'])->name('general.update');
});

// Digər route'lar
Route::get("simple", [NewsController::class, 'news'])->name('simple');
Route::get("", [PageController::class, 'index'])->name('index');
Route::get("index2", [PageController::class, 'index2'])->name('index2');
Route::get("index3", [PageController::class, 'index3'])->name('index3');
Route::get('/xeber2', [PageController::class, 'xeber2'])->name('xeber2');
// Axtarış route-u
// web.php faylına əlavə edin
Route::get('/Search', function () {
    return view('Search');
})->name('Search.page');

// Axtarış API route-u
Route::get('/Search-news', [NewsController::class, 'Search'])->name('news.Search');
