<?php

require_once './app/configs/systemSettigns.php';

use app\classes\SiteSettigns;
use app\classes\User;
use app\classes\Train;

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

$title = 'Тренировка';

Train::CreateTrain();

if(isset($_GET['skill']))
{
    if($_GET['skill'] == 'strength' || $_GET['skill'] == 'health' || $_GET['skill'] == 'defence')
    {
        Train::Training($_GET['skill'], Train::GetLevel($_GET['skill']));
        header('Location: /train');
    }
    exit('Нахуй иди.');
}

require_once './header.php';

$PriceStrength = Train::GetPrice(Train::GetLevel('strength'));
$PriceHealth = Train::GetPrice(Train::GetLevel('health'));
$PriceDefence = Train::GetPrice(Train::GetLevel('defence'));

?>
<div class="cntr lorange small mt-5">
	<span class="nowrap">
		Ваши ресурсы:  <img class="icon" src="http://144.76.127.94/view/image/icons/gold.png"><?=SiteSettigns::Numbers(User::userData()['gold'])?>, <img class="icon" src="http://144.76.127.94/view/image/icons/silver.png"><?=SiteSettigns::Numbers(User::userData()['silver'])?>	</span>
</div>

<div class="bdr bg_green">
    <div class="wr1">
        <div class="wr2">
            <div class="wr3">
                <div class="wr4">
                    <div class="wr5">
                        <div class="wr6">
                            <div class="wr7">
                                <div class="wr8">

                                    <div class="fl ml10 mt10">
                                        <img class="item_icon" src="http://144.76.127.94/view/image/train/strength.png">
                                    </div>

                                    <div class="ml68 mt10 mb10 mr10 sh small lorange">
                                        <span class="medium lwhite tdn">Сила <?=(isset($_SESSION['train']['strength'])) ? '<span class="win">+'.(Train::GetLevel('strength') * 3).'</span>' : '+'.(Train::GetLevel('strength') * 3)?></span><br>
                                        <span><span class="text_small">Увеличивает наносимый врагам урон</span><br>Уровень: <?=Train::GetLevel('strength')?> из 200</span>
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
<div class="cntr">
    <a href="/train?skill=strength" class="ubtn inbl green mb5 mt-15 ml5 mr5">
        <span class="ul">
            <span class="ur">Тренировать  за <img src="http://144.76.127.94/view/image/icons/<?=$PriceStrength[1]?>.png" class="icon"><?=$PriceStrength[0]?></span>
        </span>
    </a>
</div>

<div class="bdr bg_green">
    <div class="wr1">
        <div class="wr2">
            <div class="wr3">
                <div class="wr4">
                    <div class="wr5">
                        <div class="wr6">
                            <div class="wr7">
                                <div class="wr8">

                                    <div class="fl ml10 mt10">
                                        <img class="item_icon" src="http://144.76.127.94/view/image/train/health.png">
                                    </div>

                                    <div class="ml68 mt10 mb10 mr10 sh small lorange">
                                        <span class="medium lwhite tdn">Здоровье <?=(isset($_SESSION['train']['health'])) ? '<span class="win">+'.(Train::GetLevel('health') * 3).'</span>' : '+'.(Train::GetLevel('health') * 3)?></span><br>
                                        <span><span class="text_small">Увеличивает наносимый врагам урон</span><br>Уровень: <?=Train::GetLevel('health')?> из 200</span>
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
<div class="cntr">
    <a href="/train?skill=health" class="ubtn inbl green mb5 mt-15 ml5 mr5">
        <span class="ul">
            <span class="ur">Тренировать  за <img src="http://144.76.127.94/view/image/icons/<?=$PriceHealth[1]?>.png" class="icon"><?=$PriceHealth[0]?></span>
        </span>
    </a>
</div>

<div class="bdr bg_green">
    <div class="wr1">
        <div class="wr2">
            <div class="wr3">
                <div class="wr4">
                    <div class="wr5">
                        <div class="wr6">
                            <div class="wr7">
                                <div class="wr8">

                                    <div class="fl ml10 mt10">
                                        <img class="item_icon" src="http://144.76.127.94/view/image/train/defense.png">
                                    </div>

                                    <div class="ml68 mt10 mb10 mr10 sh small lorange">
                                        <span class="medium lwhite tdn">Броня <?=(isset($_SESSION['train']['defence'])) ? '<span class="win">+'.(Train::GetLevel('defence') * 3).'</span>' : '+'.(Train::GetLevel('defence') * 3)?></span><br>
                                        <span><span class="text_small">Увеличивает наносимый врагам урон</span><br>Уровень: <?=Train::GetLevel('defence')?> из 200</span>
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
<div class="cntr">
    <a href="/train?skill=defence" class="ubtn inbl green mb5 mt-15 ml5 mr5">
        <span class="ul">
            <span class="ur">Тренировать  за <img src="http://144.76.127.94/view/image/icons/<?=$PriceDefence[1]?>.png" class="icon"><?=$PriceDefence[0]?></span>
        </span>
    </a>
</div>

    <div class="hr_g mb2"><div><div></div></div></div>
    <div class="bntf"><div class="small"><div class="nl"><div class="nr cntr lyell small lh1 plr15 pt5 pb5 sh">
                    Тренировка увеличивает параметры вашего героя</div></div></div></div>
    <div class="hr_g mb2"><div><div></div></div></div>
<?php

unset($_SESSION['train']);

require_once './footer.php';