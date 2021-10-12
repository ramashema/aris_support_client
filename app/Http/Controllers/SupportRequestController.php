<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class SupportRequestController extends Controller
{

    public function index()
    {
        // TODO: Launch the request page for user to fill the form
        return view("request");
    }

    /**
     * @throws ValidationException
     */
    public function process_request(Request $request)
    {
        // TODO: Process the request after user submission
        // validate receive data
        $this->validate($request, [
            'registration_number' => 'required',
            'email' => 'required|email',
            'descriptions' => 'max:256',
            'others' => 'max:256'
        ]);

        $registration_number = $request->input('registration_number');
        $response = Http::get('http://localhost:8001/api/student/'.$registration_number)->json();

        if (!array_key_exists("error", $response)){
            # TODO: If the response return no error, insert data in the support database
            $user = User::where('registration_number', $registration_number)->first();

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
            }

        } else{
            return view('feedback.success')->with('error', 'Wrong registration number');
        }
    }
}
