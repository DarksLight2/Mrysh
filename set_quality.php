<?php

require_once './app/configs/systemSettigns.php';

use app\classes\Complects;
use app\classes\Shop;
use app\classes\User;
use app\classes\GET;

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

if(GET::check_isset('complect') === false)
{
    echo Shop::ShowMainCategories('Редкие вещи', 'снаряжение для закаленных ветеранов', 'quality_big/3', '/complect/3/', ['type' => 'level', 'value' => 0]);
    echo Shop::ShowMainCategories('Небычные вещи', 'прочно, практично, недорого', 'quality_big/2', '/complect/2/', ['type' => 'level', 'value' => 3]);
    echo Shop::ShowMainCategories('Обычные вещи', 'простые доспехи и оружие', 'quality_big/1', '/complect/1/', ['type' => 'level', 'value' => 0]);
}
elseif (GET::check_isset('complect') === true)
{

    $complects = Complects::get_complects_by_quality($_GET['complect']);

    echo '<div class="bdr bg_main mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">';

    $number_complect = 1;

    foreach ($complects as $complect) {

        if($number_complect !== 1){
            echo '<div class="hr_arr mt-5 mlr10"><div class="alf"><div class="art"><div class="acn"></div></div></div></div>';
        }
?>

        <div class="mt8 ml10 shop_lgt">
            <div class="fl ml5 mr10 sz0">
                <a class="nd" href="/items?set_id=<?=$complect->id?>">
                    <?=Shop::maneken([
                        $complect->item_helmet,
                        $complect->item_shoulders,
                        $complect->item_armor,
                        $complect->item_gloves,
                        $complect->item_mainhand,
                        $complect->item_offhand,
                        $complect->item_pants,
                        $complect->item_boots
                    ])?>
                </a>
            </div>
            <div>
                <a class="bold tdn" href="/items?set_id=<?=$complect->id?>">
                    <?=$complect->name?>
                </a>
            </div>
            <div class="clb"></div>
        </div>
<?php
        $number_complect++;
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
 * Pants - 6
 * Boots - 7
 * Right hand - 4
 * Left hand - 5
 */