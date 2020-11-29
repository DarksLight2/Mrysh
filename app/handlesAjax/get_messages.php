<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/app/configs/systemSettigns.php';

use app\classes\User;
use app\classes\ActiveRecords;

if(isset($_POST['action']) && $_POST['action'] === 'getMessages') {
    if(is_numeric($_POST['dialogID'])) {
        $Messages = new ActiveRecords('messages');

        $messages = $Messages
            ->select()
            ->where(['dialogID' => $_POST['dialogID'],
                'sender'   => $_POST['player_id']])
            ->orWhere(['recipient' => $_POST['player_id']])
            ->orderBy('`id` DESC')
            ->execute();

        $return = [];

        foreach ($messages->data as $message) {

            if ($messages->count_rows > 0) {
                if ($message->sender != User::userData()['id']) {
                    $ActiveRecords_2 = new ActiveRecords('messages');
                    $ActiveRecords_2->update(['read' => 1])->where(['id' => $message->id])->execute(false);
                }

                $sender = (object)User::getUserDataByID($message->sender);

                if((time() - $sender->activity) >= 180) {
                    $status = 'off';
                } else {
                    $status = 'on';
                }

                $unread = 0;

                if($message->sender === User::userData()['id']) {
                    $style = 'bg_green';
                } else {
                    $style = 'bg_blue';

                    if($message->read === 0) {
                        $unread = 1;
                    }
                }



                $message->senderLogin = $sender->login;
                $message->senderStatus = $status;
                $message->senderGender = $sender->gender;
                $message->style = $style;
                $message->unread = $unread;

                $return[] = $message;
            }
        }

        exit(json_encode($return));

    }

    exit(json_encode(['status' => 'error', 'message' => 'Неверный идентификатор диалога.']));
}
