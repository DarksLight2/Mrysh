<?php

use app\classes\ElementsMenu;
use app\classes\User;

if( ! empty($startLoadPage))
{
    $endLoadPage = microtime();

    $endLoadPage_array = explode(" ",$endLoadPage);

    $endLoadPage = $endLoadPage_array[1] + $endLoadPage_array[0];
}
else
{
    $endLoadPage = 0;
    $startLoadPage = 0;
}

if(User::userData())
{
?>
    <div class="hr_g mb2 mt10"><div><div></div></div></div>
<?php
    ElementsMenu::getElement('hero', 'level', 1);
    ElementsMenu::getElement('clan', 'clan', 1);
?>
    <a class="mbtn mb2" href="/"><img class="icon" src="http://144.76.127.94/view/image/icons/home.png"> Главная</a>

    <div class="hr_g mb2"><div><div></div></div></div>

    <div class="cntr">
        <div class="cntr lorange small">
            <img class="icon" src="http://144.76.127.94/view/image/icons/gold.png"><?=User::userData()['gold']?> | <img class="icon" src="http://144.76.127.94/view/image/icons/silver.png"><?=User::userData()['silver']?>
        </div>
    </div>

    <div class="hr_g mb2"><div><div></div></div></div>

    <div class="ftr small">
        <div class="ftr_l cntr">
            <div class="ftr_r cntr">
                <div class="grey1 mb5">
                    <a href="/settings" class="grey1">Настройки</a> | <a href="/chat" class="grey1">Чат</a> |
                    <a class="grey1" href="/online">Онлайн: <?=User::amountUsersOnline()[0]?></a> |
                    <a class="grey1" href="/about">Об игре</a>
                </div>

                <div class="grey2"><?=round($endLoadPage - $startLoadPage, 3)?> сек, <?=date('H:i:s')?><br>
                    <a class="grey2" href="/tickets">Служба поддержки</a><br>
                    <?=COMPANY_NAME?> © <?=date('H:i:s')?>, <?=AGE_TO_GAME?>+<br>
                    <br>
                    <div class="counters">

                    </div>
                </div>
            </div>
        </div>

        <div class="hr_g mb2"><div><div></div></div></div>

        <div class="cntr"></div>

    </div>

    <script>
            setInterval(function (){
                check_new_messages();
            }, 5000)
    </script>
<?php
}
else
{
?>

    <div class="hr_g mb2"><div><div></div></div></div>
    <div class="cntr mb5"><a class="small grey1" href="/about_all">Соглашение</a></div>

    <div class="ftr small">
        <div class="ftr_r cntr">
            <div class="grey2"><?=round($endLoadPage - $startLoadPage, 3)?> сек, <?=date('H:i:s')?><br> <?=COMPANY_NAME?> © <?=date('Y')?>, <?=AGE_TO_GAME?>+
            </div>
        </div>
    </div>

    <div class="hr_g mb2"><div><div></div></div></div>

    <div class="mallbery-caa" style="z-index: 2147483647 !important; text-transform: none !important; position: fixed;"></div>
<?php
}
?>
</body>
</html>