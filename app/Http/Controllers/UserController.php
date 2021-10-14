<?php

namespace App\Http\Controllers;

use App\Models\SupportRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    private $last_login;

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

        // validdate the data
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // login
        if (!auth()->attempt($request->only('email', 'password'))){
            return back()->with('error', 'Invalid username or password');
        }

        return redirect(route('private.dashboard'));
    }

    public function logout () {
        //TODO: here is where user is logged out from the system

        // update last login time

        $user = User::find(auth()->user()->id);
        $user->last_login = Carbon::now()->toDateTimeString();
        $user->save();

        // then logout
        auth()->logout();
        // redirect to the login page
        return redirect(route('login'))->with('success', 'Successfully logged out');
    }

    public function reset_student_password(User $user, SupportRequest $support_request)
    {
        //TODO: Reset student password

        // reset the password through api request
        $response = Http::get('http://localhost:8001/api/password_reset/'.$user->registration_number)->json();

        if (array_key_exists('success', $response)){
            //update the support request to attended
            $support_request->attended = true;
            $support_request->save();

            return redirect(route('private.dashboard'))->with('success', 'Password reset completed successfully');
        } else{
            return redirect(route('private.dashboard'))->with('error', 'An error occurred when perform password reset, the password was not changed');
        }
    }
}
