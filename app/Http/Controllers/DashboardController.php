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
        return view('private.dashboard');
    }
}
