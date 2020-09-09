<?php

use app\classes\DataBase;
use app\classes\User;
use app\classes\Lair;

if(User::userData() !== false)
{
	DataBase::query('UPDATE `users` SET `last_update` = ? WHERE `id` = ? LIMIT 1', [time(), User::userData()['id']]);
	
	User::activity();
	
	# Конец кулдауна Арены
	if(User::userData()['arena_cooldown'] < time() && User::userData()['arena_fights'] == 0)
	{
		DataBase::query('UPDATE `users` SET `arena_fights` = 15 WHERE `id` = ? LIMIT 1', [User::userData()['id']]);
	}

    # Конец кулдауна Lair
    if(Lair::GetData()['cooldown'] < time() && Lair::GetData()['fights'] == 0)
    {
        DataBase::query('UPDATE `lair_users` SET `fights` = 2 WHERE `userID` = ? LIMIT 1', [User::userData()['id']]);
    }
	
	/*
	if(User::userData()['health'] != User::userData()['max_health'])
	{
		#Добавление здоровья одна еденица в две секунды
		if(User::userData()['health'] < User::userData()['max_health'] && $_SERVER['PHP_SELF'] != '/arena.php')
		{
			$CountHealth = User::userData()['health'] + floor((time() - User::userData()['last_update']) / 2);
			
			if($CountHealth > User::userData()['max_health'])
			{
				$CountHealth = User::userData()['max_health'];
			}
		}
		elseif(User::userData()['health'] > User::userData()['max_health'])
		{
			$CountHealth = User::userData()['max_health'];
		}
		
		DataBase::query('UPDATE `users` SET `health` = ? WHERE `id` = ? LIMIT 1', [$CountHealth, User::userData()['id']]);
	}
	*/
}
