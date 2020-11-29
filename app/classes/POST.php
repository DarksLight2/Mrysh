<?php

namespace app\classes;

class POST
{
    public static function get($key)
    {
        if(self::check_isset($key) === true) {

            $return = new \stdClass();
            $return->date_type = gettype($_POST[$key]);

            if($return->date_type !== 'array' && $return->date_type !== 'object') {
                $return->length = strlen($_POST[$key]);
            }

            if($return->date_type === 'array')
                $return->data = (object)$_POST[$key];
            else
                $return->data = $_POST[$key];

            return $return;

        }

        return null;
    }

    public static function compare_value($value, $param = null)
    {
        if(self::check_isset($param)) {
            if (gettype($param) === 'array') {
                $array = self::get($param[0]);

                if ($array->$param[1] === $value)
                    return true;
                else
                    return false;
            } else {
                if ($value === self::get($param)->data)
                    return true;
                else
                    return false;
            }
        }
        return null;
    }

    public static function check_isset($key)
    {
        if(isset($_POST) && isset($_POST[$key])) {
            return true;
        }

        return false;
    }
}