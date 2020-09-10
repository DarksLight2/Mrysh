<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/app/configs/systemSettigns.php';

use app\classes\DataBase;
use app\classes\User;

if(User::userData() === false || User::userData()['email'] !== null)
{
    ?>
    Раздел сайта не доступен для вас.

    <script>
        setTimeout(function (){
            history.back();
        }, 3000)
    </script>
    <?php

    exit();

}

if( ! empty($_POST['name']) && ! empty($_POST['password']) && ! empty($_POST['email']) && is_numeric($_POST['gender']))
{
    $Password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    DataBase::query('UPDATE `users` SET `login` = ?, `password` = ?, `email` = ?, `gender` = ?, `gold` = `gold` + 5 WHERE `id` = ?', [$_POST['name'], $Password, $_POST['email'], $_POST['gender'], User::userData()['id']]);

    header('Location: /');
}
else
{
    exit('Do widzenia!');
}