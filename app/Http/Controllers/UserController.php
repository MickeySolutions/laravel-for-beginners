<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use function auth;
use function bcrypt;
use function dd;
use function redirect;
use function view;

class UserController extends Controller
{
    public function profile(User $user){
        return view('profile-posts',['user'=>$user]);
    }
    public function logout(){
        auth()->logout();
        return redirect('/')->with('success','You logout successfully.');
    }
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
            return redirect('/')->with('success','You login successfully');
        }else {
            return redirect('/')->with('failure','Your login failed');
        }

    }
    public function register(Request $request){
        $incomingFields=$request->validate([
            'username'=>['required','min:3','max:30',Rule::unique('users','username')],
            'email'=>['required','email',Rule::unique('users','email')],
            'password'=>['required','min:8','confirmed']
        ]);
        $incomingFields['password']=bcrypt($incomingFields['password']);
        $user=User::create($incomingFields);
        auth()->login($user);
        return redirect('/')->with('success','Your account war registered successfully');
    }
}
