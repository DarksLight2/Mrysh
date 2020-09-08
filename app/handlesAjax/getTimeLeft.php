<?php

require_once '../configs/autoLoadClasses.php';

use app\classes\User;

function past_time($unixTime)
{
    $date = '';

    $days = floor((time() - $unixTime) / 86400);
    $h = floor((time() - $unixTime) / 3600);
    $m = floor((time() - $unixTime) / 60);
    $s = floor(time() - $unixTime);

    if($s <= 60)
    {
        $date = $s.' секунду назад';
    }
    elseif($m >= 1 && $m <= 59)
    {
        $date = $m.' минут '.floor($s - (60 * $m)).' секунд назад';
    }
    elseif ($h >= 1 && $h <= 23)
    {
        $date = $h.' часов '.floor(($s - ($h * 3600)) / 60).' минут '.floor($s - (60 * $m)).' секунд назад';
    }
    elseif ($days >= 1)
    {
        $date = $days.' дней '.floor(($s - $days * 86400) / 24).' часов '.floor(($s - $h * 3600) / 60).' минут '.floor($s - (60 * $m)).' секунд назад';
    }

    return $date;
}

function time_left($unixTime)
{

    $result = '';

    $days = floor(($unixTime - time()) / 86400);
    $h = floor(($unixTime - time()) / 3600);
    $m = floor(($unixTime - time()) / 60);
    $s = floor($unixTime - time());

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

if(isset($_POST))
{
    if($_POST['where'] == 'arena')
    {
        if(User::userData())
        {
            echo time_left(User::userData()['arena_cooldown']);
        }
    }
}