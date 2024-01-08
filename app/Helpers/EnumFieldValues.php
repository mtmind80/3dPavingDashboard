<?php

namespace App\Helpers;

class EnumFieldValues
{
    public static function get($table, $field): array
    {
        $type = \DB::select(\DB::raw('SHOW COLUMNS FROM '.$table.' WHERE Field = "'. $field .'"'))[0]->Type;

        preg_match('/^enum\((.*)\)$/', $type, $matches);

        $values = [];

        foreach(explode(',', $matches[1]) as $value){
            $values[] = trim($value, "'");
        }

        return $values;
    }

}
