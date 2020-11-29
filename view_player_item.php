<?php

require_once './app/configs/systemSettigns.php';

use app\classes\Complects;
use app\classes\GET;
use app\classes\Inventory;
use app\classes\Items;
use app\classes\Shop;
use app\classes\User;

if(User::userData() === false)
{
    ?>
    Раздел сайта доступен для авторизированых игроков.

    <script>
        setTimeout(function (){
            history.back();
        }, 3000)
    </script>
    <?php
    exit();
}

$title = 'Магазин';