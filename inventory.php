<?php

require_once './app/configs/systemSettigns.php';

use app\classes\GET;
use app\classes\Shop;
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

if(Inventory::GetAmountItems() > 0 && isset($_GET['wear_item']) && is_numeric($_GET['id']))
{

    Inventory::Equip($_GET['id']);

    header('Location: /chest');
}

$InventoryItems = Inventory::GetItems(null, 0);

require_once './header.php';

if(isset($_SESSION['disassemble_items_data']) && ! empty($_SESSION['disassemble_items_data'])) {

    $item_data = $_SESSION['disassemble_items_data'];

    if( ! isset($item_data['data'])) {
        $progress = ($item_data['exp'] / Items::get_exp_to_disassemble($item_data['level'] + 1)) * 100;
?>
        <div class="hr_g mb2"><div><div></div></div></div>
    <div class="bntf"><div class="nl"><div class="nr cntr lyell lh1 p5 sh">
                <span class="win">Прогресс улучшения +<?=$item_data['exp']?></span>			<div></div>
                <div class="inbl lft mt5 small w200px">
                    <div class="fl ml5 mt5 mr5">
                        <?=$item_data['img']?>
                    </div>
                    <div class="mt5 mb5 sh small lorange">
                        <a href="/view_player_item?player_id=<?=User::userData()['id']?>&amp;id=<?=$item_data['id']?>"><?=$item_data['name']?></a>
                    </div>
                    <div class="mt5 mb5 sh small lorange">
                        <?=$item_data['quality']?>
                    </div>
                    <div class="ml58 mt5 mb5 mr10 sh small lorange">
                        <div class="mb2">Прогресс:
                            <span class="win"><?=$item_data['current_exp']?></span> из <?=Items::get_exp_to_disassemble($item_data['level'] + 1)?>
                        </div>
                        <div class="prg-bar fght mb2 w150px">
                            <div class="prg-blue fl" style="width:<?=$progress?>%;"></div>
                        </div>
                        <div class="clb"></div>
                    </div>
                    <div class="clb"></div>
                </div>
            </div></div></div>
<?php
    } else {
        foreach ($item_data as $item_id => $data) {
?>
            <div class="bntf"><div class="nl">
                    <div class="nr cntr lyell lh1 p5 sh">
                        <div class="inbl small lft">
                            <?php
                                if(isset($data['new_level']) && ! empty($data['new_level'])) {
                            ?>
                            <div class="win">Получили новый уровень</div>
                            <div class="clb"></div>
                            <div class="nwr">
                                <ul class="mt5 mb5 plr15">
                                        <?php
                                                foreach ($data['new_level'] as $item) {
                                                    echo '<li class="mb2"><a href="/" class="lyell">'.$item['name'].'</a>, <span class="win">'.$item['level'].' уровень</span></li>';
                                                }
                                        ?>
                                </ul>
                            </div>
                            <?php
                                }

                                echo '<div class="clb"></div>';

                                if(isset($data['upgrade']) && ! empty($data['upgrade'])) {
                            ?>
                            <div class="mt10"></div>
                            <div class="win">Улучшенные вещи</div>
                            <div class="clb"></div>
                            <div class="nwr">
                                <ul class="mt5 mb5 plr15">
                                    <?php
                                    foreach ($data['upgrade'] as $item) {
                                        echo '<li class="mb2"><a href="/" class="lyell">'.$item['name'].'</a> [<span class="win">'.$item['level'].'</span>/'.$item['max_level'].']</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
<?php
        }
    }

    unset($_SESSION['disassemble_items_data']);
}

if(Inventory::GetAmountItems(null, 0) === 0)
{
?>
    <div class="bdr cnr bg_blue mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                        <div class="ml5 mt5 mb5 sh small">
                                            Сумка пуста
                                        </div>
    </div></div></div></div></div></div></div></div></div>
<?php
}
else
{

    $ItemNumber = 0;

    foreach ($InventoryItems->data as $item_id) {

        $item       = Items::get_item_data($item_id->id, true);
        $comparison = Inventory::Comparison($item->inv_id);

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
                                                <a href="/view_player_item?id=<?=$item->inv_id?>"><?=$item->img?></a>
                                            </div>

                                            <div class="clb"></div>

                                        </div>

                                        <div class="ml58 mt5 mb5 sh small">
                                            <a href="/view_player_item?id=<?=$item->inv_id?>"><?=$item->name?></a>
                                        </div>

                                        <div class="ml58 mt5 mb5 sh small">
                                            <img class="icon" src="http://144.76.127.94/view/image/quality_cloth/<?=$item->quality_number?>.png"> <span class="q<?=$item->quality_number?>"><?=$item->quality_name?> [<?=$item->current_level?>/<?=$item->max_level?>]</span> <?=$comparison->text?>
                                        </div>

                                        <div class="ml58 mt5 mb5 sh small">

    <?php
        if($comparison->result === 'no_best') {
            echo '<a class="sell_link" href="/join_item?id='.$item->inv_id.'">Разобрать</a>';
        } else {
            echo '<a class="sell_link" href="?wear_item&id='.$item->inv_id.'">Надеть</a>';
        }
    ?>


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
        $ItemNumber++;
    }
}
?>
<div class="hr_g mb2"><div><div></div></div></div>

<div class="nr cntr lyell small lh1 plr15 pt5 pb5 sh">
    Можно улучшать снаряжение разбирая ненужные вещи</div>

<div class="hr_g mb2"><div><div></div></div></div>
    <a href="/join_item.php?join_all" class="mbtn mb2"><img src="http://144.76.127.94/view/image/icons/slots.png" class="icon"> Разобрать все ненужное </a>
<a href="/gear" class="mbtn mb2"><img src="http://144.76.127.94/view/image/icons/slots.png" class="icon"> Снаряжение </a>

<?php

require_once './footer.php';
