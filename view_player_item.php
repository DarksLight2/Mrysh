<?php

require_once './app/configs/systemSettigns.php';

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

if(isset($_GET['id']) && is_numeric($_GET['id'])) {

    $item_data = Items::get_item_data($_GET['id'], true);

    $title = $item_data->name;

    $progress = ($item_data->exp / Items::get_exp_to_disassemble($item_data->current_level)) * 100;

    require_once './header.php';
?>

<?php
    require_once './footer.php';
} else
    header($_SERVER["SERVER_PROTOCOL"]." 400");