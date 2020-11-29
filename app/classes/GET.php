<?php

namespace app\classes;

class GET
{
    private function __construct()
    {

    }

    public static function check_isset($key)
    {
        if(isset($_GET[$key]))
            return true;

        return false;
    }

    public static function get($key)
    {
        $return = null;

        if(self::check_isset($key)) {
            $return = new \stdClass();

            $return->data = $_GET[$key];
            $return->type = gettype($_GET[$key]);
            $return->length = strlen($_GET[$key]);
        }

        return $return;
    }
}