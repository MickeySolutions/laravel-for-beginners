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
    public function aboutPage( ): string {
        return '<h1>About page</h1><a href="/">Back Home</a>';
    }
}
