<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/app/configs/systemSettigns.php';

use app\classes\User;
use app\classes\ActiveRecords;
use app\classes\POST;

if(POST::check_isset('action') === true && POST::compare_value('post_message', 'action') === true) {

    $Data = POST::get('data')->data;

    if(POST::check_isset('data') === false) {
        exit(json_encode(['status' =>'error',
            'message' => 'Ошибка обработки данных.']));
    }

    $message_text = strip_tags($Data->message);

    if(strlen($message_text) < 2) {
        exit(json_encode(['status' =>'error',
            'message' => 'Сообщение не может содержать меньше 2х символов.']));
    }

    if($_SESSION['dialogID'] == $Data->dialog_id && $_SESSION['recipient'] == $Data->recipient) {
        $ActiveRecords = new ActiveRecords('messages');
        $ActiveRecords->insert([
            'sender' => User::userData()['id'],
            'recipient' => $_SESSION['recipient'],
            'message' => $message_text,
            'time' => time(),
            'date' => date('H:i:s d.m.Y'),
            'dialogID' => $_SESSION['dialogID']
        ]);

        exit(json_encode(['status' => 'success', 'recipient' => $_SESSION['recipient'], 'dialog_id' => $_SESSION['dialogID']]));

    } else {
        exit(json_encode(['status' =>'error',
            'message' => 'Ошибка обработки данных.']));
    }
}