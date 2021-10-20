<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
//    protected $redirectTo = RouteServiceProvider::HOME;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'privilege' => ['required', 'string']
//            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data): User
    {
        return User::create([
            'registration_number' => md5($data['first_name'].$data['middle_name'].$data['surname']),
            'name' => strtoupper($data['first_name']." ".$data['middle_name']." ".$data['surname']),
            'email' => $data['email'],
            'privilege' => $data['privilege'],
            'password' => Hash::make("MU_".strtoupper(str_shuffle($data['surname'].$data['first_name']))),
        ]);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validator($request->all())->validate();

        try{
            event(new Registered($user = $this->create($request->all())));
        }catch (\Exception $exception){
            return redirect()->back()->with('error', 'Error occurred during user registration! Check your network connection');
        }


        // this check if user has been created
        if($user){
            return redirect()->back()->with('success', 'User successfully registered!');
        }else{
            return redirect()->back()->with('error', 'User registration failed!');
        }

    }
}
