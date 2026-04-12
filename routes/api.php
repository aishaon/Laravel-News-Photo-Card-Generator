<?php

use App\Http\Controllers\PhotoCardController;
use Illuminate\Support\Facades\Route;

Route::post('/photo-card', [PhotoCardController::class, 'apiGenerate']);
