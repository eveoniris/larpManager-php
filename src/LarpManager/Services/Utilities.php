<?php
namespace LarpManager\Services;

class Utilities 
{
    public static function stable_uasort(array &$array, $value_compare_func) {
        $index = 0;
        foreach ($array as &$item) {
            $item = array($index++, $item);
        }
        $result = uasort($array, function($a, $b) use($value_compare_func) {
            $result = call_user_func($value_compare_func, $a[1], $b[1]);
            return $result == 0 ? $a[0] - $b[0] : $result;
        });
            foreach ($array as &$item) {
                $item = $item[1];
            }
            return $result;
    }
    
    public static function sortBy($a,$b)
    {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
    
    public static function sortByDesc($a,$b)
    {
        if ($a == $b) {
            return 0;
        }
        return ($a > $b) ? -1 : 1;
    }
}
