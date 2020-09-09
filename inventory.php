<?php

require_once './app/configs/systemSettigns.php';

use app\classes\User;
use app\classes\Inventory;

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
                                                <a href="/view_player_item?player_id=10528964&amp;id=1421006985&amp;type=cloth&amp;PHPSESSID=d551b1bdd58b9ac9c04e4be25522e9ac.1599602725.78752281"><img class="item_icon" width="48" height="48" src="http://144.76.127.94/view/image/item/17_head.png"></a>
                                            </div>

                                            <div class="clb"></div>

                                        </div>

                                        <div class="ml58 mt5 mb5 sh small">
                                            <a href="/view_player_item?player_id=10528964&amp;id=1421006985&amp;type=cloth&amp;PHPSESSID=d551b1bdd58b9ac9c04e4be25522e9ac.1599602725.78752281">Шлем Ополченца</a>
                                        </div>

                                        <div class="ml58 mt5 mb5 sh small">
                                            <img class="icon" src="http://144.76.127.94/view/image/quality_cloth/1.png"> <span class="q1">Обычный [1/5]</span>
                                        </div>

                                        <div class="ml58 mt5 mb5 sh small">

                                            <a class="sell_link" href="/join_item?id=1421006985&amp;reforge=1&amp;page=1&amp;PHPSESSID=d551b1bdd58b9ac9c04e4be25522e9ac.1599602725.78752281">Разобрать</a>

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
?>
<div class="hr_g mb2"><div><div></div></div></div>

<div class="nr cntr lyell small lh1 plr15 pt5 pb5 sh">
    Можно улучшать снаряжение разбирая ненужные вещи</div>

<div class="hr_g mb2"><div><div></div></div></div>

<a href="/gear" class="mbtn mb2"><img src="http://144.76.127.94/view/image/icons/slots.png" class="icon"> Снаряжение </a>

<?php

require_once './footer.php';
