<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\newsController;

Route::get('/', function () {
    return view('welcome');
});

// Xəbər route'ları
Route::prefix('news')->name('news.')->group(function () {
    Route::get('/', [newsController::class, 'news'])->name('index');
    Route::post('/store', [NewsController::class, 'store'])->name('store');
});

// Form route'ları - PageController istifadə edir
Route::prefix('forms')->name('forms.')->group(function () {
    Route::get('general/{id}', [newsController::class, 'show'])->name('general');
    Route::put('general/{id}', [newsController::class, 'update'])->name('general.update'); // YENİ

});

// Digər route'lar - PageController istifadə edir
Route::get("simple", [newsController::class, 'news'])->name('simple');
Route::get("", [PageController::class, 'index'])->name('index');
Route::get("index2", [PageController::class, 'index2'])->name('index2');
Route::get("index3", [PageController::class, 'index3'])->name('index3');
Route::get('/xeber2', [PageController::class, 'xeber2'])->name('xeber2');
// Axtarış route-u
// web.php faylına əlavə edin
Route::get('/Search', function () {
    return view('Search');
})->name('Search.page');


