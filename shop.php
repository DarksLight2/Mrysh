<?php

require_once './app/configs/systemSettigns.php';

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

require_once './header.php';

echo Shop::ShowMainCategories('Снаряжение','лучшее оружие и доспехи', 'cloth', 'set_quality', ['type' => 'level', 'value' => 0]);
echo Shop::ShowMainCategories('Торговец рунами','установка рун на вещи', 'runes', 'runes', ['type' => 'level', 'value' => 0]);

require_once './footer.php';