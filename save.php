<?php

require_once './app/configs/systemSettigns.php';

use app\classes\User;

if(User::userData() === false || User::userData()['gender'] !== null)
{
    ?>
    Раздел сайта НЕ доступен для авторизированых игроков.

    <script>
        setTimeout(function (){
            history.back();
        }, 3000)
    </script>
    <?php
    exit();
}

$title = 'Сохранение';

require_once './header.php';
?>

<div class="hr_g mb2"><div><div></div></div></div>

<div class="bntf"><div class="nl"><div class="nr cntr lyell lh1 p5 nd sh">
            За сохранение вы получите <span class="nowrap"><img class="icon" src="http://144.76.127.94/view/image/icons/gold.png">5</span> золота!</div></div></div>

<div class="hr_g mb2"><div><div></div></div></div>

<form action="/save_player" method="POST">
    <div class="bdr bg_blue mb10"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                        <div class="ml10 mt5 mb5 mr10 sh cntr">
                                            Имя			<div class="mb2"></div>
                                            <input type="text" name="name" value="">
                                            <div class="mb5"></div>
                                            Пароль			<div class="mb2"></div>
                                            <input type="text" name="password" value="">
                                            <div class="mb5"></div>
                                            E-mail				<div class="mb2"></div>
                                            <input type="text" name="email" value=""><br>
                                            <span class="small grey1">Введите <span class="text_red">правильный</span> e-mail, иначе вы не сможете восстановить вашего героя!</span>
                                            <div class="mb5"></div>
                                            Пол:
                                            <input id="gender0" type="radio" name="gender" value="0" checked="checked"><label for="gender0" class="small">Мужской</label>
                                            <input id="gender1" type="radio" name="gender" value="1"><label for="gender1" class="small">Женский</label>
                                            <div class="mb2"></div>
                                        </div>
                                    </div></div></div></div></div></div></div></div></div>
    <div class="cntr"><span class="ubtn inbl mt-15 green"><span class="ul"><input class="ur" type="submit" value="Сохранить"></span></span></div>
</form>

