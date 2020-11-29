<?php

require_once './app/configs/systemSettigns.php';

use app\classes\SiteSettigns;
use app\classes\User;
use app\classes\Messages;
use \app\classes\ActiveRecords;

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

$Messages = new Messages;

if( ! isset($_GET['player_id']) || ! is_numeric($_GET['player_id']) && ! isset($_GET['dialog_id']) || ! is_numeric($_GET['dialog_id'])) {
    header('Location: /view_posters');
}

$Dialog = new ActiveRecords('dialogs');

if($Dialog->select()->where([
                'id'     => $_GET['dialog_id'],
                'userID' => User::userData()['id']])
        ->orWhere(['companionID' => User::userData()['id']])
        ->execute('num_rows') === 0) {
    header('Location: /view_posters');
}

$_SESSION['recipient'] = $_GET['player_id'];
$_SESSION['dialogID'] = $_GET['dialog_id'];

require_once './header.php';
?>

<span id="response"></span>

<div id="mail_unread" class="mail_unread"></div>

<div class="bdr bg_blue">
    <div class="wr1">
        <div class="wr2">
            <div class="wr3">
                <div class="wr4">
                    <div class="wr5">
                        <div class="wr6">
                            <div class="wr7">
                                <div class="wr8">

                                    <div class="ml10 mt5 mb5 mr10 sh cntr">
                                        <form id="send_message_form" method="POST">

                                            <textarea id="sml" rows="5" class="lbfld ha w96 mt5" name="message_text"></textarea>
                                            <input id="recipient" type="hidden" value="<?=$_GET['player_id']?>">
                                            <input id="dialog_id" type="hidden" value="<?=$_GET['dialog_id']?>">
                                            <input class="fl ml5 ibtn plr10 mt10 mb5" id="submit" type="submit" value="Отправить">
                                            <div class="fr mt10 ml10 mr5">
                                                <a onclick="smiles();" class="nd" href="">
                                                    <img class="icon" height="28" src="http://144.76.127.94/view/image/icons/big_smile.png" alt=":)">
                                                </a>
                                            </div>

                                            <a id="use_choose_gifts" class="fr mr10 mt10" href="/choose_gifts?player_id=3317894&amp;PHPSESSID=beeaad0d47a3ddfc98c10a3ed21cd1c4.1605716591.5686237"><img class="icon" height="31" src="http://144.76.127.94/view/image/icons/biggift.png"></a>

                                            <div class="clb mb5"></div>

                                            <span class="small">Стоимость сообщения <img class="icon" src="http://144.76.127.94/view/image/icons/silver.png">1000</span>
                                        </form>

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

<div id="post_message_box"></div>

<div id="box_forum_chat_pgn" class="pgn nwr">

</div>

<script>
    $(function() {
        getMessages(<?=$_GET['dialog_id']?>, <?=$_GET['player_id']?>);
        $("#submit").click(
            function(){
                sendMessage();
                return false;
            }
        );
    });

    setInterval(function (){
        getMessages(<?=$_GET['dialog_id']?>, <?=$_GET['player_id']?>);
    }, 5000)
</script>
<?php

require_once './footer.php';