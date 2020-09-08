<?php


namespace app\classes;


class Arena
{

    private function __construct()
    {
    }

    public static function attack()
    {
        if(User::userData()['arena_fights'] > 0)
        {
            if(User::userData()['health'] >= (User::userData()['max_health'] * 0.10))
            {
                $Enemy = $_SESSION['arena']['Enemy'];

                $DamageUser = Fights::getDamage($Enemy['defence']);
                $DamageEnemy = Fights::getDamage(User::userData()['defence'], $Enemy['strength']);

                if($DamageEnemy > $DamageUser)
                {
                    $_SESSION['arena']['status'] = 'lose';

                    $Rewards = Fights::getRewards(2, $DamageUser, User::userData()['level'], true, false, true, false);
                }
                else
                {
                    $_SESSION['arena']['status'] = 'win';

                    $Rewards = Fights::getRewards(1, $DamageUser, User::userData()['level'], true, false, true, false);
                }

                $_SESSION['arena']['Rewards'] = $Rewards;

                DataBase::query('UPDATE `users` SET `silver` = `silver` + ?, `exp` = `exp` + ?, `arena_fights` = `arena_fights` - 1 WHERE `id` = ?', [$Rewards['Silver'], $Rewards['Exp'], User::userData()['id']]);

                if(User::userData()['arena_fights'] <= 1)
                {
                    DataBase::query('UPDATE `users` SET `arena_cooldown` = ? WHERE `id` = ?', [time() + (3600 * 2), User::userData()['id']]);
                }

                return true;
            }
            else
            {
                return 'lack_of_health';
            }
        }
        else
        {
            return 'not_enough_attempts';
        }
    }

}