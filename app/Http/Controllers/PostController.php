<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

use Illuminate\Support\Str;

use function auth;
use function redirect;
use function strip_tags;
use function view;

class PostController extends Controller
{
    public function viewSinglePost(Post $post){
        return view('single-post',['post'=>$post]);
    }
    public function showCreatePost(){
        return view('create-post');
    }
    public function storeNewPost(Request $request){
        $incomingFields=$request->validate([
            'title'=>'required',
            'body'=>'required'
        ]);
        $incomingFields['body']=Str::markdown($incomingFields['body']);

        $incomingFields['user_id']=auth()->id();
        $incomingFields['title']=strip_tags($incomingFields['title']);
        $incomingFields['body']=strip_tags($incomingFields['body'],'<br><h1><h3><ul><ol><strong>');

        $post=Post::create($incomingFields);
        return redirect("/posts/{$post->id}")->with('success', 'Your post was created successfully');
    }
}
