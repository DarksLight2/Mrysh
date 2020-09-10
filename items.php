<?php

require_once './app/configs/systemSettigns.php';

use app\classes\Shop;
use app\classes\User;
use app\classes\DataBase;

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
    $ComplectData = DataBase::query('SELECT * FROM `complects` WHERE `id` = ?', [$_GET['set_id']])->fetch_assoc();
?>
    <div class="bdr bg_main mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                        <div class="mt8 ml10 shop_lgt">
                                            <div class="fl ml5 mr10 sz0">
                                                <a href="/shop_avatar?set_id=1">
                                                    <?=Shop::Maneken($ComplectItems)?>
                                                </a>
                                            </div>
                                            <div class="mt5">
                                                <span class="bold lblue tdn"><?=$ComplectData['name']?></span>
                                            </div>
                                            <div class="clb"></div>
                                        </div>
                                    </div></div></div></div></div></div></div></div></div>
<br>
    <div class="cntr"><a class="ubtn inbl mt-15 green" href="?buy_set&set_id=1"><span class="ul"><span class="ur">Купить все за <img src="http://144.76.127.94/view/image/icons/gold.png" class="icon" alt=""><?=Shop::CostAllInComplect($_GET['set_id'])?></span></span></a></div>

    <div class="bdr bg_main mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
<?php
    for($i = 0; $i < 8; $i++)
    {

        echo Shop::ShowItemData($ComplectItems[$i]);

        if($i != 7)
        {
            echo '<div class="hr_arr mt-5 mlr10"><div class="alf"><div class="art"><div class="acn"></div></div></div></div>';
        }

    }
?>
    </div></div></div></div></div></div></div></div></div>

    <div class="hr_g mb2"><div><div></div></div></div>

    <a class="mbtn mb2" href="/item/sets?type_id=1&amp;PHPSESSID=d8b9e5a68aac87d00cabc853b09f085e.1599682227.36372790"><img src="http://144.76.127.94/view/image/icons/back.png" class="icon"> Назад к комплектам</a>

    <div class="hr_g mb2"><div><div></div></div></div>
<?php
}
else
{
    header('Location: /shop/');
}

require_once './footer.php';
