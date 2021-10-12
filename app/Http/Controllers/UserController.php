<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->except('index', 'login');
    }

    //login page
    public function index(){
        //TODO: launch the login page
        return view('auth.login');
    }

    public function login (Request $request) {
        //TODO: login the user

    }
}
