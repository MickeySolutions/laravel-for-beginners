<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use function auth;
use function bcrypt;
use function dd;
use function view;

class UserController extends Controller
{
    public function showHomePage(){
        if(auth()->check()){
            return view('home-feed');
        }else{
            return view('homepage');
        }
    }
    public function login(Request $request){
        $incomingFields=$request->validate([
            'loginUsername'=>'required',
            'loginPassword'=>'required'
        ]);
        if(auth()->attempt(['username'=>$incomingFields['loginUsername'],'password'=>$incomingFields['loginPassword']])){
            $request->session()->regenerate();
            return view('home-feed');
        }else {
            return view('homepage');
        }

    }
    public function register(Request $request){
        $incomingFields=$request->validate([
            'username'=>['required','min:3','max:30',Rule::unique('users','username')],
            'email'=>['required','email',Rule::unique('users','email')],
            'password'=>['required','min:8','confirmed']
        ]);
        $incomingFields['password']=bcrypt($incomingFields['password']);
        User::create($incomingFields);
        return 'Hello from register function';
    }
}
