<?php

namespace App\Http\Controllers;

use App\Mail\AccountActivationMail;
use App\Mail\AccountPasswordReset;
use App\Mail\ActivationDeactivationMail;
use App\Mail\SupportMail;
use App\Mail\UserDeletionNotification;
use App\Models\SupportRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use \Exception;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->except('index', 'login', 'reset_password_page', 'process_password_reset_request');
    }

    /**
     * Launch the login page
     * @return Application|Factory|View
     */
    public function index(){
        return view('auth.login');
    }

    /**
     * Launch the login page
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function login (Request $request) {
        // validate the data
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // login
        if (!auth()->attempt($request->only('email', 'password'))){
            return back()->with('error', 'Invalid username or password');
        }

        // check if user has been verified before, if not update verification date upon setup of initial password
        if (!auth()->user()->email_verified_at)
        {
            auth()->user()->email_verified_at = Carbon::now();
            auth()->user()->save();
        }

        // set up initial password if is not set, if the password is set that means user is verified then send user to dashboard
        if (!auth()->user()->initial_password_isset || auth()->user()->has_password_request)
        {
            return $this->create_user_password_page();
        }

        // update last login
        auth()->user()->last_login = Carbon::now()->toDateTimeString();
        auth()->user()->save();

        return  redirect(route('private.dashboard'));

    }

    /**
     * Login out the user from the system
     * @return Application|RedirectResponse|Redirector
     */
    public function logout () {
        // update last login time
        $user = User::find(auth()->user()->id);
        $user->last_login = Carbon::now()->toDateTimeString();
        $user->save();

        // then logout
        auth()->logout();
        // redirect to the login page
        return redirect(route('auth.login'))->with('success', 'Successfully logged out');
    }

    /**
     * Launch user registration page
     * @return Application|Factory|View
     */
    public function user_registration()
    {
        return view('auth.register');
    }

    /**
     * Process user registration
     * @return Application|RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function process_user_registration(Request $request){
        // receive data and validate the data
        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'privilege' => ['required', 'string']
        ]);

        // create user password
        $password = "MU_".strtoupper(str_shuffle($request->input('surname').$request->input('first_name')));

        // register user to the database
        $user = User::create([
            'registration_number' => md5($request->input('first_name').$request->input('middle_name').$request->input('surname')),
            'name' => strtoupper($request->input('first_name')." ".$request->input('middle_name')." ".$request->input('surname')),
            'email' => $request->input('email'),
            'privilege' => $request->input('privilege'),
            'password' => Hash::make($password),
        ]);

        if($user){
            // send an email containing user registration data
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

    /**
     * Reset student password
     * @param SupportRequest $support_request
     * @return Application|RedirectResponse|Redirector
     */
    public function reset_student_password(SupportRequest $support_request)
    {
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

    /**
     * Launch initial password creation page
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function create_user_password_page(){
        //check if the request is for password reset
        if(auth()->user()->has_password_request){
            $success = 'Please create new password to proceed!';
            return view('auth.create_user_password')->with('success', $success);
        } else{
            // load password creation page
            if(auth()->user()->initial_password_isset){
                $error = 'You have already created a password, if you have forgotten your password please use reset password link on your profile!';
                return redirect(route('private.dashboard'))->with('error', $error);
            } else{
                $success = 'You have successfully verified your account, please create your password to proceed!';
                return view('auth.create_user_password')->with('success', $success);
            }
        }
    }

    /**
     * Process initial password creation
     * @param Request $request
     * @param User $user
     * @return Application|RedirectResponse|Redirector
     */
    public function create_user_password(Request $request, User $user){
        if ($user->initial_password_isset){
           return redirect(route('private.dashboard'))->with('error', 'You have already created a password, if you have forgotten your password please use reset password link on your profile!');
        } else{
            $request->validate([
                'password' => 'required|string|min:8|confirmed'
            ]);

            // store user after validation and set user that is active
            $user->password = Hash::make($request->input('password'));
            $user->initial_password_isset = true;
            $user->has_password_request = false;
            $user->is_active = true;
            $user->last_login = Carbon::now()->toDateTimeString();
            $user->save();

            return redirect(route('private.dashboard'))->with('success', 'Password created successfully!');
        }
    }

    /**
     * Get all users from the database, and paginate 10 user in every page
     * @return Application|Factory|View
     */
    public function all_users(){
        $users = User::where('privilege', 'admin')->orWhere('privilege', 'support_team')->paginate(10);

        return view('private.users', compact('users'));
    }

    /**
     * Get individual user from the database
     * @param User $user
     * @return Application|Factory|View
     */
    public function show_user(User $user){
        return view('private.user', compact('user'));
    }

    /**
     * Open confirmation page for user to confirm if they do want to deactivate or activate use
     * @param User $user
     * @return Application|Factory|View
     */
    public function activation_deactivation_confirmation(User $user){
        return view('private.activate_deactivate_confirmation')->with('user', $user);
    }

    /**
     * Processing the activation or deactivation user depend on the user status
     * @param User $user
     * @return RedirectResponse
     */
    public function activation_deactivation(User $user): RedirectResponse
    {
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

    /**
     * Open confirmation page for user to confirm if they really want to delete system user
     * @param User $user
     * @return Application|Factory|View
     */
    public function user_delete_confirmation(User $user){
        return view('private.delete_user_confirmation')->with('user', $user);
    }

    /**
     * Delete user from the database
     * @param User $user
     * @return Application|RedirectResponse|Redirector
     */
    public function delete_user(User $user){
        $user_email = $user->email;
        $user_name = $user->name;

        // if user has been deleted the email them to let them know
        if($user->delete()){
            // send and email to user
            $email_details = [
                'title' => 'You account has been deleted',
                'name' => $user_name
            ];

            try{
                Mail::to($user_email)->send(new UserDeletionNotification($email_details));
            }catch (Exception $exception){
                return redirect(route('private.users_list'))->with('error', 'User account deleted, but failed to send notification email to the user. Consider checking network connection');
            }
            return redirect(route('private.users_list'))->with('success', 'User account deleted successful');
        } else{
            return redirect()->back()->with('error', 'Failed to delete user');
        }
    }

    /**
     * @param $user
     */
    private function update_last_login($user){
        // update last login
        $user = auth()->user();
        $user->last_login = Carbon::now()->toDateTimeString();
        $user->save();
    }

    /**
     * Launch the page where they can provide email for password reset functionality
     * @return Application|Factory|View
     */
    public function reset_password_page () {
        $success = "You must provide the email used during account creation.";
        return view('auth.passwords.reset')->with('success', $success);
    }

    /**
     * Process password reset
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
     * @throws ValidationException
     */

    public function process_password_reset_request (Request $request) {
        // validate the inputs
        $this->validate($request, [
            'email' => 'required|email'
        ]);

        // check if user email exist in the database
        $user = User::where('email', $request->input('email'))->first();

        if ($user){
            // put together important variable
            $password = str_shuffle('M_'.$user->email.'_U');
            $user->password = Hash::make($password);
            $user->has_password_request = true;
            $user->initial_password_isset = false;
             $user->password_reset_at = Carbon::now();
            $user->save();

            // prepare email contents
            $details = [
                'name' => $user->name,
                'password' => $password
            ];

            // try to send temporary password to the user email
            try {
                Mail::to($user->email)->send(new AccountPasswordReset($details));
            } catch (Exception $exception){
                return redirect()->back()->with('error', 'Failed to sent password reset info, please contact system administrator.');
            }
        }
        // return user to the login page
        return redirect(route('auth.login'))->with('success', 'If provided the correct email, password reset instructions will be sent to your email inbox.');
    }
}
