<?php

namespace App\Http\Controllers;

use App\Models\SupportRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct () {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display all unattended support requests
     * @return Application|Factory|View
     */
    public function index():View {
        $support_requests = SupportRequest::where('attended','=','0')->paginate(10);

        return view('private.dashboard', compact('support_requests'));
    }

    /**
     * Open individual request
     * @param SupportRequest $support_request
     * @return Application|Factory|View
     */
    public function open_request(SupportRequest $support_request):View {
        return view('private.open_request', compact('support_request'));
    }

    /**
     * Load attended support requests
     * @return Application|Factory|View
     */
    public function attended ():View {
        $support_requests = SupportRequest::where('attended','=','1')->paginate(10);

        return view('private.dashboard_attended', compact('support_requests'));
    }
}
