<?php

session_start();

require_once 'autoLoadClasses.php';

if( ! isset($_COOKIE['template']))
{
    $template = 'default';
}

# GAME SETTINGS

define('COMPANY_NAME', 'DarksLight2'); // Название компании
define('GAME_NAME', 'Разрушители'); // Название игры
define('META_TAGS', 'game, mrush, разрушители'); // Мета тэги
define('AGE_TO_GAME', '16'); // Возраст для игры

require_once $_SERVER['DOCUMENT_ROOT'].'/app/configs/userSettigns.php';


