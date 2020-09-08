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

if( ! isset($_GET['complect']))
{
    echo Shop::ShowMainCategories('Обычные вещи', 'простые доспехи и оружие', '', '/complect/1/', ['type' => 'level', 'value' => 0]);
}
elseif (is_numeric($_GET['complect']))
{
    $Complects = Shop::GetComplectsByQuality($_GET['complect']);

    echo '<div class="bdr bg_main mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">';

    if($Complects !== false)
    {
        for($i = 0; $i < count($Complects); $i++)
        {
            echo $Complects[$i];
        }
    }
    else
    {
        echo 'no data';
    }

    echo '</div></div></div></div></div></div></div></div></div>';
}

require_once './footer.php';

/*
 *
 * Type of clothes
 *
 * Helmet - 0
 * Shoulders - 1
 * Armor - 2
 * Gloves - 3
 * Pants - 4
 * Boots - 5
 * Right hand - 6
 * Left hand - 7
 */