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

    public static function GetItems($UserID = null, $Equip = null)
    {
        $Return = [];

        if($UserID === null)
        {
            $UserID = User::userData()['id'];
        }

        if($Equip === null)
        {
            $UserInventory = DataBase::query('SELECT * FROM `inventory` WHERE `userID` = ?', [$UserID]);
        }
        else
        {
            $UserInventory = DataBase::query('SELECT * FROM `inventory` WHERE `userID` = ? AND `equip` = ?', [$UserID, $Equip]);
        }


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

    public static function Comparison($NewItem, $EquipItem = null, $Return = null)
    {

        $NewItemData = DataBase::query('SELECT * FROM `inventory` WHERE `id` = ? LIMIT 1', [$NewItem])->fetch_assoc();

        $ParamsNewItem = $NewItemData['strength'] + $NewItemData['health'] + $NewItemData['defence'];

        if($EquipItem !== null)
        {

            $EquipItemData = DataBase::query('SELECT * FROM `inventory` WHERE `id` = ? LIMIT 1', [$EquipItem])->fetch_assoc();
            $ParamsEquipItem = $EquipItemData['strength'] + $EquipItemData['health'] + $EquipItemData['defence'];



            if ($ParamsEquipItem < $ParamsNewItem)
            {
                if($Return === null)
                {
                    return '<span class="win">+' . ($ParamsNewItem - $ParamsEquipItem) . '</span>';
                }

                return 'best';
            }
            elseif ($ParamsEquipItem == $ParamsNewItem)
            {
                return '';
            }
            elseif ($ParamsEquipItem > $ParamsNewItem)
            {
                if($Return === null)
                {
                    return '<span class="lose">-' . ($ParamsEquipItem - $ParamsNewItem) . '</span>';
                }

                return 'no_best';
            }
        }
        else
        {
            return '<span class="win">+' . $ParamsNewItem . '</span>';
        }
    }

    public static function AddItem($ItemID ,$UserID = null)
    {

    }

    public static function DeleteItem($ItemID ,$UserID = null)
    {

    }

    public static function UnEquip(int $ItemID): bool
    {
        $EquipItems = self::GetItems(null, 1);

        foreach ($EquipItems as $Key => $Value)
        {
            if($Value['id'] == $ItemID)
            {
                $Params = $Value['strength'] + $Value['health'] + $Value['defence'];

                DataBase::query('UPDATE `inventory` SET `equip` = 0 WHERE `id` = ?', [$ItemID]);
                DataBase::query('UPDATE `users` SET `strength` = `strength` - ?, `defence` = `defence` - ?, `health` = `health` - ? WHERE `id` = ?', [$Params, $Params, $Params, User::userData()['id']]);

                return true;
            }
        }

        return false;
    }

    public static function Equip(int $id): bool
    {
        if(is_numeric($id))
        {
            $ItemDataToEquip = DataBase::query('SELECT * FROM `inventory` WHERE `id` = ? LIMIT 1', [$id])->fetch_assoc();

            $SelectEquipItems = self::GetItems(null, 1);

            if($SelectEquipItems != false)
            {
                foreach ($SelectEquipItems as $Key => $Value)
                {
                    if($Value['type'] == $ItemDataToEquip['type'])
                    {
                        self::UnEquip($Value['id']);
                    }
                }
            }

            $Params = $ItemDataToEquip['strength'] + $ItemDataToEquip['health'] + $ItemDataToEquip['defence'];

            DataBase::query('UPDATE `inventory` SET `equip` = 1 WHERE `id` = ?', [$id]);
            DataBase::query('UPDATE `users` SET `strength` = `strength` + ?, `defence` = `defence` + ?, `health` = `health` + ? WHERE `id` = ?', [$Params, $Params, $Params, User::userData()['id']]);


            return true;
        }

        return false;
    }
}