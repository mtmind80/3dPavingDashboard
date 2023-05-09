<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App;
use App\Models\User;

class LocaleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }


    public function lang($locale)
    {
        App::setLocale($locale);
        session()->put('locale', $locale);
        $user = \Auth::user();
        $user->language = $locale;
        $user->save();
        return redirect()->back();

    }
}
