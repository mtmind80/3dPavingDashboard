<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       // fix for old versions of mysql
        Schema::defaultStringLength(191);


        /**
         *  Custom validation rules:
         */

        Validator::extend('year', 'App\Validators\CustomValidators@validateYear');
        Validator::extend('hex_color_code', 'App\Validators\CustomValidators@validateHexColorCode');
        Validator::extend('play_list_id', 'App\Validators\CustomValidators@validatePlayListId');
        Validator::extend('no_protocol_url', 'App\Validators\CustomValidators@validateNoProtocolUrl');
        Validator::extend('path_file_name', 'App\Validators\CustomValidators@validatePathFileName');
        Validator::extend('non_zero_currency', 'App\Validators\CustomValidators@validateNonZeroCurrency');
        Validator::extend('twitter', 'App\Validators\CustomValidators@validateTwitter');
        Validator::extend('token', 'App\Validators\CustomValidators@validateToken');
        Validator::extend('pay_pal_key', 'App\Validators\CustomValidators@validatePayPalKey');
        Validator::extend('stripe_key', 'App\Validators\CustomValidators@validateStripeKey');
        Validator::extend('route_name_or_full_url', 'App\Validators\CustomValidators@validateRouteNameOrFullUrl');
        Validator::extend('full_url', 'App\Validators\CustomValidators@validateFullUrl');
        Validator::extend('route_name', 'App\Validators\CustomValidators@validateRouteName');
        Validator::extend('password', 'App\Validators\CustomValidators@validatePassword');
        Validator::extend('code', 'App\Validators\CustomValidators@validateCode');

        Validator::extend('positive_between', 'App\Validators\CustomValidators@validatePositiveBetween');

        Validator::replacer('positive_between', function ($message, $attribute, $rule, $parameters) {
            $patterns = ['/\:min/', '/\:max/'];
            $replacements = [$parameters[0], $parameters[1]];

            return preg_replace($patterns, $replacements, $message);
        });

        Validator::extend('text', 'App\Validators\CustomValidators@validateText');
        Validator::extend('plain_text', 'App\Validators\CustomValidators@validatePlainText');
        Validator::extend('slug', 'App\Validators\CustomValidators@validateSlug');
        Validator::extend('identifier', 'App\Validators\CustomValidators@validateIdentifier');
        Validator::extend('file_name', 'App\Validators\CustomValidators@validateFileName');
        Validator::extend('positive', 'App\Validators\CustomValidators@validatePositive');
        Validator::extend('zero_or_positive', 'App\Validators\CustomValidators@validateZeroOrPositive');
        Validator::extend('float', 'App\Validators\CustomValidators@validateFloat');
        Validator::extend('currency', 'App\Validators\CustomValidators@validateCurrency');
        Validator::extend('zip_code', 'App\Validators\CustomValidators@validateZipcCode');
        Validator::extend('postal_code', 'App\Validators\CustomValidators@validatePostalCode');
        Validator::extend('person_name', 'App\Validators\CustomValidators@validatePersonName');
        Validator::extend('phone', 'App\Validators\CustomValidators@validatePhone');
        Validator::extend('credit_card', 'App\Validators\CustomValidators@validateCreditCard');
        Validator::extend('instagram', 'App\Validators\CustomValidators@validateInstagram');
        Validator::extend('address', 'App\Validators\CustomValidators@validateAddress');
        Validator::extend('location', 'App\Validators\CustomValidators@validateLocation');
        Validator::extend('iso_date', 'App\Validators\CustomValidators@validateIsoDate');
        Validator::extend('us_date', 'App\Validators\CustomValidators@validateUsDate');
        Validator::extend('sp_date', 'App\Validators\CustomValidators@validateSpDate');
        Validator::extend('iso_date_time', 'App\Validators\CustomValidators@validateIsoDateTime');
        Validator::extend('us_date_time', 'App\Validators\CustomValidators@validateUsDateTime');
        Validator::extend('any_date', 'App\Validators\CustomValidators@validateAnyDate');
        Validator::extend('time', 'App\Validators\CustomValidators@validateTime');
        Validator::extend('iso_date_time', 'App\Validators\CustomValidators@validateIsoDateTime');
        Validator::extend('us_date_time', 'App\Validators\CustomValidators@validateUsDateTime');
        Validator::extend('sp_date_time', 'App\Validators\CustomValidators@validateSpDateTime');
        Validator::extend('relative_url', 'App\Validators\CustomValidators@validateRelativeUrl');
        Validator::extend('url_segment', 'App\Validators\CustomValidators@validateUrlSegment');
        Validator::extend('friendly_url_segments', 'App\Validators\CustomValidators@validateFriendlyUrlSegments');
        Validator::extend('friendly_url_segments', 'App\Validators\CustomValidators@validateFriendlyUrlSegments');
        Validator::extend('subdomain', 'App\Validators\CustomValidators@validateSubdomain');
        Validator::extend('custom_url_segment', 'App\Validators\CustomValidators@validateCustomUrlSegment');
        Validator::extend('int_percent', 'App\Validators\CustomValidators@validateIntPercent');
        Validator::extend('boolean', 'App\Validators\CustomValidators@validateBoolean');
        Validator::extend('token', 'App\Validators\CustomValidators@validateAlphaNumeric');
        Validator::extend('alpha_numeric', 'App\Validators\CustomValidators@validateAlphaNumeric');
        Validator::extend('lower', 'App\Validators\CustomValidators@validateLower');
        Validator::extend('video_id', 'App\Validators\CustomValidators@validateVideoId');
    }
}
