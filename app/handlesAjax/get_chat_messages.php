<?php

use app\classes\POST;
use app\classes\ActiveRecords;
use app\classes\User;

require_once $_SERVER['DOCUMENT_ROOT'].'/app/configs/systemSettigns.php';

if(POST::check_isset('chat_data') === true) {

    $ActiveRecords = new ActiveRecords('chat');
    $chat_data = POST::get('chat_data')->data;
    $return = [];

    if($chat_data->type !== 'chat' && $chat_data->type !== 'clan') {
        exit(json_encode(['status' => 'error', 'message' => 'Неверный тип чата']));
    } elseif ($chat_data->type === 'chat') {

        $data = $ActiveRecords->select()->where(['chat_type' => 'chat'])->orderBy('`id` DESC')->execute();

        foreach ($data as $field => $value) {

            if(gettype($value) === 'object' || gettype($value) === 'array') {
                foreach ($value as $object) {

                    $sender_data = User::getUserDataByID($object->user_id);

                    if(($sender_data['activity'] - time()) <= 180)
                        $object->sender_activity = 'on';
                    else
                        $object->sender_activity = 'off';

                    $object->sender_login = $sender_data['login'];
                    $object->sender_gender = $sender_data['gender'];
                }
            }

            $return[$field] = $value;
        }

    } elseif ($chat_data->type === 'clan') {
        exit('clan');
    }

    exit(json_encode($return));
}