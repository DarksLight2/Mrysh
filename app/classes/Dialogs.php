<?php

namespace app\classes;

class Dialogs
{
    public static function create($user_id, $recipient)
    {
        $ActiveRecords = new ActiveRecords('dialogs');
        $ActiveRecords->insert(['userID' => $user_id, 'companionID' => $recipient]);

        return DataBase::lastInsertID();
    }

    public static function find($params = [])
    {

        $ActiveRecords = new ActiveRecords('dialogs');

        $result = $ActiveRecords->select()->where($params)->execute();

        if($result->count_rows > 0)
            return $result->data[0]->id;
        else
            return null;
    }
}