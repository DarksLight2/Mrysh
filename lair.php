<?php

require_once './app/configs/systemSettigns.php';

use app\classes\Lair;
use app\classes\User;
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

if(isset($_GET['action']) && $_GET['action'] == 'enter')
{
    Lair::EnterInFight();

    header('Location: /lair');

}

$LairData = Lair::GetData();
$MobData = Lair::GetMob($LairData['lair_mob']);

if(isset($_GET['action']) && $_GET['action'] == 'attack')
{
    Lair::attack($MobData, $LairData);
}

$title = $MobData['place'];

require_once './header.php';

if(isset($_SESSION['lair']['end']))
{
    $Rewards = Fights::getRewards($_SESSION['lair']['end'], $_SESSION['lair']['UserDamage'], User::userData()['level'], false, 2, true);

    $Text = 'Вы отступили.<br>
                Возвращайтесь когда станете сильнее';

    if($_SESSION['lair']['end'] == 'win')
    {
        $Text = 'Win';
    }

?>
    <div class="hr_g mb2"><div><div></div></div></div>

    <div class="bntf">
        <div class="nl">
            <div class="nr cntr lyell lh1 p5 sh"><?=$Text?><br><br>
                <span class="<?=$_SESSION['lair']['end']?>">Награда:</span>
                <img class="icon" src="http://144.76.127.94/view/image/icons/gold.png"><?=$Rewards['Gold']?>,
                <img class="icon" src="http://144.76.127.94/view/image/icons/expirience.png"><?=$Rewards['Exp']?>
                <div class="mb5"></div>
                <a href="/?check_cd=1" class="ubtn inbl green">
                    <span class="ul">
                        <span class="ur">Забрать награду</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
<?php
}

if($LairData['start'] === 0)
{
?>

<div class="bdr bg_blue"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                    <div class="mt10 mb10 mr10 sh cntr">
                                        <span class="lwhite tdn">Вы встретили <?=Lair::NamesMobs($MobData['name'], 0)?></span>
                                    </div>
                                    <div class="cntr">
                                        <a href="/lair?action=enter"><img src="http://144.76.127.94/view/image/lair/lair<?=$MobData['id']?>_nowin.jpg"></a>
                                    </div>
                                    <div class="mt5 mb5 mr10 sh cntr small lorange">
                                        <img class="icon" src="http://144.76.127.94/view/image/icons/strength.png"> <?=$MobData['strength']?> <img class="icon" src="http://144.76.127.94/view/image/icons/health.png"> <?=$MobData['health']?> <img class="icon" src="http://144.76.127.94/view/image/icons/defense.png"> <?=$MobData['defence']?>		</div>
                                    <div class="clb"></div>
                                </div></div></div></div></div></div></div></div>
</div>
<br>
<div class="cntr"><a href="/lair?action=enter" class="ubtn inbl mt-15 red mb5"><span class="ul"><span class="ur">Начать бой</span></span></a></div>

<?php
}
else
{

    $UserProgressHealth = Fights::progressHealth(User::getUserDataByID()['max_health'], User::getUserDataByID()['health']);
?>
    <div class="bdr bg_red">
        <div class="wr1">
            <div class="wr2">
                <div class="wr3">
                    <div class="wr4">
                        <div class="wr5">
                            <div class="wr6">
                                <div class="wr7">
                                    <div class="wr8">
                                        <div class="fl ml10 mt10">
                                            <div class="ibgcont5">
                                                <div class="icbtn30">
                                                    <a class="nd" href="/lair?action=attack&amp;r=464&amp;hash=6015">
                                                        <img src="http://144.76.125.123/view/image/lair/lair1.png">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ml68 mt10 mb10 mr10 sh">
                                            <span class="lwhite tdn"><?=$MobData['name']?></span>
                                            <div class="small mb2">
                                                <?php
                                                if(isset($_SESSION['lair']['UserDamage']))
                                                {
                                                ?>
                                                <span class="fr rdmg">-<?=$_SESSION['lair']['UserDamage']?></span>
                                                <?php
                                                }
                                                ?>
                                                <span class="lorange">

								                    <img src="http://144.76.127.94/view/image/icons/strength.png" class="va_t" height="16" width="16" alt=""> <?=$MobData['strength']?>
                                                    <img src="http://144.76.127.94/view/image/icons/health.png" class="va_t" height="16" width="16" alt=""> <?=$LairData['health_mob']?>
                                                    <img src="http://144.76.127.94/view/image/icons/defense.png" class="va_t" height="16" width="16" alt=""> <?=$MobData['defence']?>

                                                </span>
                                            </div>

                                            <div class="prg-bar fght"><div class="prg-green fl" style="width:<?=Fights::progressHealth($MobData['health'], $LairData['health_mob'])?>%;"></div><div class="prg-red fl" style="width:<?=$_SESSION['lair']['ProgressHealthMob'] - Fights::progressHealth($MobData['health'], $LairData['health_mob'])?>%;"></div></div></div>
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

    <div class="abgcont10"><div class="acbtn18"><div class="cntr"><a href="/lair?action=attack" class="ubtn inbl mt-15 red mb2"><span class="ul"><span class="ur">Атаковать</span></span></a></div></div></div>

    <div class="bdr bg_blue mb2">
        <div class="wr1">
            <div class="wr2">
                <div class="wr3">
                    <div class="wr4">
                        <div class="wr5">
                            <div class="wr6">
                                <div class="wr7">
                                    <div class="wr8">
                                        <div class="fl ml10 mt10">
                                            <div class="ibgcont5">
                                                <div class="icbtn30">
                                                    <a class="nd" href="/lair?action=attack">
                                                        maneken
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ml68 mt10 mb10 mr10 sh">
                                            <span class="lwhite tdn"><?=User::userData()['login']?></span>
                                            <div class="small mb2">
                                                <?php
                                                if(isset($_SESSION['lair']['UserDamage']))
                                                {
                                                    ?>
                                                    <span class="fr rdmg">-<?=$_SESSION['lair']['MobDamage']?></span>
                                                    <?php
                                                }
                                                ?>
                                                <span class="lorange">

								                    <img src="http://144.76.127.94/view/image/icons/strength.png" class="va_t" height="16" width="16" alt=""> <?=User::userData()['strength']?>
                                                    <img src="http://144.76.127.94/view/image/icons/health.png" class="va_t" height="16" width="16" alt=""> <?=User::getUserDataByID()['health']?>
                                                    <img src="http://144.76.127.94/view/image/icons/defense.png" class="va_t" height="16" width="16" alt=""> <?=User::userData()['defence']?>

                                                </span>
                                            </div>

                                            <div class="prg-bar fght"><div class="prg-green fl" style="width:<?=$UserProgressHealth?>%;"></div><div class="prg-red fl" style="width:<?=$_SESSION['lair']['ProgressHealthUser'] - $UserProgressHealth?>%;"></div></div></div>
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

    <div class="bntf"><div class="nl"><div class="nr cntr small lyell lh1 p5 sh">
                Подробнее <a class="lyell" href="/view_lair">о <?=Lair::NamesMobs($MobData['name'], 1)?></a>		</div></div></div>

    <div class="hr_g mb2"><div><div></div></div></div>
<?php
$_SESSION['lair']['ProgressHealthMob'] = Fights::progressHealth($MobData['health'], $LairData['health_mob']);
$_SESSION['lair']['ProgressHealthUser'] = $UserProgressHealth;

unset($_SESSION['lair']['UserDamage']);
unset($_SESSION['lair']['MobDamage']);

require_once './footer.php';
