<?php

require_once './app/configs/systemSettigns.php';

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

$title = 'Мой герой';

require_once './header.php';
?>
    <div class="cntr">
        <?=User::Maneken(null, [240, 320])?>
    </div>

    <div class="hr_g mb2"><div><div></div></div></div>

    <a class="mbtn mb2" href="/hero"><img class="icon" src="http://144.76.127.94/view/image/icons/back.png"> Назад в профиль</a>

    <div class="hr_g mb2"><div><div></div></div></div>

    <div class="bntf"><div class="small"><div class="nl"><div class="nr cntr lyell small lh1 plr15 pt5 pb5 sh">
                    Самое лучшее снаряжение всегда можно купить в магазине</div></div></div></div>

    <div class="hr_g mb2"><div><div></div></div></div>
<?php
require_once './footer.php';
?>