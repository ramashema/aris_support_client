<?php

namespace App\Http\Controllers;

use App\Mail\OtherRequestsMail;
use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use \Exception;
use Illuminate\Validation\ValidationException;

class SupportRequestController extends Controller
{
    /**
     * Launch the request page for user to fill the form
     * @return Application|Factory|View
     */
    public function index()
    {
        return view("request");
    }

    /**
     * Process the request after user submission
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     * @throws ValidationException
     */
    public function process_request(Request $request)
    {
        // validate receive data
        $this->validate($request, [
            'registration_number' => 'required',
            'email' => 'required|email',
            'descriptions' => 'max:256|required',
            'others' => 'max:256'
        ]);

        try{
            $response = Http::get('http://localhost:8001/api/student/'.$request->input('registration_number'))->json();

            if (!array_key_exists("error", $response)){
                # TODO: If the response return no error, insert data in the support database


                $user = User::where('registration_number', $request->input('registration_number'))->first();

                //  here we should check if email match with the registration number for those returning users

                if ($user == null){
                    # create user if not available
                    $created_user = User::create([
                        'registration_number'=>$request->input('registration_number'),
                        'name' => $response['szFirstName'].' '.$response['szSurName'],
                        'email' => $request->input('email')
                    ]);

                    // insert the support to the support table
                    if ($request->input('descriptions') == "others"){
                        $support_request = $created_user->support_requests()->create([
                            //'descriptions' => $request->input('descriptions')
                            'descriptions' => $request->input("others")
                        ]);
                    } else {
                        $support_request = $created_user->support_requests()->create([
                            //'descriptions' => $request->input('descriptions')
                            'descriptions' => $request->input('descriptions')
                        ]);
                    }


                    if ($support_request != null) {
                        return view('feedback.success')->with('success', 'If you provided correct registration number, you will get notified using your email address about your reported case');
                    } else{
                        return view('feedback.success')->with('error', 'Something went wrong, Contact Administrator [rshemahonge@mzumbe.ac.tz]');
                    }
                } else{
                    // if user exists there is no need to create this user

                    // check if the email provided by the user matches the email exists in the system.
                    if($user->email == $request->input('email')) {

                        // just create the support description
                        if ($request->input('descriptions') == "others"){
                            $support_request = $user->support_requests()->create([
                                'descriptions' => $request->input("others")
                            ]);
                        } else {
                            $support_request = $user->support_requests()->create([
                                'descriptions' => $request->input('descriptions')
                            ]);
                        }

                        if ($support_request != null) {
                            return view('feedback.success')->with('success', 'If you provided correct registration number, you will get notified using your email address about your reported case');
                        } else{
                            return view('feedback.success')->with('error', 'Something went wrong, Contact Administrator [rshemahonge@mzumbe.ac.tz]');
                        }
                    } else {
                        // if the email does not match
                        return redirect()->back()->with('error', 'The email you provided does not match the email used in your earlier request(s), please use the email you used in your earlier request(s) or contact administrator');
                    }
                }

            } else{
                //return view('feedback.success')->with('error', 'Wrong registration number');
                return redirect()->back()->with('error', 'Wrong registration number, please verify your registration number and try again!');
            }
        } catch (ConnectionException $connectionException){
            return redirect()->back()->with('error', 'Failed to verify your registration number, please contact administrator!');
        }

    }

    /**
     * This open the aris and update the database to attended case
     * @param SupportRequest $support_request
     * @return RedirectResponse
     */
    public function attend_other_support (SupportRequest  $support_request): RedirectResponse
    {
        // prepare variables to be used
        $registration_number = $support_request->user->registration_number;
        $user_full_name = $support_request->user->name;
        $user_email = $support_request->user->email;

        // prepare the details for the email
        $details_to_email = [
            'title' => 'ARIS Support Request',
            'full_name' => $user_full_name,
            'username' => $registration_number,
        ];

        // send email
        try{
            Mail::to($user_email)->send(new OtherRequestsMail($details_to_email));
            //updated to attended
            $support_request->attended = true;
            $support_request->save();

            return redirect()->back()->with('success', 'You can now click open aris for further actions');
        }catch (Exception $exception){
            return redirect()->back()->with('error', 'There is a problem with sending a feedback email, contact the administrator');
        }
    }
}
