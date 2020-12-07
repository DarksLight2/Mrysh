<?php

use app\classes\Inventory;
use app\classes\Items;
use app\classes\Runes;
use app\classes\User;

require_once './app/configs/systemSettigns.php';

$title = 'Повышение заточки';

$sharpening_table = include_once './app/configs/sharpening_table.php';

if(isset($_GET['grind']) && is_numeric($_GET['grind'])) {

    $item_data = Items::get_item_data($_GET['grind'], true);

    if($item_data->sharpening === 100) {
        echo 'Достигнут максимальный уровень заточки.';
    } else {
        $sharpening_level = $item_data->sharpening + 1;
        $sharpening_data = $sharpening_table[$sharpening_level];

        if (User::userData()[$sharpening_data['type']] >= $sharpening_data['price']) {

            User::change_data([
                $sharpening_data['type'] => User::userData()[$sharpening_data['type']] - $sharpening_data['price'],
                'strength' => User::userData()['strength'] + 2,
                'health' => User::userData()['health'] + 2,
                'defence' => User::userData()['defence'] + 2
            ]);

            Inventory::change_data($item_data->inv_id, [
                'strength' => $item_data->inv_strength + 2,
                'health' => $item_data->inv_health + 2,
                'defence' => $item_data->inv_defence + 2,
                'sharpening' => $sharpening_level
            ]);

            header('Location: /sharpening');
        } else {
            echo 'Задонать ублюдок!!! Денег нету!';
        }
    }
} else {
    $items = Inventory::GetItems(null, 1);

    require_once './header.php';
?>
    <div class="cntr lorange small mt-5">
	<span class="nowrap">
		Ваши ресурсы:  <img class="icon" src="http://144.76.127.94/view/image/icons/gold.png">49, <img class="icon" src="http://144.76.127.94/view/image/icons/silver.png">109.95k	</span>
    </div>
<?php
    if($items->count_rows > 0 ) {

        foreach ($items->data as $item) {
            $item_data = Items::get_item_data($item->id, true);
            $sharpening_level = $item_data->sharpening + 1;
            $sharpening_data  = $sharpening_table[$sharpening_level];


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
                                                    <a href="/view_player_item?player_id=<?=User::userData()['id']?>&id=<?=$item_data->inv_id?>">
                                                        <?=$item_data->img?>
                                                    </a>
                                                </div>

                                                <div class="mt5 mb5 sh small">
                                                    <a href="/view_player_item?player_id=<?=User::userData()['id']?>&id=<?=$item_data->inv_id?>">
                                                        <?=$item_data->name?>
                                                    </a>
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
                                                        <img src="http://144.76.127.94/view/image/quality/<?=$item_data->rune?>.png" alt=""> <span class="q_rune<?=$item_data->rune?>">+<?=$rune_data->params?></span>
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
            <br>
            <div class="cntr"><a href="?grind=<?=$item_data->inv_id?>" class="ubtn inbl mt-15 green mb5">
					<span class="ul">
						<span class="ur">Заточить за <img src="http://144.76.127.94/view/image/icons/<?=$sharpening_data['type']?>.png" class="icon"><?=$sharpening_data['price']?></span>
					</span>
                </a></div>

            <?php
        }
    } else {
        echo 'Нету подходящей вещи для установки рун.';
    }
}
require_once './footer.php';