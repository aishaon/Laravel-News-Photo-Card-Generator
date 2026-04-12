<?php

use App\Http\Controllers\PhotoCardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('photo-card.index');
});

Route::get('/photo-card', [PhotoCardController::class, 'index']);
Route::post('/photo-card/preview', [PhotoCardController::class, 'preview']);
Route::post('/photo-card/generate', [PhotoCardController::class, 'generate']);
