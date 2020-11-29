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

if(isset($_GET['clan']) && $_GET['clan'] === 1) {
    $title = 'Чат клана';
} else {
    $title = 'Чат';
}

require_once './header.php';
?>

<div class="bdr bg_blue">
    <div class="wr1">
        <div class="wr2">
            <div class="wr3">
                <div class="wr4">
                    <div class="wr5">
                        <div class="wr6">
                            <div class="wr7">
                                <div class="wr8">

                                    <form action="" class="mb10" method="POST">

                                        <div class="mt5 mlr10 lwhite">

                                            <textarea id="sml" rows="2" class="lbfld ha w96 mt5" name="message_text"></textarea>

                                            <div class="mt5 mr5 fr"><a onclick="smiles();return false;" class="nd" href="?PHPSESSID=beeaad0d47a3ddfc98c10a3ed21cd1c4.1605716591.5686237">
                                                    <img class="icon" height="28" src="http://144.76.127.94/view/image/icons/big_smile.png" alt=":)">
                                                </a></div>

                                            <div class="mt5 mr10 fl">
                                                <input id="submit_message" type="submit" class="ibtn w90px" onclick="return false;" value="Отправить">
                                            </div>

                                            <div class="mt10 small"><a class="grey1 ml10" href="#" onclick="get_chat_messages('chat', 15);return false;">Обновить</a></div>

                                            <input type="hidden" id="action" name="action" value="chat">
                                            <input type="hidden" id="to_user" value="0">

                                        </div>
                                        <div class="mb10"></div>
                                        <div class="hr_arr mlr10 mb5"><div class="alf"><div class="art"><div class="acn"></div></div></div></div>
                                    </form>

                                    <div class="mlr10 lwhite" id="messages_box"></div>

                                    <div class="hr_arr mt2 mb2 mlr10"><div class="alf"><div class="art"><div class="acn"></div></div></div></div>

                                    <div id="navigation_box" class="pgn nwr"></div>

                                    <div class="hr_arr mt2 mb2 mlr10"><div class="alf"><div class="art"><div class="acn"></div></div></div></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        get_chat_messages('chat', 15);

        setInterval(function (){
            get_chat_messages('chat', 15);
        }, 1000);

        add_new_message('chat');
    });
</script>
<?php

require_once './footer.php';
