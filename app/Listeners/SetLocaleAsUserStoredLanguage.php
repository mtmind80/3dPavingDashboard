<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SetLocaleAsUserStoredLanguage
{
    public function __construct()
    {
        //
    }

    public function handle(Login $event)
    {
        $lang = auth()->user()->language ?? 'en';
        app()->setLocale($lang);
        session()->put('locale', $lang);
    }
}
