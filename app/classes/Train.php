<?php

namespace app\classes;

class Train
{
    private function __construct() {}

    public static function CreateTrain(): void
    {
        $TrainData = DataBase::query('SELECT * FROM `train` WHERE `userID` = ?', [User::userData()['id']])->fetch_assoc();

        if( ! $TrainData)
        {
            DataBase::query('INSERT INTO `train` (`userID`) VALUES (?)', [User::userData()['id']]);
        }
    }

    public static function GetLevel($Param)
    {
        $Data = DataBase::query('SELECT * FROM `train` WHERE `userID` = ?', [User::userData()['id']])->fetch_assoc();

        return $Data[$Param.'_level'];
    }

    public static function GetPrice($Level): array
    {
        $Price = 50;
        $Type = 'silver';

        if($Level > 0)
        {
            $Price *= 2;
        }

        $Step = range(0, 200, 5);

        if(in_array(($Level + 1), $Step))
        {
            $Type = 'gold';
        }

        return [$Price, $Type];

    }

    public static function Training($Param, $Level)
    {
        $Price = self::GetPrice($Level);

        if(User::userData()[$Price[1]] >= $Price[0])
        {
            DataBase::query('UPDATE `users` SET `'.$Param.'` = ?, `'.$Price[1].'` = ? WHERE `id` = ?', [User::userData()[$Param] + 3, User::userData()[$Price[1]] - $Price[0], User::userData()['id']]);
            DataBase::query('UPDATE `train` SET `'.$Param.'_level` = ? WHERE `userID` = ? LIMIT 1', [$Level + 1, User::userData()['id']]);

            $_SESSION['train'][$Param] = true;

            return true;
        }
        else
        {
            return false;
        }

    }
}