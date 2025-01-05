<?php

namespace App\Helpers;

class MailParams
{
    static public function get()
    {
        return [
            'mail_from_address' => config('env.MAIL_FROM_ADDRESS', $config['email'] ?? 'no-reply@localhost.com'),
            'mail_from_name' => config('env.MAIL_FROM_NAME', $config['company'] ?? 'LOCALHOST'),
            'app_name' => config('env.APP_NAME'),
            'app_url' => config('env.APP_URL'),
        ];
    }

}
