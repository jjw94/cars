<?php
namespace Cars;

class Constants {
    static function sanitizeString($str) {
        $str = FILTER_VAR($str, FILTER_SANITIZE_STRING);
        return $str;
    }

    static function sanitizeArr($arr) {
        foreach($arr as &$val) {
            if(is_array($val)) {
                $val = self::sanitizeArr($val);
            } else {
                $val = self::sanitizeString($val);
            }
        }
        return $arr;
    }

    static function escapeString($str) {
        $str = strip_tags($str);
        $str = htmlspecialchars($str);
        return $str;
    }

    static function escapeArr($arr) {
        foreach($arr as &$val) {
            if(is_array($val)) {
                $val = self::escapeArr($val);
            } else {
                $val = self::escapeString($val);
            }
        }
        return $arr;
    }
}
