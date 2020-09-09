<?php

require_once './app/configs/systemSettigns.php';

use app\classes\User;
use app\classes\Inventory;
use app\classes\Items;

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

$title = 'Сумка';

require_once './header.php';

if(Inventory::GetAmountItems() === 0)
{
?>
    <div class="bdr cnr bg_blue mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                        <div class="ml5 mt5 mb5 sh small">
                                            Сумка пуста			</div>
    </div></div></div></div></div></div></div></div></div>
<?php
}
else
{
    $InventoryItems = Inventory::GetItems();
    $ItemsData = Items::GetItemsData($InventoryItems);

    var_dump($ItemsData);

    foreach ($ItemsData as $Key => $Item)
    {


?>
    <div class="bdr cnr bg_blue mb2">
        <div class="wr1">
            <div class="wr2">
                <div class="wr3">
                    <div class="wr4">
                        <div class="wr5">
                            <div class="wr6">
                                <div class="wr7">
                                    <div class="wr8">

                                        <div class="fl ml5 mt5 mr5 w48px">

                                            <div class="fl h48">
                                                <a href="/view_player_item?id=<?=$Item['id']?>"><img class="item_icon" width="48" height="48" src="http://144.76.127.94/view/image/item/<?=$Item['id']?>_head.png"></a>
                                            </div>

                                            <div class="clb"></div>

                                        </div>

                                        <div class="ml58 mt5 mb5 sh small">
                                            <a href="/view_player_item?id=<?=$Item['id']?>"><?=$Item['name']?></a>
                                        </div>

                                        <div class="ml58 mt5 mb5 sh small">
                                            <img class="icon" src="http://144.76.127.94/view/image/quality_cloth/<?=$Item['quality']?>.png"> <span class="q1">Обычный [1/5]</span>
                                        </div>

                                        <div class="ml58 mt5 mb5 sh small">

                                            <a class="sell_link" href="/join_item?id=<?=$Item['id']?>">Разобрать</a>

                                        </div>

                                        <div class="clb"></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php

    }
}
?>
<div class="hr_g mb2"><div><div></div></div></div>

<div class="nr cntr lyell small lh1 plr15 pt5 pb5 sh">
    Можно улучшать снаряжение разбирая ненужные вещи</div>

<div class="hr_g mb2"><div><div></div></div></div>

<a href="/gear" class="mbtn mb2"><img src="http://144.76.127.94/view/image/icons/slots.png" class="icon"> Снаряжение </a>

<?php

require_once './footer.php';
