<?php

namespace app\classes;

class SMS
{
    private static $login    = '415137_darkslight2';
    private static $password = 'Makc1120m333';
    private static $sender   = 'DarksLigth2';
    private static $url      = 'http://sms.cityhost.ua/send.php';
    private static $response = null;

    private static function change_sender($sender)
    {
        self::$sender = $sender;
    }

    private static function set_auth_data()
    {
        self::$url .= '?login='.self::$login.'&psw='.urlencode(self::$password);
    }

    private static function set_number($number)
    {
        self::$url .= '&phones='.urlencode($number);
    }

    private static function set_message($msg)
    {
        self::$url .= '&mes='.urlencode($msg);
    }

    private static function get_cost()
    {
        self::$url .= '&cost=1';
    }

    private static function send()
    {
        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, self::$url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $out = curl_exec($curl);
            self::$response = $out;
            curl_close($curl);
        }

        return self::$response;
    }
}