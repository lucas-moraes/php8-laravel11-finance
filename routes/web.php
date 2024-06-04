<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/movement/getAll', [MovementController::class, 'getAllMovements']);
Route::get('/movement/filter', [MovementController::class, 'getByMonthAndYearAndCategory']);
Route::post('/movement/create', [MovementController::class, 'createMovement']);
Route::delete('/movement/{rowid}', [MovementController::class, 'deleteById']);
Route::put('/movement/{rowid}', [MovementController::class, 'updateFullMovementById']);
Route::patch('/movement/{rowid}', [MovementController::class, 'updatePartialMovementById']);
Route::get('/category/getAll', [CategoryController::class, 'getAllCategories']);
Route::get('/category/create', [CategoryController::class, 'createCategory']);
