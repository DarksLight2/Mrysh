<?php


namespace app\classes;


class Fights
{
    private function __construct()
    {

    }

    public static function getRewards($Status, $Damage, $UserLevel, $Silver = false, $Gold = false, $Exp = false, $Item = false)
    {
        if($Silver === true)
        {
            $Silver = round((($Damage * $UserLevel) / 2) / $Status);
        }
        else
        {
            $Silver = 0;
        }

        if($Gold === true)
        {
            $Gold = round((($Damage * 0.10) / 20) / $Status);

            if($Gold < 0)
            {
                $Gold = 0;
            }
        }
        else
        {
            $Gold = 0;
        }

        if($Exp === true)
        {
            $Exp = round(($Damage * $UserLevel) / $Status);
        }
        else
        {
            $Exp = 0;
        }

        return ['Silver' => $Silver, 'Gold' => $Gold, 'Exp' => $Exp];
    }

    public static function getDamage($DefenceEnemy, $Strength = null, $Buffs = null)
    {

        if($Strength === null)
        {
            $Strength = User::userData()['strength'];
        }

        $Damage = $Strength / 1.25 + rand(0, 40);

        $DefenceEnemy = $DefenceEnemy * rand(0.20, 0.30);

        return $Damage - $DefenceEnemy;
    }

    public static function getEnemy($Place, $UserParams = [])
    {

        if(empty($UserParams))
        {
            $UserParams = [
                'strength' => User::userData()['strength'],
                'defence' => User::userData()['defence'],
                'health' => User::userData()['health'],
            ];
        }

        $Enemy = DataBase::query('SELECT * FROM `users` WHERE `strength` + `defence` + `health` >= ? OR `strength` + `defence` + `health` <= ? AND `id` != ? ORDER BY RAND() LIMIT 1',
            [
                ($UserParams['strength'] + $UserParams['defence'] + $UserParams['health'] * 2),
                ($UserParams['strength'] + $UserParams['defence'] + $UserParams['health'] * 2),
                User::userData()['id']
            ]);

        if($Enemy->num_rows === 1)
        {
            $_SESSION[$Place]['Enemy'] = $Enemy->fetch_assoc();

            return true;

        }
        else
        {
            return false;
        }
    }

    public static function progressHealth($MaxHealth, $Health)
    {
        if($Health > $MaxHealth)
        {
            $Health = $MaxHealth;
        }

        return floor( 100 / ($MaxHealth / $Health));
    }
}