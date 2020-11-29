<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/app/configs/systemSettigns.php';

use app\classes\ActiveRecords;
use app\classes\POST;
use app\classes\User;

if(POST::check_isset('action') === true && POST::get('action')->data === 'check') {
    $Messages = new ActiveRecords('messages');
    $messages = $Messages->select('`read`, `recipient`')->where(['recipient' => User::userData()['id'], 'read' => 0])->execute();

    if($messages->count_rows > 0) {
        exit(json_encode(['action' => 'show']));
    } else {
        exit(json_encode(['action' => 'none']));
    }
}