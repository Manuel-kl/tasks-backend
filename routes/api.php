<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//user routes
Route::post('sign-up', [App\Http\Controllers\UserController::class, 'register']);
Route::post('sign-in', [App\Http\Controllers\UserController::class, 'login']);
Route::post('logout/{id}', [App\Http\Controllers\UserController::class, 'logout']);
Route::post('delete-account/{id}', [App\Http\Controllers\UserController::class, 'deleteAccount']);

//task routes
Route::post('create-task', [App\Http\Controllers\TaskController::class, 'createTask']);
Route::get('get-tasks', [App\Http\Controllers\TaskController::class, 'getTasks']);
Route::get('get-task/{id}', [App\Http\Controllers\TaskController::class, 'getTask']);
Route::put('update-task/{id}', [App\Http\Controllers\TaskController::class, 'updateTask']);
Route::delete('delete-task/{id}', [App\Http\Controllers\TaskController::class, 'deleteTask']);