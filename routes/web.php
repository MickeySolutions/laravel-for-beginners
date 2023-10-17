<?php

use App\Events\ChatMessage;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\GateController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
//Gate related routes
Route::get('admin-only',[GateController::class,'adminOnly'])->middleware('can:canViewPage');
//User related routes
Route::get('/', [UserController::class, 'showHomePage'])->name("login");
Route::post('/register',[UserController::class,'register'])->middleware('guest');
Route::post('/login',[UserController::class,'login'])->middleware('guest');
Route::post('/logout',[UserController::class,'logout'])->middleware('mustBeLogIn');

//Follow related routes
Route::post('/create-follow/{user:username}',[FollowController::class,'createFollow'])->middleware('mustBeLogIn');
Route::post('/remove-follow/{user:username}',[FollowController::class,'removeFollow'])->middleware('mustBeLogIn');

//Post related routes
Route::get('/create-post',[PostController::class,'showCreatePost'])->middleware('mustBeLogIn');
Route::post('/create-post',[PostController::class,'storeNewPost'])->middleware('mustBeLogIn');
Route::get("/post/{post}",[PostController::class,"viewSinglePost"]);
Route::delete("/post/{post}",[PostController::class,'delete'])->middleware('can:delete,post');
Route::put('/post/{post:id}',[PostController::class,'updatePost'])->middleware('can:update,post');
Route::get('/post/{post:id}/edit',[PostController::class,'showEditPost'])->middleware('can:update,post');
Route::get("/search/{term}",[PostController::class,'search']);

//Profile related routes
Route::get('/profile/{user:username}',[UserController::class,'profile'])->middleware('auth');
Route::get('/profile/{user:username}/followers',[UserController::class,'profileFollowers'])->middleware('auth');
Route::get('/profile/{user:username}/following',[UserController::class,'profileFollowing'])->middleware('auth');

Route::middleware('cache.headers:public;max_age=20;etag')->group(function(){
Route::get('/profile/{user:username}/raw',[UserController::class,'profileRaw'])->middleware('auth');
Route::get('/profile/{user:username}/followers/raw',[UserController::class,'profileFollowersRaw'])->middleware('auth');
Route::get('/profile/{user:username}/following/raw',[UserController::class,'profileFollowingRaw'])->middleware('auth');
});

Route::get('/manage-avatar',[UserController::class,'showManageAvatar'])->middleware('mustBeLogIn');
Route::post('/manage-avatar',[UserController::class,'storeAvatar'])->middleware('mustBeLogIn');

//Chat route
Route::post('/send-chat-message',function(Request $request){
    $formFields=$request->validate([
        'textvalue'=>'required'
    ]);
    if(!trim(strip_tags($formFields['textvalue']))){
        return response()->noContent();
    }
    broadcast(new ChatMessage(['username'=>auth()->user()->username,'textvalue'=>strip_tags($request->textvalue),'avatar'=>auth()->user()->avatar]))->toOthers();
    return response()->noContent();
})->middleware('mustBeLogIn');
