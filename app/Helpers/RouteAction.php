<?php namespace App\Helpers;

class RouteAction
{
    static public function getControllerFunctionNames() {
        $tokens = explode('\\', strtolower(\Route::currentRouteAction()));

        list($controllerName, $functionName) = explode('@', $tokens[count($tokens)-1]);

        return [
            'controller_name' => str_replace('controller', '', $controllerName),
            'function_name' => $functionName,
        ];
    }

    static public function getControllerName() {
        return self::getControllerFunctionNames()['controller_name'];
    }

    static public function getFunctionName() {
        return self::getControllerFunctionNames()['function_name'];
    }

}
