<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/painel', function () {
    return view('painel.index');
});

Route::get('/movimentations', function () {
    return view('movimentations.index');
});

Route::post('/doc', [movementController::class, 'doc']);

Route::get('/movement/get-all', [MovementController::class, 'getAllMovements']);
Route::get('/movement/filter', [MovementController::class, 'getByMonthAndYearAndCategory']);
Route::post('/movement/create', [MovementController::class, 'createMovement']);
Route::delete('/movement/{rowid}', [MovementController::class, 'deleteById']);
Route::put('/movement/{rowid}', [MovementController::class, 'updateFullMovementById']);
Route::patch('/movement/{rowid}', [MovementController::class, 'updatePartialMovementById']);
Route::get('/movement/{rowid}', [MovementController::class, 'getMovementById']);
Route::get('/movement/filter-year-group-by-month', [MovementController::class, 'getMovementsByYearGroupByMonth']);
Route::get('/movement/filter-year-group-by-category', [MovementController::class, 'getMovementsByYearGroupByCategory']);
Route::get('/category/get-all', [CategoryController::class, 'getAllCategories']);
Route::get('/category/create', [CategoryController::class, 'createCategory']);
