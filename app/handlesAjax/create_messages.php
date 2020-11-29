<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/app/configs/systemSettigns.php';

use app\classes\POST;
use app\classes\ActiveRecords;
use app\classes\User;

if(POST::check_isset('action') && POST::check_isset('message')) {
    $action = POST::get('action')->data;
    $message = POST::get('message');

    if(strlen(strip_tags($message->data)) < 2) {
        exit(json_encode(['status' => 'error', 'message' => 'Сообщение не может содержать менее 2-х символов']));
    } else {

        $ActiveRecords = new ActiveRecords($action);

        $params = [];

        if(is_numeric(POST::get('to_user')->data) && POST::get('to_user')->data >= 0)
            $params['to_user'] = POST::get('to_user')->data;
        else
            $params['to_user'] = 0;

        if($action === 'chat') {
            $params['user_id'] = User::userData()['id'];
            $params['message'] = strip_tags($message->data);
        } elseif ($action === 'chat_clan') {
            $params['user_id'] = User::userData()['id'];
            $params['message'] = strip_tags($message->data);
            $params['clan_id'] = User::userData()['clan'];
        }

        $ActiveRecords->insert($params);

        exit(json_encode(['status' => 'success', 'message' => 'Успешно отправлено сообщение']));
    }
}