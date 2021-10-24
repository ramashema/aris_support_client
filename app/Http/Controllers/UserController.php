<?php

namespace App\Http\Controllers;

use App\Mail\AccountActivationMail;
use App\Mail\ActivationDeactivationMail;
use App\Mail\SupportMail;
use App\Models\SupportRequest;
use App\Models\User;
use Carbon\Carbon;
use Dotenv\Validator;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use \Exception;
use Illuminate\Validation\ValidationException;

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

    /**
     * @throws ValidationException
     */
    public function login (Request $request) {
        //TODO: login the user

        // validate the data
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // login
        if (!auth()->attempt($request->only('email', 'password'))){
            return back()->with('error', 'Invalid username or password');
        }

        if (!auth()->user()->email_verified_at)
        {
            $user = auth()->user();
            $user->email_verified_at = Carbon::now();
            $user->save();
        }

        if (!auth()->user()->initial_password_isset)
        {
            return $this->create_user_password_page();

        } else{
            return  redirect(route('private.dashboard'));
        }
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
        return redirect(route('auth.login'))->with('success', 'Successfully logged out');
    }

    public function user_registration()
    {
        return view('auth.register');
    }

    /**
     * @throws ValidationException
     */
    public function process_user_registration(Request $request){
        //TODO: Register user of the system

        # receive data and validate the data
        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'privilege' => ['required', 'string']
        ]);

        # create user password
        $password = "MU_".strtoupper(str_shuffle($request->input('surname').$request->input('first_name')));

        # register user to the database
        $user = User::create([
            'registration_number' => md5($request->input('first_name').$request->input('middle_name').$request->input('surname')),
            'name' => strtoupper($request->input('first_name')." ".$request->input('middle_name')." ".$request->input('surname')),
            'email' => $request->input('email'),
            'privilege' => $request->input('privilege'),
            'password' => Hash::make($password),
        ]);

        if($user){
            # send an email containing user registration data
            $user_detail = [
                'name' => $request->input('first_name')." ".$request->input('surname'),
                'password' => $password,
                'privilege' => $request->input('privilege'),
                'email' => $request->input('email')
            ];

            try{
                Mail::to($request->input('email'))->send(new AccountActivationMail($user_detail));
            } catch (Exception $exception){
                return redirect()->back()->with('error', 'Failed to send account activation email to user, please consider checking network connection');
            }
            return redirect(route('private.dashboard'))->with('success', 'User registration successful');

        } else{
            return redirect()->back()->with('error', 'User registration failed!');
        }
    }

    public function reset_student_password(SupportRequest $support_request)
    {
        //TODO: Reset student password
        $registration_number = $support_request->user->registration_number;
        $user_full_name = $support_request->user->name;
        $user_email = $support_request->user->email;

        // reset the password through api request --> try and catch block can be here

        try{

            $response = Http::get('http://localhost:8001/api/password_reset/'.$registration_number)->json();

            // check if password reset is success
            if (array_key_exists('success', $response)){

                //update the support request to attended
                $support_request->attended = true;
                $support_request->save();

                // send email to the user telling about successful password reset
                $details_to_email = [
                    'title' => 'ARIS password reset request!',
                    'full_name' => $user_full_name,
                    'username' => $registration_number,
                    'password' => 'YOUR SURNAME in CAPITAL LETTERS'
                ];

                // Try to send an email
                try {
                    Mail::to($user_email)->send(new SupportMail($details_to_email));
                    return redirect(route('private.dashboard'))->with('success', 'Password reset completed successfully');
                } catch (Exception $exception){
                    return redirect()->back()->with('error', 'There is a problem with sending and email, contact the administrator!');
                }
            } else{
                return redirect(route('private.dashboard'))->with('error', 'An error occurred when perform password reset, the password was not changed');
            }

        }catch (ConnectionException $connectionException){
            return redirect()->back()->with('error','Failed to connect to the server, please contact the administrator');
        }
    }

    public function create_user_password_page(){
        // load password creation page
        if(auth()->user()->initial_password_isset){
            $error = 'You have already created a password, if you have forgotten your password please use reset password link on your profile!';
            return redirect(route('private.dashboard'))->with('error', $error);
        } else{
            $success = 'You have successfully verified your account, please create your password to proceed!';
            return view('auth.create_user_password')->with('success', $success);
        }
    }

    public function create_user_password(Request $request, User $user){
        //TODO: force user to change password after initial login
        if ($user->initial_password_isset){
           return redirect(route('private.dashboard'))->with('error', 'You have already created a password, if you have forgotten your password please use reset password link on your profile!');
        } else{
            $request->validate([
                'password' => 'required|string|min:8|confirmed'
            ]);

            // store user after validation
            $user->password = Hash::make($request->input('password'));
            $user->initial_password_isset = true;
            $user->save();

            return redirect(route('private.dashboard'))->with('success', 'Password created successfully!');
        }
    }

    public function all_users(){
        // TODO: Get all users with support team privilege and show them to the page
        $users = User::where('privilege', 'support_team')->paginate(10);

        return view('private.users', compact('users'));
    }

    public function show_user(User $user){
        // TODO: Get individual user from the database
        return view('private.user', compact('user'));
    }

    public function delete_user(User $user){
        //TODO: delete user from the database
    }

    public function activation_deactivation_confirmation(User $user){
        //TODO: open confirmation page for user to confirm if they do want to deactivate or activate user
        return view('private.activate_deactivate_confirmation')->with('user', $user);
    }

    public function activation_deactivation(User $user): \Illuminate\Http\RedirectResponse
    {
        //TODO: processing the activation or deactivation user depend of the user status

        if ($user->initial_password_isset){

            // check if user is active upon activate/deactivate button click
            if ($user->is_active){
                // user is active therefore you need to deactivate
                $user->is_active = false;
                $user->password = "";
                $user->save();

                // Send email telling user that their account has been deactivated
                $email_details = [
                    'title' => 'Account Deactivated',
                    'name' => $user->name,
                    'password' => false
                ];

                try{
                    Mail::to($user->email)->send(new ActivationDeactivationMail($email_details));
                } catch (Exception $exception){
                    return redirect(route('private.user', $user))->with('error', 'User account deactivated, but failed to send notification email to the user. Consider checking network connection');
                }

                return redirect(route('private.user', $user))->with('success', 'User deactivation successful');
            } else {
                // user is deactivated then you need to activate
                $user->is_active = true;
                $user->password = strtoupper(str_shuffle($user->email));
                $user->save();

                // Send email telling user that their account has been activated
                $email_details = [
                    'title' => 'Account Deactivated',
                    'name' => $user->name,
                    'password' => $user->password
                ];

                try{
                    Mail::to($user->email)->send(new ActivationDeactivationMail($email_details));
                } catch (Exception $exception){
                    return redirect(route('private.user', $user))->with('error', 'User account activated, but failed to send notification email to the user. Consider checking network connection');
                }

                return redirect(route('private.user', $user))->with('success', 'User activated successful');
            }

        } else{
            // For user who has never logged in can only be deactivated
            $user->is_active = false;
            $user->password = "";
            $user->save();

            // Send email telling user that their account has been deactivated
            $email_details = [
                'title' => 'Account Deactivated',
                'name' => $user->name,
                'password' => false
            ];

            try{
                Mail::to($user->email)->send(new ActivationDeactivationMail($email_details));
            } catch (Exception $exception){
                return redirect(route('private.user', $user))->with('error', 'User account deactivated, but failed to send notification email to the user. Consider checking network connection');
            }

            return redirect(route('private.user', $user))->with('success', 'User deactivation successful');
        }
    }
}
