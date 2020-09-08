<?php


namespace app\classes;

class Inventory
{
    private function __construct()
    {

    }

    public static function getItemsInInventory($UserID = null)
    {
        if($UserID === null)
        {
            $UserID = User::userData()['id'];
        }

        $UserInventory = DataBase::query('SELECT * FROM `inventory` WHERE `userID` = ?', [$UserID]);

        if($UserInventory->num_rows > 0)
        {
            while ($row = $UserInventory->fetch_assoc())
            {
                $_SESSION['Inventory'][$row['id']] = $row;
            }

            return true;
        }

        return 'not exist items.';

    }

    public static function addItemInInventory($ItemID ,$UserID = null)
    {

    }

    public static function deleteItemInInventory($ItemID ,$UserID = null)
    {

    }
}