<?php

require_once './app/configs/systemSettigns.php';

use app\classes\User;
use app\classes\Arena;
use app\classes\Fights;

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

if(isset($_GET['attack']))
{

    Arena::attack();

    header('Location: /arena');

    exit();

}

Fights::getEnemy('arena');

$title = 'Арена';

require_once './header.php';

if(isset($_SESSION['arena']['Rewards']))
{
    $Status = '';

    switch ($_SESSION['arena']['status'])
    {
        case 'lose':
            $Status = 'Поражение';
            break;

        case 'win':
            $Status = 'Победа';
            break;
    }
?>
    <div class="bntf"><div class="nl"><div class="nr cntr lyell lh1 p5 sh"><span class="<?=$_SESSION['arena']['status']?>"><b><?=$Status?></b></span><br>Серебро <img class="icon" src="http://144.76.127.94/view/image/icons/silver.png"><?=$_SESSION['arena']['Rewards']['Silver']?>, Опыт <img class="icon" src="http://144.76.127.94/view/image/icons/expirience.png"><?=$_SESSION['arena']['Rewards']['Exp']?></div></div></div>
<?php

    unset($_SESSION['arena']['Rewards']);
}
if(User::userData()['arena_fights'] == 0)
{
?>
    <script>
        setInterval(function (){
            getTimeLeft();
        }, 1000);
    </script>

    <div class="bdr cnr bg_blue cntr"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                        <div class="ml5 mt5 mb5 sh small">
                                            <img src="http://144.76.127.94/view/image/arena/arena_grey.png" alt="" class="icon">
                                            15 боев закончились, возвращайтесь через<br> <span id="cooldown_arena">[загрузка...]</span> чтобы продолжить сражения		</div>

                                        <div class="clb"></div>
                                    </div></div></div></div></div></div></div></div></div>
    <br>
    <div class="cntr"><a class="ubtn inbl mt-15 mb5 green" href="/"><span class="ul"><span class="ur">На главную</span></span></a></div>
<?php
}
else
{
?>

    <div class="ribbon mb2"><div class="rl"><div class="rr">
                Арена (<?=User::userData()['arena_fights']?> боев)	</div></div></div>

    <div class="bdr bg_blue mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                        <div class="fl ml5 mt5">

                                            <div class="iqcont3">
                                                <div class="ieblck29">
                                                    <a href="/arena/?attack"><?=User::Maneken($_SESSION['arena']['Enemy']['id'])?></a>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="ml130 mt10 mb10 mr10 sh">
                                            <span class="lwhite tdn"><img class="icon" src="http://144.76.127.94/view/image/icons/hero.png"> <?=$_SESSION['arena']['Enemy']['login']?></span><br> <br><img class="icon" src="http://144.76.127.94/view/image/icons/strength.png"> Сила: <?=$_SESSION['arena']['Enemy']['strength']?><br><img class="icon" src="http://144.76.127.94/view/image/icons/health.png"> Здоровье: <?=$_SESSION['arena']['Enemy']['health']?><br><img class="icon" src="http://144.76.127.94/view/image/icons/defense.png"> Броня: <?=$_SESSION['arena']['Enemy']['defence']?><br>			</div>
                                        <div class="clb"></div>
                                    </div></div></div></div></div></div></div></div></div>

    <div class="aqcont9"><div class="adblck17"><div class="cntr"><a href="/arena/?attack" class="ubtn inbl mt-15 red mb5"><span class="ul"><span class="ur">Атаковать</span></span></a></div></div></div>

<?php
}


require_once './footer.php';