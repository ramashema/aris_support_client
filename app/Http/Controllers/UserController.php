<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //login page
    public function index(){
        //TODO: launch the login page
        return view('auth.login');
    }
}
