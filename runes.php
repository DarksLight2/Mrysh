<?php

use app\classes\Inventory;
use app\classes\Items;
use app\classes\Runes;
use app\classes\User;

require_once './app/configs/systemSettigns.php';

$title = 'Торговец рунами';

if(isset($_GET['item_id']) && is_numeric($_GET['item_id'])) {

    $item_data = Items::get_item_data($_GET['item_id'], true);
    $runes     = Runes::get();

    $available_runes = 0;

    if(isset($_GET['rune']) && is_numeric($_GET['rune'])) {
        if(Runes::update($_GET['item_id'], $_GET['rune'], null, true) === true) {
            header('Location: /view_player_item?player_id='.User::userData()['id'].'&id='.$item_data->inv_id);
        } else {
            echo 'Неудалось!';
        }
    }

    require_once './header.php';
?>
    <div class="cntr lorange small mt-5">
	<span class="nowrap">
		Ваши ресурсы:  <img class="icon" src="http://144.76.127.94/view/image/icons/gold.png">49, <img class="icon" src="http://144.76.127.94/view/image/icons/silver.png">109.95k	</span>
    </div>

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

                                            <a href="/view_player_item?player_id=<?=User::userData()['id']?>&id=<?=$item_data->inv_id?>">
                                                <?=$item_data->img?>
                                            </a>

                                        </div>

                                        <div class="mt5 mb5 sh small">
                                            <a href="/view_player_item?player_id=<?=User::userData()['id']?>&id=<?=$item_data->inv_id?>"><?=$item_data->name?></a>
                                            <?=($item_data->sharpening > 0 ? '+'.$item_data->sharpening : '')?>
                                        </div>

                                        <div class="mt5 mb5 sh small">
                                            <img class="icon" src="http://144.76.127.94/view/image/quality_cloth/<?=$item_data->quality_number?>.png"> <span class="q<?=$item_data->quality_number?>"><?=$item_data->quality_name?> [<?=$item_data->current_level?>/<?=$item_data->max_level?>]</span>
                                            <br>
                                        </div>

                                        <?php
                                        if($item_data->rune > 0) {
                                            $rune_data = Runes::get($item_data->rune)->data[0];

                                            ?>
                                            <div class="mt5 mb5 sh small">
                                                <img src="http://144.76.127.94/view/image/quality/<?=$item_data->rune?>.png" alt="">
                                                <span class="q_rune<?=$item_data->rune?>">+<?=$rune_data->params?></span>
                                            </div>
                                            <?php
                                        }
                                        ?>

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
        foreach ($runes->data as $rune) {
            if($rune->id > $item_data->rune) {
?>
            <div class="bdr bg_blue"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                                <div class="fl ml5 mt5 mr5">
                                                    <img class="item_icon" src="http://144.76.127.94/view/image/rune/<?=$rune->id?>.png">
                                                </div>
                                                <div class="mt5 mb2">
                                                    <span class="medium lwhite tdn"><?=$rune->name?></span>
                                                </div>
                                                <div class="ml5 mb5 sh small lorange">
                                                    Бонус:  <span class="win">+<?=$rune->params?></span> к параметрам			</div>
                                                <div class="clb"></div>
                                            </div></div></div></div></div></div></div></div></div>
            <br>
            <div class="cntr">
                <a href="?item_id=<?=$_GET['item_id']?>&rune=<?=$rune->id?>" class="ubtn inbl mt-15 green mb5">
                    <span class="ul"><span class="ur">Купить за <img src="http://144.76.127.94/view/image/icons/gold.png" class="icon"><?=$rune->price?></span></span>
                </a>
            </div>
<?php
                $available_runes++;
            }
        }

        if($available_runes === 0){
            echo 'Нету подходящей руны для вещи';
        }
?>
    <div class="hr_g mb2"><div><div></div></div></div>
    <div class="bntf"><div class="small"><div class="nl"><div class="nr cntr lyell small lh1 plr15 pt5 pb5 sh">
                    <ul class="mt5 mb5">
                        <li class="lft mb2">Руну всегда можно перенести на более лучшую вещь</li>
                        <li class="lft">При покупке руны есть 10% шанс получить более качественную руну за те же деньги</li>
                    </ul>
                </div></div></div></div>
    <div class="hr_g mb2"><div><div></div></div></div>
    <a class="mbtn mb2" href="/runes/"><img src="http://144.76.127.94/view/image/icons/back.png" class="icon"> Назад к выбору вещей</a>
    <div class="hr_g mb2"><div><div></div></div></div>
<?php
} else {
    $items = Inventory::GetItems(null, 1);
    require_once './header.php';
?>
<div class="hr_g mb2"><div><div></div></div></div>
<div class="bntf"><div class="nl"><div class="nr cntr lyell small lh1 plr15 pt5 pb5 sh">
            Выберите вещь</div></div></div>
<div class="hr_g mb2"><div><div></div></div></div>

<?php
    if($items->count_rows > 0) {
        foreach ($items->data as $item) {
            $item_data = Items::get_item_data($item->id, true);
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

                                        <a href="/view_player_item?player_id=<?=User::userData()['id']?>&amp;id=<?=$item_data->inv_id?>">
                                            <?=$item_data->img?>
                                        </a>

                                    </div>

                                    <div class="mt5 mb5 sh small">
                                        <a href="/view_player_item?player_id=<?=User::userData()['id']?>&amp;id=<?=$item_data->inv_id?>"><?=$item_data->name?></a>
                                        <?=($item_data->sharpening > 0 ? '+'.$item_data->sharpening : '')?>
                                    </div>

                                    <div class="mt5 mb5 sh small">
                                        <img class="icon" src="http://144.76.127.94/view/image/quality_cloth/<?=$item_data->quality_number?>.png"> <span class="q<?=$item_data->quality_number?>"><?=$item_data->quality_name?> [<?=$item_data->current_level?>/<?=$item_data->max_level?>]</span>
                                        <br>
                                    </div>

                                    <?php
                                    if($item_data->rune > 0) {
                                    ?>
                                    <div class="mt5 mb5 sh small">
                                        <img src="http://144.76.127.94/view/image/quality/<?=$item_data->rune?>.png" alt=""> <span class="q_rune<?=$item_data->rune?>">+<?=Runes::get($item_data->rune)->data[0]->params?></span>
                                    </div>
                                    <?php
                                    }
                                    ?>

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
<div class="cntr"><a href="?item_id=<?=$item_data->inv_id?>" class="ubtn inbl mt-15 green mb5"><span class="ul"><span class="ur">Выбрать</span></span></a></div>

<?php
        }
    } else {
        echo 'Нету подходящей вещи для установки рун.';
    }
?>

<div class="hr_g mb2"><div><div></div></div></div>

<div class="bntf small">
    <div class="nl">
        <div class="nr cntr lyell lh1 p5 sh">
            Магические свойства рун улучшат ваши вещи!
        </div>
    </div>
</div>

<div class="hr_g mb2"><div><div></div></div></div>
<a class="mbtn mb2" href="/shop">
    <img src="http://144.76.127.94/view/image/icons/back.png" class="icon"> Назад к категориям
</a>
<div class="hr_g mb2"><div><div></div></div></div>
<?php
}
require_once './footer.php';