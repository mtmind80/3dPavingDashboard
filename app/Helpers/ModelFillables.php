<?php

namespace App\Helpers;

class ModelFillables
{
    public static function getFillables($model)
    {
        return (new $model)->getFillable();
    }

}
