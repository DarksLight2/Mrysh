<?php

require_once './app/configs/systemSettigns.php';

use app\classes\User;
use app\classes\Travel;

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

if(isset($_GET['start']) && is_numeric($_GET['start']))
{
    Travel::ChangeStartStatus($_GET['start'], 'start');
}
if(isset($_GET['end']))
{
    Travel::ChangeStartStatus($_GET['end'], 'end');
}

$title = 'Набег';

require_once './header.php';

if(isset($_SESSION['travel']['Start']) && $_SESSION['travel']['Start'] === 1)
{
    if(isset($_GET['attack']))
    {
        echo 'attacked';
    }

?>
    <div class="bdr cnr bg_green cntr"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
                                        <div class="ml5 mt5 mb5 sh small">
                                            <span id="timer_travel_start"><img src="http://144.76.127.94/view/image/travel/travel_progress.jpg" alt=""><br><span>Ваш герой участвует в набеге.</span><br><span>До конца осталось 59м 32с</span></span>
                                        </div>
                                        <div class="clb"></div>
                                    </div></div></div></div></div></div></div></div></div>
<br>
    <div class="cntr"><a class="ubtn inbl mt-15 mb5 green" href="/?PHPSESSID=827e06a06fe5b8becc6a2b4ade4ddf4e.1602332227.51265586"><span class="ul"><span class="ur">На главную</span></span></a></div>
<?php
}
else
{

    foreach (Travel::ShowAllPlaces() as $Place)
    {
        $SlyleComplexity = 'lose';

        $Difference = User::userData()['strength'] - $Place['need_strength'];

        if($Difference >= 0 && $Difference <= 50)
        {
            $Complexity = 'Сложно';
            $StyleComplexity = 'lose';
        }
        elseif($Difference >= 51 && $Difference <= 100)
        {
            $Complexity = 'Среднее';
            $SlyleComplexity = 'lorange';
        }
        else
        {
            $Complexity = 'Легко';
            $SlyleComplexity = 'lorange';
        }
?>
    <div class="bdr cnr bg_green">
        <div class="wr1">
            <div class="wr2">
                <div class="wr3">
                    <div class="wr4">
                        <div class="wr5">
                            <div class="wr6"
                            ><div class="wr7">
                                    <div class="wr8">

                                        <div class="fl ml5 mt5 mr10 mb5">
                                            <img src="http://144.76.127.94/view/image/travel/location<?=$Place['id']?>.jpg">
                                        </div>
                                        <div class="ml100 mt5 mb2 mlr10 lwhite large">
                                            <?=$Place['name']?>
                                        </div>
                                        <div class="ml100 mb2 mlr10 <?=$SlyleComplexity?> small">
                                            <?=$Complexity?> (<?=time_left($Place['time'], false)?>)
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
    <div class="cntr"><a href="?start=<?=$Place['id']?>" class="ubtn inbl green mb5 mt-15 w50"><span class="ul"><span class="ur">В <?=mb_strtolower(explode(' ', $Place['name'])[0])?></span></span></a></div>
<?php
    }

    if(Travel::ShowNextNotAvailablePlace()->num_rows === 1)
    {
        $Place = Travel::ShowNextNotAvailablePlace()->fetch_assoc();

?>
    <div class="bdr cnr bg_green">
        <div class="wr1">
            <div class="wr2">
                <div class="wr3">
                    <div class="wr4">
                        <div class="wr5">
                            <div class="wr6">
                                <div class="wr7">
                                    <div class="wr8">

                                        <div class="fl ml5 mt5 mr10 mb5">
                                            <img src="http://144.76.127.94/view/image/travel/location<?=$Place['id']?>.jpg">
                                        </div>
                                        <div class="ml100 mt5 mb2 mlr10 lwhite large">
                                            <?=$Place['name']?>
                                        </div>
                                        <div class="ml100 mb2 mlr10 lorange small">
                                            <span class="grey1">Требуется <?=$Place['need_strength']?> силы</span>
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
    <div class="cntr"><span class="ubtn inbl grey mb5 mt-15 w50"><span class="ul"><span class="ur">В <?=mb_strtolower(explode(' ', $Place['name'])[0])?></span></span></span></div>
<?php
    }
?>
    <div class="hr_g mb2"><div><div></div></div></div>

<div class="bntf"><div class="small"><div class="nl"><div class="nr cntr lyell small lh1 plr15 pt5 pb5 sh">
                В набегах можно зарабатывать опыт и серебро. Чем продолжительнее набег, тем больше награда!				</div></div></div></div>

    <div class="hr_g mb2"><div><div></div></div></div>

<?php
}
require_once './footer.php';

