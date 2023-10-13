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
    public function search($term){
        $posts=Post::search($term)->get();
        $posts->load('user:id,username,avatar');
        return $posts;
    }
    public function showEditPost(Post $post){
        return view('edit-post',['post'=>$post]);
    }
    public function updatePost(Post $post, Request $request){
        $incomingFields=$request->validate([
            'title'=>'required',
            'body'=>'required'
        ]);
        $incomingFields['body']=Str::markdown($incomingFields['body']);
        $incomingFields['title']=strip_tags($incomingFields['title']);
        $incomingFields['body']=strip_tags($incomingFields['body'],'<br><h1><h3><ul><ol><strong>');

        $post->update($incomingFields);
        return back()->with('success','The post was successfully update');
    }
    public function delete(Post $post){
        if(auth()->user()->cannot('delete',$post)){
            return redirect("/post/{$post->id}")->with('failure','You are not allowed to delete the post');
        }
        $post->delete();
        return redirect('/profile/'.auth()->user()->username)->with('success','You delete the post successfully');
    }
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
        return redirect("/post/{$post->id}")->with('success', 'Your post was created successfully');
    }
}
