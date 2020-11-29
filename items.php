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

if(isset($_GET['buy']) && is_numeric($_GET['buy']))
{
    if(Shop::BuyItem($_GET['buy']) === true)
    {
        header('Location: /chest');
        exit;
    }
} elseif(GET::check_isset('buy_set') && GET::check_isset('set_id') && (Inventory::GetAmountItems(null, 0) + 8) <= 20) {

    $complect_id   = GET::get('set_id')->data;
    $complect_data = Complects::get_data($complect_id);

    if(User::userData()['gold'] >= $complect_data->price) {

        foreach ($complect_data->arr_items as $item_id) {
            Shop::BuyItem($item_id);
        }
    }

    header('Location: /hero');
}
elseif(isset($_GET['set_id']) && is_numeric($_GET['set_id']))
{
    require_once './header.php';

    $complect_data = Complects::get_data($_GET['set_id']);

    $price_of_all = Shop::get_data($complect_data->item_helmet)->price +
                    Shop::get_data($complect_data->item_shoulders)->price +
                    Shop::get_data($complect_data->item_armor)->price +
                    Shop::get_data($complect_data->item_gloves)->price +
                    Shop::get_data($complect_data->item_mainhand)->price +
                    Shop::get_data($complect_data->item_offhand)->price +
                    Shop::get_data($complect_data->item_pants)->price +
                    Shop::get_data($complect_data->item_boots)->price;
?>
    <div class="bdr bg_main mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                        <div class="mt8 ml10 shop_lgt">
                                            <div class="fl ml5 mr10 sz0">
                                                <a href="/shop_avatar?set_id=1">
                                                    <?=Shop::maneken([
                                                        $complect_data->item_helmet,
                                                        $complect_data->item_shoulders,
                                                        $complect_data->item_armor,
                                                        $complect_data->item_gloves,
                                                        $complect_data->item_mainhand,
                                                        $complect_data->item_offhand,
                                                        $complect_data->item_pants,
                                                        $complect_data->item_boots
                                                    ])?>
                                                </a>
                                            </div>
                                            <div class="mt5">
                                                <span class="bold lblue tdn"><?=$complect_data->name?></span>
                                            </div>
                                            <div class="clb"></div>
                                        </div>
                                    </div></div></div></div></div></div></div></div></div>
<br>
    <div class="cntr"><a class="ubtn inbl mt-15 green" href="?buy_set&set_id=<?=$complect_data->id?>"><span class="ul"><span class="ur">Купить все за <img src="http://144.76.127.94/view/image/icons/gold.png" class="icon" alt=""><?=$price_of_all?></span></span></a></div>

    <div class="bdr bg_main mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
<?php

    $number_item = 1;

    foreach ($complect_data->arr_items as $item_id) {
        $item_data = Items::get_item_data($item_id);

        if($number_item !== 1) {
            echo '<div class="hr_arr mt-5 mlr10"><div class="alf"><div class="art"><div class="acn"></div></div></div></div>';
        }
?>
        <div class="mt8 ml10 shop_lgt">
            <div class="fl ml5 mr10 sz0">
                <a href="/viewItem/<?=$item_data->item_id?>/">
                    <?=$item_data->img?>
                </a>
            </div>

            <div class="ml58 mt5 mb5 sh small">
                <a href="/viewItem/<?=$item_data->item_id?>/"><?=$item_data->name?></a>
            </div>
            <div class="ml58 mt5 mb5 sh small">
                <img class="icon" src="http://144.76.127.94/view/image/quality_cloth/<?=$item_data->quality_number?>.png"> <span class="q<?=$item_data->quality_number?>"><?=$item_data->quality_name?> [<?=$item_data->current_level?>/<?=$item_data->max_level?>]</span>
            </div>
            <div class="ml58 mt5 sh small">
                <a class="buy_link" href="?buy=<?=$item_data->item_id?>">Купить</a><span class="buy_link"> за <img src="http://144.76.127.94/view/image/icons/gold.png" alt="" class="icon"><?=Shop::get_data($item_data->item_id)->price?></span>
            </div>
            <div class="clb"></div>
        </div>
<?php
        $number_item++;
    }
?>
    </div></div></div></div></div></div></div></div></div>

    <div class="hr_g mb2"><div><div></div></div></div>

    <a class="mbtn mb2" href="/item/sets?type_id=1&amp;PHPSESSID=d8b9e5a68aac87d00cabc853b09f085e.1599682227.36372790"><img src="http://144.76.127.94/view/image/icons/back.png" class="icon"> Назад к комплектам</a>

    <div class="hr_g mb2"><div><div></div></div></div>
<?php
}
elseif(GET::check_isset('viewItem'))
{
    $title = 'Детальное описание';

    $item_id = GET::get('viewItem')->data;

    require_once './header.php';

    $item_data = Items::get_item_data($item_id);

?>
    <div class="cntr lorange small mt-5">
	<span class="nowrap">
		Ваши ресурсы:  <img class="icon" src="http://144.76.127.94/view/image/icons/gold.png"><?=User::userData()['gold']?>, <img class="icon" src="http://144.76.127.94/view/image/icons/silver.png"><?=User::userData()['silver']?>	</span>
    </div>

    <div class="bdr cnr bg_blue mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                        <div class="fl ml5 mt5 mr5">
                                            <?=$item_data->img?>
                                        </div>
                                        <div class="mt5 mb5 sh small">
                                            <?=$item_data->name?>
                                        </div>
                                        <div class="mt5 mb5 sh small">
                                            <img class="icon" src="http://144.76.127.94/view/image/quality_cloth/<?=$item_data->quality_number?>.png"> <span class="q<?=$item_data->quality_number?>"><?=$item_data->quality_name?> [<?=$item_data->current_level?>/<?=$item_data->max_level?>]</span>, <?=explode(' ', $item_data->name)[0]?><br>		</div>
                                        <div class="clb"></div>
                                    </div></div></div></div></div></div></div></div></div>

    <div class="bdr cnr mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                        <div class="ml5 mt5 mb5 sh small">
                                            <img class="icon" src="http://144.76.127.94/view/image/icons/strength.png"> Сила:   <?=$item_data->item_strength?><br>
                                            <img class="icon" src="http://144.76.127.94/view/image/icons/health.png"> Здоровье: <?=$item_data->item_health?><br>
                                            <img class="icon" src="http://144.76.127.94/view/image/icons/defense.png"> Броня:   <?=$item_data->item_defence?>
                                        </div>
                                        <div class="clb"></div>
                                    </div></div></div></div></div></div></div></div></div>

    <div class="ml5 mt-5 mb5 sh small cntr">
        <a class="ubtn inbl green" href="?buy=<?=$item_data->item_id?>"><span class="ul"><span class="ur">Купить за <img src="http://144.76.127.94/view/image/icons/gold.png" alt="" class="icon"><?=$item_data->price?></span></span></a>	</div>

    <?php

    $equip_item_id = Inventory::search_similar_equip_item($item_data->type_number);

    if($equip_item_id->result === true) {

        $equip_item_data = Items::get_item_data($equip_item_id->id_inv, true);
?>

    <div class="hr_g mb2"><div><div></div></div></div>
    <div class="mt5 mb5 large cntr">На вас надето</div>
    <div class="hr_g mb2"><div><div></div></div></div>

           <div class="bdr cnr bg_blue mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                               <div class="fl ml5 mt5 mr5"><?=$equip_item_data->img?></div>
                                               <div class="mt5 mb5 sh small">
                                                   <a href="/view_player_item?player_id=<?=User::userData()['id']?>&id=<?=$equip_item_data->inv_id?>">
                                                       <?=$equip_item_data->name?>
                                                   </a>
                                               </div>
                                               <div class="mt5 mb5 sh small">
                                                   <img class="icon" src="http://144.76.127.94/view/image/quality_cloth/<?=$equip_item_data->quality_number?>.png"> <span class="q<?=$equip_item_data->quality_number?>"><?=$equip_item_data->quality_name?> [<?=$equip_item_data->current_level?>/<?=$equip_item_data->max_level?>]</span>, <?=explode(' ', $equip_item_data->name)[0]?><br>
                                               </div>
                                               <div class="clb"></div>
                                           </div></div></div></div></div></div></div></div></div>

           <div class="bdr cnr mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                               <div class="ml5 mt5 mb5 sh small">
                                                   <img class="icon" src="http://144.76.127.94/view/image/icons/strength.png"> Сила:   <?=$equip_item_data->inv_strength?><br>
                                                   <img class="icon" src="http://144.76.127.94/view/image/icons/health.png"> Здоровье: <?=$equip_item_data->inv_health?><br>
                                                   <img class="icon" src="http://144.76.127.94/view/image/icons/defense.png"> Броня:   <?=$equip_item_data->inv_defence?>
                                               </div>
                                               <div class="clb"></div>
                                           </div></div></div></div></div></div></div></div></div>

<?php
    }
?>
    <div class="hr_g mb2"><div><div></div></div></div>
    <a class="mbtn mb2" href="/items?set_id=<?=$item_data->complect_number?>"><img src="http://144.76.127.94/view/image/icons/back.png" class="icon"> Назад к комплекту</a>
    <div class="hr_g mb2"><div><div></div></div></div>
<?php
}
else
{
    header('Location: /shop/');
}

require_once './footer.php';
