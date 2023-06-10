<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [UserController::class, 'index']);

Route::get("/user_tbl", [UserController::class, 'userTbl']);

Route::post("/user_save", [UserController::class, 'saveUser']);

Route::get("/edit_user", [UserController::class, 'editModal']);

Route::post("/update_user", [UserController::class, 'updateUser']);

Route::get("/delete_user", [UserController::class, 'deleteUser']);