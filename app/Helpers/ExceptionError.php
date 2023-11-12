<?php

namespace App\Helpers;

use Exception;

use Illuminate\Support\Facades\Log;

class ExceptionError
{
    public static function handleError(Exception $e, $redirectTo = null, $errorMessage = 'Exception error')
    {
        if (app()->environment() !== 'local') {
            Log::error(__CLASS__ . ' - ' . $e->getMessage());
            return !$redirectTo ? redirect()->back()->with('error', $errorMessage) : redirect()->to(($redirectTo))->with('error', $errorMessage);
        }
        throw $e;
    }

    public static function handleAjaxError(Exception $e, $errorMessage = 'Exception error')
    {
        if (app()->environment() === 'local') {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        } else {
            Log::error(__CLASS__ . ' - ajax - ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $errorMessage,
            ];
        }
    }

    public static function returnError(Exception $e, $errorMessage = 'Exception error')
    {
        if (app()->environment() === 'local') {
            return $e->getMessage();
        } else {
            Log::error(__CLASS__ . ' - ' . $e->getMessage());
            return $errorMessage;
        }
    }

}
