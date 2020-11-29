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

        //$_SESSION['item_to_destroy'] = $item_data;
        //$_SESSION['equip_item']      = $equip_item_data;

        if(GET::check_isset('disassemble')) {

            Inventory::join_items([$data->data]);

//            if($_SESSION['equip_item']->max_level <= $_SESSION['equip_item']->current_level)
//                header('Location: /');
//
//            if(isset($_SESSION['item_to_destroy']) && isset($_SESSION['equip_item'])) {
//
//                if($_SESSION['item_to_destroy']->rune > $_SESSION['equip_item']->rune) {
//                    $rune = $_SESSION['item_to_destroy']->rune;
//                } else {
//                    $rune = $_SESSION['equip_item']->rune;
//                }
//
//                if($_SESSION['item_to_destroy']->sharpening > $_SESSION['equip_item']->sharpening) {
//                    $sharpening = $_SESSION['item_to_destroy']->sharpening;
//                } else {
//                    $sharpening = $_SESSION['equip_item']->sharpening;
//                }
//
//                $exp = $_SESSION['equip_item']->exp + Items::get_exp_to_disassemble($_SESSION['item_to_destroy']->current_level) + $_SESSION['item_to_destroy']->exp;
//
//                Items::get_exp_to_disassemble($_SESSION['item_to_destroy']->current_level);
//
//                $level_item = $_SESSION['equip_item']->current_level;
//
//                if($exp >= Items::get_exp_to_disassemble($_SESSION['equip_item']->current_level + 1)) {
//                    $level_item = $level_item + 1;
//                    $exp = $exp - Items::get_exp_to_disassemble($_SESSION['equip_item']->current_level + 1);
//                    Inventory::change_data($equip->data[0]->id, [
//                            'strength' => $_SESSION['equip_item']->inv_strength + 2,
//                            'health'   => $_SESSION['equip_item']->inv_health   + 2,
//                            'defence'  => $_SESSION['equip_item']->inv_defence  + 2
//                    ]);
//                    User::change_data([
//                            'strength' => User::userData()['strength'] + 2,
//                            'health'   => User::userData()['health']   + 2,
//                            'defence'  => User::userData()['defence']  + 2]);
//                }
//
//                Inventory::change_data($equip->data[0]->id, [
//                        'rune'       => $rune,
//                        'sharpening' => $sharpening,
//                        'exp'        => $exp,
//                        'level_items'=> $level_item.'|'.$_SESSION['equip_item']->max_level
//
//                ]);
//
//                Inventory::DestroyItem($data->data);
//
//                $_SESSION['success_disassemble']['id']          = $equip->data[0]->id;
//                $_SESSION['success_disassemble']['name']        = $_SESSION['equip_item']->name;
//                $_SESSION['success_disassemble']['img']         = $_SESSION['equip_item']->img;
//                $_SESSION['success_disassemble']['quality']     = '<img class="icon" src="/view/image/quality_cloth/'.$_SESSION['equip_item']->quality_number.'.png"> <span class="q'.$_SESSION['equip_item']->quality_number.'">'.$_SESSION['equip_item']->quality_name.' ['.$_SESSION['equip_item']->current_level.'/'.$_SESSION['equip_item']->max_level.']</span>';
//                $_SESSION['success_disassemble']['level']       = $_SESSION['equip_item']->current_level;
//                $_SESSION['success_disassemble']['exp']         = Items::get_exp_to_disassemble($_SESSION['item_to_destroy']->current_level);
//                $_SESSION['success_disassemble']['current_exp'] = $exp;
//            }
//
//            unset($_SESSION['item_to_destroy']);
//            unset($_SESSION['equip_item']);
//
//            header('Location: /chest');

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
