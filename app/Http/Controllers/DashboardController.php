<?php

namespace App\Http\Controllers;

use App\Models\SupportRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct () {
        $this->middleware('auth');
    }

    public function index() {
        // display all unattended support requests
        $support_requests = SupportRequest::where('attended','=','0')->get();

        return view('private.dashboard', compact('support_requests'));
    }

    public function open_request(SupportRequest $support_request) {
        // TODO: open individual request
        return view('private.open_request', compact('support_request'));
    }
}
