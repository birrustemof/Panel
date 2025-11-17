<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});

// Xəbər route'ları
Route::prefix('news')->name('news.')->group(function () {
    Route::get('/',                      [NewsController::class, 'news'])->name('index');
    Route::post('/store',                [NewsController::class, 'store'])->name('store');
    Route::delete('/{id}',               [NewsController::class, 'destroy'])->name('destroy');
    Route::get('/deleted',               [NewsController::class, 'deletedNews'])->name('deleted');
    Route::post('/{id}/restore',         [NewsController::class, 'restore'])->name('restore'); // PATCH -> POST
    Route::delete('/{id}/force-delete',  [NewsController::class, 'forceDelete'])->name('forceDelete');
    Route::get('/export',                [NewsController::class, 'export'])->name('export');

});

// Form route'ları - PageController istifadə edir
Route::prefix('forms')->name('forms.')->group(function () {
    Route::get('general/{id}',           [NewsController::class, 'show'])->name('general');
    Route::put('general/{id}',           [NewsController::class, 'update'])->name('general.update');
});

// Digər route'lar
Route::get("simple",                     [NewsController::class, 'news'])->name('simple');
Route::get("",                           [PageController::class, 'index'])->name('index');
Route::get("index2",                     [PageController::class, 'index2'])->name('index2');
Route::get("index3",                     [PageController::class, 'index3'])->name('index3');
Route::get('/xeber2',                    [PageController::class, 'xeber2'])->name('xeber2');
// Kateqoriya route'ları

Route::get('/Katoqoriya-new', [CategoryController::class, 'KatoqoriyaNew'])->name('tables/Katoqoriya-new');
Route::get('/Katoqoriya-list', [CategoryController::class, 'KatoqoriyaList'])->name('tables/Katoqoriya-list');
Route::get('/katoqoriya-new', [CategoryController::class, 'KatoqoriyaNew'])->name('katoqoriya.new');
Route::get('/katoqoriya-list', [CategoryController::class, 'KatoqoriyaList'])->name('katoqoriya.list');
Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');

