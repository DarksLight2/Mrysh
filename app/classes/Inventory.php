<?php

namespace app\classes;

class Inventory
{
    private function __construct()
    {

    }

    public static function GetAmountItems($UserID = null): int
    {
        if($UserID === null)
        {
            $UserID = User::userData()['id'];
        }

        return DataBase::query('SELECT * FROM `inventory` WHERE `userID` = ?', [$UserID])->num_rows;

    }

    public static function GetItems($UserID = null)
    {
        $Return = [];

        if($UserID === null)
        {
            $UserID = User::userData()['id'];
        }

        $UserInventory = DataBase::query('SELECT * FROM `inventory` WHERE `userID` = ?', [$UserID]);

        if($UserInventory->num_rows > 0)
        {
            while ($row = $UserInventory->fetch_assoc())
            {
                $Return[] = $row;
            }

            return $Return;
        }

        return false;

    }

    public static function AddItem($ItemID ,$UserID = null)
    {

    }

    public static function DeleteItem($ItemID ,$UserID = null)
    {

    }
}