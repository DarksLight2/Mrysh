<?php


namespace app\classes;


class SiteSettigns
{
    private function __construct()
    {
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
}