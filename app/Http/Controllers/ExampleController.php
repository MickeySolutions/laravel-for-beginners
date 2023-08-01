<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{

    public function homePage( ) {
        $firstName="Milancho";
        $lastName="Ivanov";
        $petsNames=['Miki','Kiki','Riki','Piki'];
        return view('homepage',[
            'firstName'=>$firstName,
            'lastName'=>$lastName,
            'petsNames'=>$petsNames
        ]);
    }
    public function aboutPage( ) {
        return view('single-post');
    }
}
