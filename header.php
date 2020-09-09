<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use app\classes\User;

$startLoadPage = microtime();

$startLoadPage_array = explode(" ",$startLoadPage);

$startLoadPage = $startLoadPage_array[1] + $startLoadPage_array[0];

$UserData = User::userData();
?>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="author" content="Overmobile">
    <meta name="keywords" content="разрушители, overmobile, овермобайл, онлайн игра, пвп, сражения, pvp, приключения, прокачка героя, арена, битва, турнир, подземелья, бесплатная, mmorpg">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
    <link rel="icon" href="https://144.76.127.94/view/image/icons/favicon.png?1" type="image/png">
    <link rel="stylesheet" type="text/css" media="all" href="/view/style/index.css">
    <title><?
        if(isset($title))
        {
            echo $title;
        }
        ?></title>
    <link rel="stylesheet" type="text/css" href="chrome-extension://ldbfffpdfgghehkkckifnjhoncdgjkib/styles.css"></head>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="/js/ajax.js"></script>

<body id="bg">
<?php

if($UserData)
{
    User::newLevel();
?>
    <div id="header" style="ov_h"><div class="cntr small lorange mt5 mb5" style="position: relative">
            <img class="icon" src="http://144.76.127.94/view/image/icons/strength.png"> <?=(isset($_SESSION['train']['strength'])) ? '<span class="win">'.$UserData['strength'].'</span>' : $UserData['strength']?>	<img class="icon" src="http://144.76.127.94/view/image/icons/health.png"> <?=(isset($_SESSION['train']['health'])) ? '<span class="win">'.$UserData['health'].'</span>' : $UserData['health']?>	<img class="icon" src="http://144.76.127.94/view/image/icons/defense.png"> <?=(isset($_SESSION['train']['defence'])) ? '<span class="win">'.$UserData['defence'].'</span>' : $UserData['defence']?>


        </div>
        <div class="hr_g mb2"><div><div></div></div></div>
        <table class="small yell h25 bgc_prg"><tbody><tr>
                <td class="va_m plr10 nwr"><img src="http://144.76.127.94/view/image/icons/up.png" class="va_t" height="16" width="16" alt=""><?=$UserData['level']?></td>
                <td class="va_m w100"><div class="prg-bar"><div class="prg-blue" style="width: <?=User::progressExp()?>%"></div></div></td>
                <td class="va_m plr10"><?=User::progressExp()?>%</td>
            </tr></tbody></table>
        <div class="hr_g mb2"><div><div></div></div></div></div>
    <div class="ribbon mb2"><div class="rl"><div class="rr">
                <?
                if(isset($title))
                {
                    echo $title;
                }
                ?>	</div></div></div>
<?php

    if(isset($_SESSION['newLevel']) && $_SESSION['newLevel'] === 1)
    {
        echo '<div class="bntf"><div class="nl"><div class="nr cntr lyell lh1 p5 sh"><span class="win"><b>Вы получили новый уровень!<br>Награда: <img class="icon" src="http://144.76.127.94/view/image/icons/gold.png">5</b></span><br></div></div></div><div class="hr_g mb2"><div><div></div></div></div></div>';

        unset($_SESSION['newLevel']);
    }
}
if(User::userData() !== false && User::userData()['level'] >= 3 && User::userData()['login'] == 'Новобранец')
{
?>
<div class="bntf">
    <div class="nl">
        <div class="nr cntr lyell lh1 p5 sh">
            <a href="/save" class="save_link"><b>Сохранись</b></a>

            <span class="save_link"> и получи <span class="nowrap"><img class="icon" src="http://144.76.127.94/view/image/icons/gold.png">5</span></span>

            <br>

        </div>
    </div>
</div>

<div class="hr_g mb2"><div><div></div></div></div>
<?php
}