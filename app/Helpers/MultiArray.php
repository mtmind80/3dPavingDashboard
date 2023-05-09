<?php namespace App\Helpers;

class MultiArray
{
    static private function build_sorter($key, $order = 'asc') {
        return function ($a, $b) use ($key, $order) {
            return strtolower($order) == 'asc' ? strnatcmp($a[$key], $b[$key]) : strnatcmp($b[$key], $a[$key]);
        };
    }

    static public function sort($array, $key, $order = 'asc') {
        $tmpArr = $array;

        usort($tmpArr, self::build_sorter($key, $order));

        return $tmpArr;
    }

}
