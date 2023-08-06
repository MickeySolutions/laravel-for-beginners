<?php

use App\Http\Controllers\PostController;
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
//User related routes
Route::get('/', [UserController::class, 'showHomePage'])->name("login");
Route::post('/register',[UserController::class,'register'])->middleware('guest');
Route::post('/login',[UserController::class,'login'])->middleware('guest');
Route::post('/logout',[UserController::class,'logout'])->middleware('mustBeLogIn');
//Post related routes
Route::get('/create-post',[PostController::class,'showCreatePost'])->middleware('mustBeLogIn');
Route::post('/create-post',[PostController::class,'storeNewPost'])->middleware('mustBeLogIn');
Route::get("/post/{post}",[PostController::class,"viewSinglePost"]);
Route::delete("/post/{post}",[PostController::class,'delete']);
//Profile related routes
Route::get('/profile/{user:username}',[UserController::class,'profile'])->middleware('auth');
