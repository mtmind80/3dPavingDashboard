<?php


//project CalendarDevelopment
//client id
//784244267523-9rr55n0u96mbe43d9ostfgvhf6t8pqc6.apps.googleusercontent.com

//Client secret: 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

return [

    'default_auth_profile' => env('8a603022180fc879194f936b726ec7338f334639', 'service_account'),

    'auth_profiles' => [

        /*
         * Authenticate using a service account.
         */
        'service_account' => [
            /*
             * Path to the json file containing the credentials.
             */
            'credentials_json' => storage_path('app/google-calendar/calendardevelopment-8a603022180f.json'),
        ],

        /*
         * Authenticate with actual google user account.
         */
        'oauth' => [
            /*
             * Path to the json file containing the oauth2 credentials.
             */
            'credentials_json' => storage_path('app/google-calendar/oauth-credentials.json'),

            /*
             * Path to the json file containing the oauth2 token.
             */
            'token_json' => storage_path('app/google-calendar/oauth-token.json'),
        ],
    ],

    /*
     *  The id of the Google Calendar that will be used by default.
     */
    'calendar_id' => env('GOOGLE_CALENDAR_ID'),

     /*
     *  The email address of the user account to impersonate.
     */
    'user_to_impersonate' => env('GOOGLE_CALENDAR_IMPERSONATE'),
];
