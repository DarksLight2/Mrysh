<?php

require_once '../configs/autoLoadClasses.php';

use app\classes\Lair;
use app\classes\User;
use app\classes\SiteSettigns;

if(isset($_POST))
{
    if($_POST['where'] == 'arena')
    {
        if(User::userData())
        {
            echo SiteSettigns::time_left(User::userData()['arena_cooldown']);
        }
    }
    elseif($_POST['where'] == 'lair')
    {
        if(User::userData())
        {
            echo SiteSettigns::time_left(Lair::GetData()['cooldown']);
        }
    }
}