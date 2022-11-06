<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\TodolistController     ;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//user roles Routes
Route::get('/role',[UserRoleController::class,'index'])->name('role');
Route::get('/role/create',[UserRoleController::class,'create']);
Route::post('/role',[UserRoleController::class,'store']);
Route::get('/role/{id}',[UserRoleController::class,'show']);
Route::put('/role/{id}',[UserRoleController::class,'update']);
Route::delete('/role/{id}',[UserRoleController::class,'destroy']);

//user routes
Route::get('/user',[UserController::class,'index'])->name('user');
Route::get('/user/create',[UserController::class,'create']);
Route::post('/user',[UserController::class,'store']);
Route::get('/user/{id}',[UserController::class,'show']);
Route::put('/user/{id}',[UserController::class,'update']);
Route::delete('/user/{id}',[UserController::class,'destroy']);

//todoList Routes
Route::get('/todolist',[TodolistController::class,'index'])->name('todo');;
Route::get('/todolist/create',[TodolistController::class,'create']);
Route::post('/todolist',[TodolistController::class,'store']);
Route::get('/todolist/{id}',[TodolistController::class,'show']);
Route::put('/todolist/{id}',[TodolistController::class,'update']);
Route::delete('/todolist/{id}',[TodolistController::class,'destroy']);