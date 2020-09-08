<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/app/configs/systemSettigns.php';

use app\classes\User;

if(User::userData() !== false)
{
?>
    Раздел сайта доступен для НЕ авторизированых игроков.

    <script>
        setTimeout(function (){
            history.back();
        }, 3000)
    </script>
<?php

    exit();

}

if(isset($_POST['name']) && isset($_POST['password']))
{

    $AuthResult = User::auth($_POST['name'], $_POST['password']);

    if($AuthResult === 'successful_authorization')
    {
        header('Location: /');
    }
    else
    {
        echo $AuthResult;
        ?>
        <script>
            setTimeout(function (){
                history.back();
            }, 2000)
        </script>
        <?php
    }

}
else
{
    ?>
    <script>
        history.back();
    </script>
<?php
}