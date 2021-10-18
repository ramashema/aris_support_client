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
        $support_requests = SupportRequest::where('attended','=','0')->paginate(10);

        return view('private.dashboard', compact('support_requests'));
    }

    public function open_request(SupportRequest $support_request) {
        // TODO: open individual request
        return view('private.open_request', compact('support_request'));
    }

    public function attended () {
        $support_requests = SupportRequest::where('attended','=','1')->paginate(10);

        return view('private.dashboard_attended', compact('support_requests'));
    }
}
