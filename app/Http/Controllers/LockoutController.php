<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\WorkOrder;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Log;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Currency;

class LockoutController extends Controller
{
    public function lockout() {
        if (!session()->get('lockout', false)) {
            session()->put('lockout', true);

            if (!str_contains(URL::previous(), 'lockout')) {
                session()->put('previous_url', URL::previous());
            }
        }

        return view('lock-screen');
    }

    public function endlockout(Request $request) {
        if (Hash::check($request->userpassword, Auth::user()->password)){
            session()->forget('previous_url');
            session()->forget('lockout');

            return ($previous_url = session()->get('previous_url')) ? redirect($previous_url) : redirect('/');
        } else {
            return redirect()->back()->with('error', __('invalid_credentials'));
        }
    }

    public function logout() {
        session()->flush();
        auth()->logout();

        return redirect('login');
    }

}
