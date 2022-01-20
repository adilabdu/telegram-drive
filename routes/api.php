<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('folders')->name('folder.')->group(function() {
    Route::get('/{user}', [FolderController::class, 'index'])->name('index');
    Route::get('/subfolders/{folder}', [FolderController::class, 'children'])->name('children');
    Route::get('/parent/{folder}', [FolderController::class, 'parent'])->name('parent');
    Route::post('/', [FolderController::class, 'create'])->name('create');
    Route::patch('/{folder}', [FolderController::class, 'update'])->name('update');
    Route::delete('/{folder}', [FolderController::class, 'destroy'])->name('delete');
});
