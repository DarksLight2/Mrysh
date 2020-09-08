<?php

require_once './app/configs/systemSettigns.php';

use app\classes\User;

if(User::userData())
{
    header('Location: /');
}

if( ! isset($_GET['attack']))
{
    require_once './header.php';
?>

    <div class="ribbon"><div class="rl"><div class="rr">Возвращение домой</div></div></div>

    <div class="hr_g mb2"><div><div></div></div></div>

<div class="bntf"><div class="nl"><div class="nr cntr lyell lh1 p5 sh">
            Возвращаясь из похода вы встретили Волка <br><span class="small">— Придется с ним сразиться, чтобы пройти дальше</span>	</div></div></div>

<div class="hr_g mb2"><div><div></div></div></div>

<div class="bdr bg_blue mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                    <div class="ml5 mt5 cntr">
                                        <a href="/first_attack?attack"><img src="http://144.76.127.94/view/image/lair/lair1_nowin.jpg"></a>
                                    </div>
                                    <div class="clb"></div>
                                </div></div></div></div></div></div></div></div></div>

<div class="cntr"><a href="/first_attack?attack" class="ubtn inbl mt-15 red mb5"><span class="ul"><span class="ur">Атаковать</span></span></a></div>
<?php
}
else
{

    if(User::createUser() != 'successful_registration')
    {
        echo 'Ошибка регистрации... ['.User::createUser().']';
    }

    require_once './header.php';
    ?>
    <div class="ribbon"><div class="rl"><div class="rr">Возвращение домой</div></div></div>

    <div class="hr_g mb2"><div><div></div></div></div>

    <div class="bntf"><div class="nl"><div class="nr cntr lyell lh1 p5 sh">
                Волк отступил, но он охранял что-то ценное <div class="mb10"></div><span class="win"><b>Награда:</b></span><div class="mb5"></div>
                Серебро <img class="icon" src="http://144.76.127.94/view/image/icons/silver.png">12, Опыт <img class="icon" src="http://144.76.127.94/view/image/icons/expirience.png">9<div class="mb5"></div>
                <div class="inbl lft mt5 w200px">
                    <div class="fl mr5">
                        <img class="item_icon" src="http://144.76.127.94/view/image/item/chest1.png">
                    </div>
                    <div class="ml58 nwr">
                        Серебряный сундук
                    </div>
                </div>
                <div class="cntr"><a href="/" class="ubtn inbl mt10 green mb5"><span class="ul"><span class="ur">Продолжить путь</span></span></a></div>
            </div></div></div>

    <div class="hr_g mb2"><div><div></div></div></div>

    <div class="bdr bg_blue mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                        <div class="ml5 mt5 cntr">
                                            <a href="/first_attack?attack=1&amp;r=819"><img src="http://144.76.127.94/view/image/lair/lair1_nowin.jpg"></a>
                                        </div>
                                        <div class="clb"></div>
                                    </div></div></div></div></div></div></div></div></div>
<?php
}

require_once './footer.php';
