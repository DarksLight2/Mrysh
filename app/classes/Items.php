<?php

namespace app\classes;

class Items
{
    private function __construct() {}

    private static $ItemsData = null;

    public static function GetItemsData($Items = [])
    {
        if( ! empty($Items))
        {

            foreach ($Items as $Key => $Value)
            {
                self::$ItemsData[] = DataBase::query('SELECT * FROM `items` WHERE `id` = ? LIMIT 1', [$Value['id']])->fetch_assoc();
            }

            return self::$ItemsData;

        }

        return false;
    }
}