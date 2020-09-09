<?php


namespace app\classes;


class Lair
{
    private static $LairData = null;

    private function __construct()
    {
    }

    public static function GetMob($MobID = null)
    {
        if($MobID === null)
        {
            $MobID = self::GetData()['lair_mob'];
        }

        $Query = DataBase::query('SELECT * FROM `lair_mobs` WHERE `id` = ?', [$MobID]);

        if($Query->num_rows === 1)
        {
            return $Query->fetch_assoc();
        }

        return false;
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

                    DataBase::query('INSERT INTO `lair_users` (`lair_mob`, `userID`, `health_mob`, `health_user`, `start`, `fights`, `cooldown`) VALUES (?, ?, ?, ?, ?, ?, ?)', [1, User::userData()['id'], $MobData['health'], User::userData()['health'], 0, 2, 0]);

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
            DataBase::query('UPDATE `lair_users` SET `start` = 1, `health_mob` = ?, `health_user` = ?, `damage_user` = 0 WHERE `userID` = ?', [self::GetMob(self::GetData()['lair_mob'])['health'], User::userData()['health'], User::userData()['id']]);

            return true;
        }

        return false;
    }

    public static function attack($MobData = [], $LairData = [])
    {

        $UserData = User::getUserDataByID();
        $UserDamage = Fights::getDamage($MobData['defence']);
        $MobDamage = Fights::getDamage(User::userData()['defence']);

        DataBase::query('UPDATE `lair_users` SET `health_mob` = `health_mob` - ?, `health_user` = `health_user` - ?, `damage_user` = `damage_user` + ? WHERE `userID` = ?', [$UserDamage, $MobDamage, $UserDamage, User::userData()['id']]);

        if(($LairData['health_mob'] - $UserDamage) <= 0)
        {
            $_SESSION['lair']['end'] = 'win';

            return true;
        }
        elseif(($LairData['health_user'] - $MobDamage) <= 0)
        {
            $_SESSION['lair']['end'] = 'lose';

            return true;
        }


        $_SESSION['lair']['MobDamage'] = $MobDamage;
        $_SESSION['lair']['UserDamage'] = $UserDamage;
    }

    public static function EndBattle($Status)
    {
        $Cooldown = time() + (3600 * 2);

        if($Status == 'win')
        {
            $StatusNum = 1;
            $Gold = self::GetMob(self::GetData()['lair_mob'])['gold'];

            $LairMob = 1;

            if(self::GetMob(self::GetData()['lair_mob'] + 1) === false)
            {
                $LairMob = 0;
            }

            DataBase::query('UPDATE `lair_users` SET `fights` = `fights` - 1, `start` = 0, `lair_mob` = `lair_mob` + ?, `cooldown` = ? WHERE `id` = ?', [$LairMob ,$Cooldown, self::GetData()['id']]);
        }
        else
        {
            $StatusNum = 2;
            $Gold = 2;

            DataBase::query('UPDATE `lair_users` SET `fights` = `fights` - 1, `health_user` = ?, `health_mob` = ? , `cooldown` = ? WHERE `id` = ?', [User::userData()['health'], self::GetMob(self::GetData()['lair_mob'])['health'], $Cooldown, self::GetData()['id']]);
        }

        $Rewards = Fights::getRewards($StatusNum, self::GetData()['damage_user'], User::userData()['level'], false, $Gold, true);

        DataBase::query('UPDATE `users` SET `gold` = `gold` + ?, `exp` = `exp` + ? WHERE `id` = ?', [$Rewards['Gold'], $Rewards['Exp'], User::userData()['id']]);

        return $Rewards;

    }
}