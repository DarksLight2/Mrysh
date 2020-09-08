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

if(isset($_GET['set_id']) && is_numeric($_GET['set_id']))
{
    $ComplectItems = Shop::GetAllItemsFromShop($_GET['set_id']);

    for($i = 0; $i < 8; $i++)
    {
        echo Shop::ShowItemData($ComplectItems[$i]);
    }
}
else
{
    header('Location: /shop/');
}

require_once './footer.php';
