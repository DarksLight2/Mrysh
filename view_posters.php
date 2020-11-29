<?php

require_once './app/configs/systemSettigns.php';

use app\classes\ActiveRecords;
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

$title = 'Почта';

require_once './header.php';

$Dialogs = new ActiveRecords('dialogs');

$count_dialogs_on_page = 10;

$current_page = (isset($_GET['page']) ? (int)$_GET['page'] : 1);
if($current_page < 1)
    $current_page = 1;

$dialog_list = $Dialogs
    ->select()
    ->where(['userID' => User::userData()['id']])
    ->orWhere(['companionID' => User::userData()['id']])
    ->execute();

$count_pages = ceil($dialog_list->count_rows / $count_dialogs_on_page);
$start_select = $current_page * $count_dialogs_on_page - $count_dialogs_on_page;

?>
<div class="bdr cnr bg_blue mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">

                                    <div id="view_posters_box">
                                        <?php
                                            foreach ($Dialogs->select()->where(['userID' => User::userData()['id']])->orWhere(['companionID' => User::userData()['id']])->limit($start_select.', '.$count_dialogs_on_page)->execute()->data as $dialog) {

                                                $Message = new ActiveRecords('messages');
                                                $message = $Message->select('`message`, `sender`, `dialogID`, `read`, `recipient`, `time`')->where(['dialogID' => $dialog->id])->limit()->execute()->data;

                                                $user = (object)User::getUserDataByID($message[0]->sender);

                                                if($message[0]->read === 0 && $message[0]->sender !== User::userData()['id'])
                                                    $classStyle = '';
                                                else
                                                    $classStyle = 'grey1';

                                                if($message[0]->sender !== User::userData()['id']) {
                                                    $recipient = $message[0]->sender;
                                                } else {
                                                    $recipient = $message[0]->recipient;
                                                }
                                        ?>
                                        <div class="ml10 mt5 mr5">
                                            <img class="icon" src="http://144.76.127.94/view/image/icons/hero_off_0.png"> <a class="player_name yell" href="/view_profile?player_id=<?=$user->id?>"><?=$user->login?></a>: <a href="/post_message?player_id=<?=$recipient?>&dialog_id=<?=$dialog->id?>" class="<?=$classStyle?>"><?=(mb_strlen($message[0]->message) > 15 ? mb_strimwidth($message[0]->message, 0, 15).'..' : $message[0]->message)?></a> <span class="grey1 small"><span class="nwr fr"><small><?=(\app\classes\SiteSettigns::past_time($message[0]->time, ['s', 'i', 'h', 'd'], true))?></small></span></span>
                                        </div>
                                        <?php
                                            }
                                            ?>
                                    </div>

</div></div></div></div></div></div></div></div></div>

<?php
$perv_page = '';
$pages = '';
$next_page = '';

if ($current_page != 1)
    $perv_page = '<a class="pag" href="?page=' . ($current_page - 1) . '"><img src="http://144.76.127.94/view/image/art/ico-arr_left.png" width="21" height="15" alt="<<"></a>';
if ($current_page != $count_pages)
    $next_page = '<a class="pag" href="?page=' . ($current_page + 1) . '"><img src="http://144.76.127.94/view/image/art/ico-arr_right.png" width="21" height="15" alt=">>"></a>';

for ($i = 1; $i <= $count_pages; $i++) {

    if ($i === $current_page) {
        $pages .= '<span class="pag">' . $current_page . '</span>';
    } else {
        if(($i - 1) < 1) {
            $pages .= '<a class="pag" href="?page=' . ($current_page - 1) . '">' . ($current_page - 1) . '</a>';
        }
        if($i >= $count_pages) {
            $pages .= '<a class="pag" href="?page=' . ($current_page + 1) . '">' . ($current_page + 1) . '</a>';
        }
    }
}

// Вывод меню
echo '<div class="pgn">'.$perv_page . $pages . $next_page.'</div>';
