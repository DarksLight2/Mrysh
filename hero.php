<?php

require_once './app/configs/systemSettigns.php';

use app\classes\SiteSettigns;
use app\classes\User;

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

$ThisUser = false;

if( ! isset($_GET['userID']) || $_GET['userID'] === User::userData()['id'])
{
    $UserData = User::userData();

    $title = 'Мой герой';

    $ThisUser = true;
}
else
{
    $UserData = User::getUserDataByID($_GET['userID']);

    $title = $UserData['login'];
}

require_once './header.php';

?>

<div class="bdr bg_main mb2"><div class="light"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                        <table class="dummy">
                                            <tbody><tr>
                                                <td class="w39px"><div class="slot">
                                                    </div></td>

                                                <td colspan="2" rowspan="4"><div class="pic_shd"><a class="nd" href="/avatar"><img src="http://144.76.125.123/maneken/42_0_44_45_46_47_0_48_529587657_avatar0.jpg?1.3.78"></a></div></td>

                                                <td class="w39px"><div class="slot">
                                                    </div></td>

                                            </tr><tr>
                                                <td class="w39px"><div class="slot">
                                                    </div></td>


                                                <td class="w39px"><div class="slot">
                                                    </div></td>

                                            </tr><tr>
                                                <td class="w39px"><div class="slot">
                                                    </div></td>


                                                <td class="w39px"><div class="slot">
                                                    </div></td>

                                            </tr><tr>
                                                <td class="w39px"><div class="slot">
                                                    </div></td>


                                                <td class="w39px"><div class="slot">
                                                    </div></td>

                                            </tr>	</tbody></table>
                                    </div></div></div></div></div></div></div></div></div></div>


<div class="bdr bg_main mb2">
    <div class="light">
        <div class="wr1">
            <div class="wr2">
                <div class="wr3">
                    <div class="wr4">
                        <div class="wr5">
                            <div class="wr6">
                                <div class="wr7">
                                    <div class="wr8">
                                        <div class="yell mlr10 mt5 mb5">
                                            <span class="nowrap win">
                                                <img class="icon" src="http://144.76.127.94/view/image/icons/hero_on_0.png">

                                                <span class="win">
                                                    <?php

                                                        if( ! isset($_GET['userID']) || $_GET['userID'] === User::userData()['id'])
                                                        {
                                                            echo 'Вы';
                                                        }
                                                        else
                                                        {
                                                            echo $UserData['login'];
                                                        }
                                                    ?>
                                                </span>, <?=$UserData['level']?> уровень
                                            </span>

                                            <br>

                                            <span class="nowrap"><img class="icon" src="http://144.76.127.94/view/image/icons/expirience.png"> Опыт: <?=SiteSettigns::Numbers($UserData['exp'])?> / <?=SiteSettigns::Numbers(User::levels($UserData['level']))?></span><br>
                                            <span class="nowrap"><img class="icon" src="http://144.76.127.94/view/image/icons/strength.png"> Сила: <?=SiteSettigns::Numbers($UserData['strength'])?></span><br>
                                            <span class="nowrap"><img class="icon" src="http://144.76.127.94/view/image/icons/health.png"> Здоровье: <?=SiteSettigns::Numbers($UserData['health'])?></span><br>
                                            <span class="nowrap"><img class="icon" src="http://144.76.127.94/view/image/icons/defense.png"> Броня: <?=SiteSettigns::Numbers($UserData['defence'])?></span><br>
                                            <span class="nowrap"><img class="icon" src="http://144.76.127.94/view/image/icons/gold.png"> Золото: <?=SiteSettigns::Numbers($UserData['gold'])?></span>
                                            <span class="nowrap"><img class="icon" src="http://144.76.127.94/view/image/icons/silver.png"> Серебро: <?=SiteSettigns::Numbers($UserData['silver'])?></span><br>

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
</div>

<?php
if($ThisUser === false)
{
?>
    <a class="mbtn mb2" href="/post_message?player_id=8233419"><img src="http://144.76.127.94/view/image/icons/post.png" class="icon"> Отправить почту</a>
    <a class="mbtn mb2" href="/add_friend?player_id=8233419"><img class="icon" src="http://144.76.127.94/view/image/icons/friend.png"> Добавить в друзья</a>
<?php
}
else
{
?>
    <a class="mbtn mb2" href="/view_posters"><img src="http://144.76.127.94/view/image/icons/post.png" class="icon"> Моя почта</a>
<?php
}
?>
<a class="mbtn mb2" href="/view_gifts?player_id=8233419"><img class="icon" src="http://144.76.127.94/view/image/icons/gift_s.png"> Подарки (314)</a>
<a class="mbtn mb2" href="/records?player_id=8233419"><img class="icon" src="http://144.76.127.94/view/image/records/records.png"> Достижения (59)</a>

<div class="hr_g mb2"><div><div></div></div></div>

<a class="mbtn mb2" href="/view_gear?player_id=8233419"><img src="http://144.76.127.94/view/image/icons/slots.png" class="icon"> Снаряжение <span class="text_vmenu">(8 из 8)</span></a>
<a class="mbtn mb2" href="/view_amulet?player_id=8233419"><img class="icon" src="http://144.76.127.94/view/image/icons/amulet.png"> Амулет  (85  из  85)</a>

<div class="mb10"></div>

<a class="mbtn mb2" href="/view_trophies?player_id=8233419"><img src="http://144.76.127.94/view/image/icons/trophies.png" class="icon" alt="" width="20px"> Трофеи (23)</a>
<a class="mbtn mb2" href="/view_train?player_id=8233419"><img class="icon" src="http://144.76.127.94/view/image/icons/train.png"> Тренировка  (600)</a>
<a class="mbtn mb2" href="/view_abilities?player_id=8233419"><img class="icon" src="http://144.76.127.94/view/image/icons/ability.png"> Умения  (900)</a>

<?php

require_once './footer.php';