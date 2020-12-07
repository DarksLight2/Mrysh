<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/app/configs/systemSettigns.php';

use app\classes\Dialogs;
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

        if(POST::get('action')->data === 'chat') {
            if (is_numeric(POST::get('to_user')->data) && POST::get('to_user')->data >= 0)
                $params['to_user'] = POST::get('to_user')->data;
            else
                $params['to_user'] = 0;

            if ($action === 'chat') {
                $params['user_id'] = User::userData()['id'];
                $params['message'] = strip_tags($message->data);
            } elseif ($action === 'chat_clan') {
                $params['user_id'] = User::userData()['id'];
                $params['message'] = strip_tags($message->data);
                $params['clan_id'] = User::userData()['clan'];
            }
        } elseif (POST::get('action')->data === 'messages') {

            if(POST::check_isset('dialog_id')) {
                $dialog_id = POST::get('dialog_id')->data;
            } else {
                $find_dialog = Dialogs::find(['userID' => User::userData()['id'], 'companionID' => POST::get('to_user')->data]);
                if($find_dialog === null) {
                    $find_dialog = Dialogs::find(['userID' => POST::get('to_user')->data, 'companionID' => User::userData()['id']]);
                    if($find_dialog === null) {
                        $dialog_id = Dialogs::create(User::userData()['id'], POST::get('to_user')->data);
                    }
                    else
                        $dialog_id = $find_dialog;
                }
                else
                    $dialog_id = $find_dialog;
            }

            $ActiveRecords->update(['read' => 1])->where(['dialogID' => $dialog_id, 'recipient' => User::userData()['id']])->execute();

            $params['message']   = $message_text = strip_tags($message->data);
            $params['recipient'] = POST::get('to_user')->data;
            $params['sender']    = User::userData()['id'];
            $params['time']      = time();
            $params['date']      = date('H:i:s d.m.Y');
            $params['dialogID']  = $dialog_id;
        }

        $ActiveRecords->insert($params);

        exit(json_encode(['status' => 'success', 'message' => 'Успешно отправлено сообщение', 'type' => $action]));
    }
}