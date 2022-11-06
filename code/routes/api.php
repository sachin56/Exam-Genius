<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\ApiemployeeController;
use App\Http\Controllers\ApiTodolistController;
use App\Http\Controllers\ApiUserController;

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

//ap login
Route::post('/login',[ApiUserController::class,'login']);

//Companies Routes
Route::get('/todolist',[ApiTodolistController::class,'index'])->name('todolist');
Route::get('/todolist/create',[ApiTodolistController::class,'create']);
Route::post('/todolist',[ApiTodolistController::class,'store']);
Route::get('/todolist/{id}',[ApiTodolistController::class,'show']);
Route::put('/todolist/{id}',[ApiTodolistController::class,'update']);
Route::delete('/todolist/{id}',[ApiTodolistController::class,'destroy']);
