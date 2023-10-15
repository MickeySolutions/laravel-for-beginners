<?php

namespace App\Http\Controllers;

use App\Events\ChatMessage;
use Illuminate\Http\Request;

use function broadcast;

class ChatController extends Controller
{
    public function publisherIntegration(Request $request){
//        $formFields=$request->validate([
//            'textvalue'=>'required'
//        ]);
//        If (!trim(strip_tags($formFields['textvalue']))){
//            return response()->noContent();
//        }
//        broadcast(new ChatMessage(['username'=>auth()->user()->username,'textvalue'=>strip_tags($request->textvalue),'avatar'=>auth()->user()->avatar]))->toOthers();
//        return response()->noContent();
    }
}
