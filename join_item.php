<?php

require_once './app/configs/systemSettigns.php';

use app\classes\Inventory;
use app\classes\Items;
use app\classes\User;
use app\classes\GET;
use app\classes\ActiveRecords;

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
$InventoryItems = Inventory::GetItems(null, 0);

if(GET::check_isset('join_all') === true) {

    Inventory::join_items($InventoryItems->data);

    header('Location: /chest');
    exit;
}

$data = GET::get('id');

if(GET::check_isset('id') === false || ! is_numeric($data->data)) {
    header('Location: /');
}

$ActiveRecordsInventory = new ActiveRecords('inventory');
$ActiveRecordsItems     = new ActiveRecords('items');
$item_data       = '';
$equip_item_data = '';

$item = $ActiveRecordsInventory->select()->where(['id' => $data->data])->execute();

if($item->count_rows === 1) {

    $equip           = Inventory::search_similar_equip_item($item->data[0]->type);
    $item_data       = Items::get_item_data($item->data[0]->id, true);
    $equip_item_data = Items::get_item_data($equip->id_inv, true);

    if($equip->result === true && $equip_item_data->current_level < $equip_item_data->max_level) {

        if(GET::check_isset('disassemble')) {

            Inventory::join_items([$data->data]);

            header('Location: /chest');

        }

        $no_item = false;

    } else
        $no_item = true;


$title = 'Разобрать вещь';

require_once './header.php';
?>

<div class="hr_g mb2"><div><div></div></div></div>

<div class="bntf">
    <div class="small">
        <div class="nl">
            <div class="nr cntr lyell small lh1 plr15 pt5 pb5 sh">
                Потратив <a href="/view_player_item?player_id=10528964&id=<?=$item->data[0]->id?>" class="lyell"><?=$item_data->name?></a> [<?=$item_data->current_level?>/<?=$item_data->max_level?>], вы улучшите одетую на вас вещь
            </div>
        </div>
    </div>
</div>

<div class="hr_g mb2"><div><div></div></div></div>

<?php
    if($no_item === false) {
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

                                    <div class="fl ml5 mt5 mr5">
                                        <a href="/view_player_item?player_id=10528964&amp;id=1271006781&amp;type=cloth&amp;PHPSESSID=beeaad0d47a3ddfc98c10a3ed21cd1c4.1605716591.5686237"><?=$equip_item_data->img?></a>
                                    </div>
                                    <div class="ml58 mt5 mb5 sh small">
                                        <a href="/view_player_item?player_id=10528964&amp;id=1271006781&amp;type=cloth&amp;PHPSESSID=beeaad0d47a3ddfc98c10a3ed21cd1c4.1605716591.5686237"><?=$equip_item_data->name?></a></div>
                                    <div class="ml58 mt5 mb5 sh small">
                                        <span class="lorange small">[На мне сейчас]</span>					</div>
                                    <div class="ml58 mt5 mb5 sh small">
                                        <img class="icon" src="/view/image/quality_cloth/<?=$equip_item_data->quality_number?>.png"> <span class="q<?=$equip_item_data->quality_number?>"><?=$equip_item_data->quality_name?> [<?=$equip_item_data->current_level?>/<?=$equip_item_data->max_level?>]</span>
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
<br>
<div class="cntr mb5">
    <a href="/join_item?id=<?=$data->data?>&disassemble" class="ubtn inbl mt-15 green mb5"><span class="ul"><span class="ur">Улучшить</span></span></a>
<?php
    } else {
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
            <div class="nr cntr lyell small lh1 plr15 pt5 pb5 sh">
                Нету подходящей вещи для разбора
            </div>
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
?>
    <div class="hr_g mb2"><div><div></div></div></div>
    <div class="bntf"><div class="small"><div class="nl"><div class="nr cntr lyell small lh1 plr15 pt5 pb5 sh">
                    <ul class="mt5 mb5"><li class="lft mb2">Руны и заточки при разборке переносятся</li>
                        <li class="lft">Улучшать можно только самую лучшую вещь из имеющихся</li></ul></div>
            </div></div></div></div>
<div class="hr_g mb2"><div><div></div></div></div>
<?php
} else {
    exit('error');
}

require_once './footer.php';
