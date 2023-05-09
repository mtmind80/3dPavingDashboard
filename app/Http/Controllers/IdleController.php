<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class IdleController extends Controller
{
    protected function lockOut()
    {
        if (!session()->get('lockout', false)) {
            session()->put('lockout', true);

            if (!str_contains(URL::previous(), 'lockout')) {
                session()->put('previous_url', URL::previous());
            }
        }

        $data = [
            'seo' => [
                'pageTitlePrefix' => '',
                'pageTitle'       => 'Unlock Screen',
            ],
        ];

        return view('auth.lock-screen', $data);
    }

    protected function unlock(Request $request)
    {
        \Log::info('in IdleController@unlock: '.session()->get('lockout', 'not set'));

        if (Hash::check($request->userpassword, Auth::user()->password)){
            session()->forget('previous_url');
            session()->forget('lockout');

            return ($previous_url = session()->get('previous_url')) ? redirect($previous_url) : redirect('/');
        } else {
            return redirect()->back()->with('error', __('invalid_credentials'));
        }
    }

}
