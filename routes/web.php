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

Route::post('/doc', [MovementController::class, 'doc']);

Route::get('/movement/get-all', [MovementController::class, 'getAllMovements']);
Route::get('/movement/filter', [MovementController::class, 'getByMonthAndYearAndCategory']);
Route::get('/movement/{id}', [MovementController::class, 'getMovementById']);
Route::get('/movement/filter-year-group-by-month/{year}', [MovementController::class, 'getMovementsByYearGroupByMonth']);
Route::get('/movement/filter-year-group-by-category/{year}', [MovementController::class, 'getMovementsByYearGroupByCategory']);
Route::get('/category/get-all', [CategoryController::class, 'getAllCategories']);
Route::post('/category/create', [CategoryController::class, 'createCategory']);
Route::post('/movement/create', [MovementController::class, 'createMovement']);
Route::post('/movement/create-multiple', [MovementController::class, 'createMultipleMovements']);
Route::delete('/movement/{id}', [MovementController::class, 'deleteById']);
Route::put('/movement/{id}', [MovementController::class, 'updateFullMovementById']);
Route::patch('/movement/{id}', [MovementController::class, 'updatePartialMovementById']);
