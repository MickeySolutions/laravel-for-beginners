<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

use function auth;
use function strip_tags;
use function view;

class PostController extends Controller
{
    public function showCreatePost(){
        return view('create-post');
    }
    public function storeNewPost(Request $request){
        $incomingFields=$request->validate([
            'title'=>'required',
            'body'=>'required'
        ]);
        $incomingFields['user_id']=auth()->id();
        $incomingFields['title']=strip_tags($incomingFields['title']);
        $incomingFields['body']=strip_tags($incomingFields['body']);

        Post::create($incomingFields);
    }
}
