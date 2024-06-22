<?php

use App\Http\Controllers\Shop\ChapterAPIController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/truyen/fetch/{last_id}', [ChapterAPIController::class, 'fetch'])->name('api.chapters.fetch');

