<?php


namespace app\classes;


class SiteSettigns
{
    public static function past_time($unixTime, $params = null, $auto_switcher = false)
    {
        $return = [
            's' => 1,
            'i' => 1,
            'h' => 1,
            'd' => 1
        ];

        if($params !== null) {
            $return = [
                's' => 0,
                'i' => 0,
                'h' => 0,
                'd' => 0
            ];

            if(gettype($params) === 'array') {
                foreach ($params as $key => $param) {
                    foreach ($return as $type => $value) {
                        if($param === $type) {
                            $return[$type] = 1;
                        }
                    }
                }
            }
        }

        $date = '';

        $days = floor((time() - $unixTime) / 86400);
        $h = floor((time() - $unixTime) / 3600);
        $m = floor((time() - $unixTime) / 60);
        $s = floor(time() - $unixTime);

        if($auto_switcher === true) {

            if($days >= 1) {
                $return['m'] = 0;
                $return['s'] = 0;
            } else {
                $return['m'] = 1;
                $return['d'] = 0;
            }

            if($h >= 1) {
                $return['s'] = 0;
            } else {
                $return['s'] = 1;
                $return['h'] = 0;
            }

        }

        if($return['d'] === 1) {

            if($auto_switcher === true && $s >= 60 && $return['s'] === 1) {
                $return['s'] = 0;
            }

            if($auto_switcher === true && $m >= 60 && $return['i'] === 1) {
                $return['i'] = 0;
            }

            $h = floor(($s / 3600) - ($days * 24));
            $m = floor(($s - $h * 3600) / 60);
            $s = floor($s - (60 * $m));

            $date .=  $days.' дней ';
        }

        if ($return['h'] === 1) {

            $m = floor(($s - $h * 3600) / 60);
            $s = floor($s - (60 * $m));

            $date .=  $h.' часов ';
        }

        if($return['i'] === 1) {
            $s = floor($s - (60 * $m));

            $date .=  $m.' минут ';
        }

        if ($return['s'] === 1) {
            $date .= $s.' секунд ';
        }


        return $date.' назад';
    }

    public static function time_left($unixTime, $Unix = true)
    {

        $result = '';

        if($Unix === true)
        {
            $days = floor(($unixTime - time()) / 86400);
            $h = floor(($unixTime - time()) / 3600);
            $m = floor(($unixTime - time()) / 60);
            $s = floor($unixTime - time());
        }
        else
        {
            $days = floor($unixTime / 86400);
            $h = floor($unixTime / 3600);
            $m = floor($unixTime / 60);
            $s = floor($unixTime);
        }


        if($s <= 60)
        {
            $result = $s.' секунд';
        }
        elseif($m >= 1 && $m <= 59)
        {
            $result = $m.' минут '.floor($s - (60 * $m)).' секунд';
        }
        elseif ($h >= 1 && $h <= 23)
        {
            $result = $h.' часов '.floor(($s - ($h * 3600)) / 60).' минут '.floor($s - (60 * $m)).' секунд';
        }
        elseif ($days >= 1)
        {
            $result = $days.' дней '.floor(($s - $days * 86400) / 24).' часов '.floor(($s - $h * 3600) / 60).' минут '.floor($s - (60 * $m)).' секунд';
        }

        if($s <= 0)
        {
            $result = 'end';
        }

        return $result;
    }

    public static function Numbers($Num)
    {

        if($Num < 1000)
        {
            $Return = $Num;
        }
        elseif($Num > 999 && $Num < 10000)
        {
            $Return = substr($Num, 0, 1).','.substr($Num, 1, 3);
        }
        elseif ($Num > 9999 && $Num < 100000)
        {
            $Return = substr($Num, 0, 2).','.substr($Num, 2, 3);
        }
        elseif ($Num > 99999 && $Num < 1000000)
        {
            $Return = substr($Num, 0, 3).','.substr($Num, 3, 3);
        }
        elseif ($Num > 999999 && $Num < 10000000)
        {
            $Return = substr($Num, 0, 1).','.substr($Num, 1, 3).','.substr($Num, 4, 3);
        }
        elseif ($Num > 9999999 && $Num < 100000000)
        {
            $Return = substr($Num, 0, 2).','.substr($Num, 2, 3).','.substr($Num, 5, 3);
        }
        elseif ($Num > 99999999 && $Num < 1000000000)
        {
            $Return = substr($Num, 0, 3).','.substr($Num, 3, 3).','.substr($Num, 6, 3);
        }
        elseif ($Num > 999999999 && $Num < 10000000000)
        {
            $Return = substr($Num, 0, 1).','.substr($Num, 1, 3).','.substr($Num, 4, 3).','.substr($Num, 7, 3);
        }
        elseif ($Num > 9999999999 && $Num < 100000000000)
        {
            $Return = substr($Num, 0, 2).','.substr($Num, 2, 3).','.substr($Num, 5, 3).','.substr($Num, 8, 3);
        }
        elseif ($Num > 99999999999 && $Num < 1000000000000)
        {
            $Return = substr($Num, 0, 3).','.substr($Num, 3, 3).','.substr($Num, 6, 3).','.substr($Num, 9, 3);
        }
        else
        {
            $Return = 'Некорректное число. <small><small>['.$Num.']</small></small>';
        }

        return $Return;
    }

    public static function debug($str)
    {
        echo '<pre>';
        var_dump($str);
        echo '</pre>';
    }
}