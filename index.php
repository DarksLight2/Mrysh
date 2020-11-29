<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './app/configs/systemSettigns.php';

$title = 'Главная';

require_once './header.php';

use app\classes\User;
use app\classes\ElementsMenu;
use app\classes\Lair;

if(User::userData())
{
?>
<div id="new_message_index" style="display: none">
    <a href="/view_posters" class="mbtn m2">
        <img class="icon" src="http://144.76.127.94/view/image/icons/post_new.png" /> Новая почта
    </a>
</div>
    <div class="hr_g mb2"><div><div></div></div></div>
<?php

    if(Lair::GetData()['cooldown'] > time() && Lair::GetData()['fights'] == 0)
    {
?>
        <script>
            setInterval(function (){
                getTimeLeft('lair');
            }, 1000);
        </script>

        <div class="bdr cnr bg_blue mb2 bl nd">

            <div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                            <div class="fl ml10 mt10 mr10">
                                                <img src="http://144.76.127.94/view/image/lair/grey_lair5.png">		</div>
                                            <div class="ml10 mt10 mb10 mr10 sh small" id="groupTimer_index_lair"><span class="grey1"><?=Lair::GetMob()['place']?> откроется через <span id="cooldown_lair">[загрузка...]</span> </span></div>
                                            <div class="clb"></div>
                                        </div></div></div></div></div></div></div></div>

        </div>
<?php
    }
    else
    {
        $Mob = Lair::GetMob(Lair::GetData()['lair_mob']);
?>
    <a href="/lair" class="bdr cnr bg_blue mb2 bl nd">

        <div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                        <div class="fl ml10 mt10 mr10">
                                            <img src="http://144.76.127.94/view/image/lair/lair<?=Lair::GetData()['lair_mob']?>.png">		</div>
                                        <div class="ml10 mt10 mb10 mr10 sh small" id="groupTimer_index_lair">
                                            <span class="medium lwhite tdn bold mt5" href="/lair"><?=$Mob['name']?></span><br><span class="lblue">В <?=$Mob['place']?><br>Осталось боев: <?=Lair::GetData()['fights']?></span>					</div>
                                        <div class="clb"></div>
                                    </div></div></div></div></div></div></div></div>

    </a>
<?php
    }
    if(User::userData()['arena_cooldown'] > time())
    {
?>
        <script>
            setInterval(function (){
                getTimeLeft('arena');
            }, 1000);
        </script>

        <div class="bdr cnr bg_blue mb2 bl nd">

            <div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                            <div class="fl ml10 mt10 mr10">
                                                <img src="http://144.76.127.94/view/image/arena/arena_grey.png" class="icon">		</div>
                                            <div class="ml10 mt10 mb10 mr10 sh small"><span class="grey1">Арена откроется через <span id="cooldown_arena">[загрузка...]</span></span></div>
                                            <div class="clb"></div>
                                        </div></div></div></div></div></div></div></div>

        </div>
<?php
    }
    else
    {
?>
        <a href="/arena" class="bdr cnr bg_blue mb2 bl nd">

            <div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                            <div class="fl ml10 mt10 mr10">
                                                <img src="http://144.76.127.94/view/image/arena/arena.png" class="icon">		</div>
                                            <div class="ml10 mt10 mb10 mr10 sh small" id="groupTimer_index_arena">
                                                <span class="medium lwhite tdn bold mt5">На Арену</span><br><span class="lblue">Осталось боев: <?=User::userData()['arena_fights']?><br></span>							<br>
                                            </div>
                                            <div class="clb"></div>
                                        </div></div></div></div></div></div></div></div>

        </a>
<?php
    }
    ElementsMenu::getElement('travel', 'level', 3);
    ElementsMenu::getElement('pvp', 'level', 3);
    ElementsMenu::getElement('maze', 'level', 3);
    ElementsMenu::getElement('task', 'level', 3);
    ElementsMenu::getElement('train', 'level', 3);

    echo '<br>';

    ElementsMenu::getElement('shop', 'level', 1);
    ElementsMenu::getElement('best', 'level', 3);
    ElementsMenu::getElement('paylist', 'level', 3);
}
else
{ 
?>
    <div class="bdr bg_blue mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                        <div class="ml10 mb10 mr10 small cntr sh">
                                            <img src="http://144.76.127.94/view/image/welcome.jpg"><br> Новая эпическая игра Разрушители!<br>Победи их всех!			</div>
                                        <div class="clb"></div>
                                    </div></div></div></div></div></div></div></div></div>
    <div class="cntr"><a href="/first_attack" class="ubtn mt-15 inbl green mb5"><span class="ul"><span class="ur">Начать игру</span></span></a></div>


    <div class="bdr bg_blue mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                        <div class="ml10 mt10 mb10 mr10 cntr sh">
                                            <form action="/login" method="POST">
                                                Имя<div class="mb2"></div>
                                                <input type="text" name="name" value=""><div class="mb2"></div>
                                                Пароль<div class="mb2"></div>
                                                <input type="password" name="password" value=""><div class="mb5"></div>
                                                <span class="ubtn inbl green"><span class="ul"><input class="ur" type="submit" value="Войти"></span></span>
                                            </form>
                                            <div class="mt10 small">
                                                <a href="/recover_pw" class="darkgreen_link">Забыли пароль?</a>						</div>
                                        </div>
                                        <div class="clb"></div>
                                    </div></div></div></div></div></div></div></div></div>
<?php
}

echo '<div class="hr_g mb2"><div><div></div></div></div>';

require_once './footer.php';