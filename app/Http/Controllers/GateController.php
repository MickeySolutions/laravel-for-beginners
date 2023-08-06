<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class GateController extends Controller
{
    public function adminOnly(){
        If (Gate::allows('canViewPage')){
             return view('admin-only');
        }
        return view('not-admin');
    }
}
