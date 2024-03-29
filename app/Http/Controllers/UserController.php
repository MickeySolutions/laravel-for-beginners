<?php

namespace App\Http\Controllers;

use App\Events\OurExampleEvent;
use App\Models\Follow;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;


use Intervention\Image\Facades\Image;

use function auth;
use function back;
use function bcrypt;
use function dd;
use function redirect;
use function str_replace;
use function uniqid;
use function view;

class UserController extends Controller
{
    public function storeAvatar(Request $request){
        $request->validate([
            'avatar'=>'required|image|max:3000'
        ]);
        $user=auth()->user();
        $fileName=$user->id.'-'.uniqid().'.jpg';

        $imgData=Image::make($request->file('avatar'))->fit(120)->encode('jpg');
        Storage::put('public/avatars/'.$fileName,$imgData);

        $oldAvatar=$user->avatar;

        $user->avatar=$fileName;
        $user->save();

        if ($oldAvatar != '/default-profile.jpg'){
            Storage::delete(str_replace("/storage/",'public/',$oldAvatar));
        }
        return back()->with('success','Your avatar was uploaded successfully');
    }
    public function showManageAvatar(){
        return view('manage-avatar');
    }
    private function getSharedData($user){
        if (auth()->check()){
           $currentlyFollowing=Follow::where([['user_id','=',auth()->user()->id],['followeduser','=',$user->id]])->count();
        }
        View::share('sharedData',['followingCount'=>$user->followingTheseUsers()->count(),'followerCount'=>$user->followers()->count(),'postCount'=>$user->posts()->count(),'avatar'=>$user->avatar,'username'=>$user->username,'currentlyFollowing'=>$currentlyFollowing,'user'=>$user]);
    }
    public function profile(User $user){
        $this->getSharedData($user);
        return view('profile-posts',['posts'=>$user->posts()->latest()->get()]);
    }
    public function profileRaw(User $user){
        return response()->json(['theHTML'=>view('profile-posts-only',['posts'=>$user->posts()->latest()->get()])->render(), 'docTitle'=>$user->username."'s Profile"]);
    }

    public function profileFollowers(User $user){
        $this->getSharedData($user);
        return view('profile-followers',['followers'=>$user->followers()->latest()->get()]);
    }
    public function profileFollowersRaw(User $user){
        return response()->json(['theHTML'=>view('profile-followers-only',['followers'=>$user->followers()->latest()->get()])->render(), 'docTitle'=>$user->username."'s Followers"]);
    }
    public function profileFollowing(User $user){
        $this->getSharedData($user);
        return view('profile-following',['following'=>$user->followingTheseUsers()->latest()->get()]);
    }
    public function profileFollowingRaw(User $user){
        return response()->json(['theHTML'=>view('profile-following-only',['following'=>$user->followingTheseUsers()->latest()->get()])->render(), 'docTitle'=>'Who '.$user->username." Follows"]);
    }
    public function logout(){
        event(new OurExampleEvent(['username'=>auth()->user()->username,'action'=>'logout']));
        auth()->logout();
        return redirect('/')->with('success','You logout successfully.');
    }
    public function showHomePage(){
        if(auth()->check()){
            return view('home-feed',['posts'=>auth()->user()->feedPosts()->latest()->paginate(5)]);
        }else{
            $postCount=Cache::remember('postCount',20,function(){
                return Post::count();
            });
            return view('homepage',['postCount'=>$postCount]);
        }
    }
    public function loginApi(Request $request){
        $incomingFields=$request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);
        if (auth()->attempt($incomingFields)){
            $user=User::where('username',$incomingFields['username'])->first();
            $token=$user->createToken('ourapptoken')->plainTextToken;
            return $token;
        }
        return 'Sorry! You have errors at your request.';
    }
    public function login(Request $request){
        $incomingFields=$request->validate([
            'loginUsername'=>'required',
            'loginPassword'=>'required'
        ]);
        if(auth()->attempt(['username'=>$incomingFields['loginUsername'],'password'=>$incomingFields['loginPassword']])){
            $request->session()->regenerate();
            event(new OurExampleEvent(['username'=>auth()->user()->username,'action'=>'login']));
            return redirect('/')->with('success','You login successfully');
        }else {
            return redirect('/')->with('failure','Your login failed');
        }

    }
    public function register(Request $request){

        $incomingFields=$request->validate([
            'username'=>['required','min:3','max:30',Rule::unique('users','username')],
            'email'=>['required','email',Rule::unique('users','email')],
            'password'=>['required','min:8','confirmed'],
        ]);
        $incomingFields['isAdmin']=false;
        $incomingFields['password']=bcrypt($incomingFields['password']);

        $user=User::create($incomingFields);
        auth()->login($user);
        return redirect('/')->with('success','Your account was registered successfully');
    }
}
