<?php


namespace app\classes;


class Lair
{
    private static $LairData = null;

    private function __construct()
    {
    }

    public static function GetMob($MobID)
    {
        return DataBase::query('SELECT * FROM `lair_mobs` WHERE `id` = ?', [$MobID])->fetch_assoc();
    }

    public static function NamesMobs($MobName, $Type)
    {
        $Return = '';

        if($Type === 0)
        {
            switch ($MobName)
            {
                case 'Лесной волк':
                    $Return = 'Лесного Волка';
                    break;
            }
        }
        elseif ($Type === 1)
        {
            switch ($MobName)
            {
                case 'Лесной волк':
                    $Return = 'Лесном волке';
                    break;
            }
        }

        return $Return;
    }

    public static function GetData($UserID = null)
    {
        if(self::$LairData === null)
        {
            if($UserID === null)
            {
                $Lair = DataBase::query('SELECT * FROM `lair_users` WHERE `userID` = ?', [User::userData()['id']]);

                if($Lair -> num_rows == 1)
                {
                    self::$LairData = $Lair->fetch_assoc();

                    return self::$LairData;
                }
                else
                {

                    $MobData = self::GetMob(1);

                    DataBase::query('INSERT INTO `lair_users` (`lair_mob`, `userID`, `health_mob`, `start`, `fights`, `cooldown`) VALUES (?, ?, ?, ?, ?, ?)', [1, User::userData()['id'], $MobData['health'], 0, 2, 0]);

                    $Lair = DataBase::query('SELECT * FROM `lair_users` WHERE `userID` = ?', [User::userData()['id']]);

                    self::$LairData = $Lair->fetch_assoc();

                    return self::$LairData;
                }
            }
        }
        else
        {
            return self::$LairData;
        }
    }

    public static function EnterInFight()
    {
        if(self::GetData()['start'] == 0)
        {
            DataBase::query('UPDATE `lair_users` SET `start` = 1 WHERE `userID` = ?', [User::userData()['id']]);

            return true;
        }

        return false;
    }

    public static function attack($MobData = [], $LairData = [])
    {

        $UserData = User::getUserDataByID();
        $UserDamage = Fights::getDamage($MobData['defence']);
        $MobDamage = Fights::getDamage(User::userData()['defence']);

        DataBase::query('UPDATE `users` SET `health` = `health` - ? WHERE `id` = ?', [$MobDamage, User::userData()['id']]);
        DataBase::query('UPDATE `lair_users` SET `health_mob` = `health_mob` - ? WHERE `userID` = ?', [$UserDamage, User::userData()['id']]);

        if($LairData['health_mob'] <= 0)
        {
            $_SESSION['lair'] = 'win';

            return true;
        }

        if($UserData['health'] <= 0)
        {
            $_SESSION['lair'] = 'lose';

            return true;
        }


        $_SESSION['lair']['MobDamage'] = $MobDamage;
        $_SESSION['lair']['UserDamage'] = $UserDamage;
    }
}