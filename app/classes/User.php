<?php

namespace app\classes;

class User
{
    public static $userData = null;
    public static $cookieName = null;
    private static $hash = null;

    private function __construct()
    {

    }

    public static function createUser($password = null, $login = null)
    {
        if($login === null)
        {
            $login = 'Новобранец';
        }

        if($password === null)
        {
            $password = rand(000000, 999999);
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        DataBase::query('INSERT INTO `users` (`login`, `password`, `token`, `token_time`) VALUES (?, ?, ?, ?)', [$login, $passwordHash, '', 0]);

        $userID = DataBase::lastInsertID();

        if(self::generateToken($userID))
        {
            return 'successful_registration';
        }
        else
        {
            return 'error_in_generate_token';
        }
    }

    public static function auth($login, $password)
    {
        if(empty($_COOKIE[self::getCookieName()]))
        {
            $user = DataBase::query('SELECT `password`, `id` FROM `users` WHERE `login` = ? LIMIT 1', [$login]);

            if($user->num_rows == 1)
            {
                $user = $user->fetch_assoc();

                if(password_verify($password, $user['password']))
                {
                    self::generateToken($user['id']);

                    return 'successful_authorization';
                }
                else
                {
                    return 'invalid_password';
                }
            }
            else
            {
                return 'no_user_found';
            }
        }
        else
        {
            return 'the_user_is_authorized';
        }
    }

    public static function userData()
    {
        if(self::$userData === null)
        {
            if(isset($_COOKIE[self::getCookieName()]))
            {
                $query = DataBase::query('SELECT * FROM `users` WHERE `token` = ? LIMIT 1', [$_COOKIE[self::getCookieName()]]);

                if($query->num_rows == 1)
                {
                    self::$userData = $query->fetch_assoc();

                    if(self::$userData['token_time'] < (time() + 3600))
                    {
                        self::generateToken(self::$userData['id']);
                    }
                }
            }
            else
            {
                return false;
            }
        }

        return self::$userData;
    }

    public static function getCookieName()
    {
        if(self::$cookieName === null)
        {
            self::$cookieName = sha1(md5(sha1($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'])));
        }

        return self::$cookieName;
    }

    private static function getUserHash()
    {
        if(self::$hash === null)
        {
            $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
            $size = strlen($chars) - 1;
            $max = 20;
            $token = '';
            while ($max--) {
                $token .= $chars[rand(0, $size)];
            }

            self::$hash = sha1(md5('Creator.Maksym.Kovalets.----HASH----.'.$token));
        }

        return self::$hash;
    }

    public static function generateToken($userID)
    {
        $query = DataBase::query('SELECT * FROM `users` WHERE `id` = ? LIMIT 1', [$userID]);

        if($query->num_rows == 1)
        {
            $user = $query->fetch_assoc();

            $timeToken = time() + (86400 * 7);

            if(isset($_COOKIE[self::getCookieName()]))
            {
                $_COOKIE[self::getCookieName()] = self::getUserHash();
            }
            else
            {
                setcookie(self::getCookieName(), self::getUserHash(), $timeToken, '/');
            }

            DataBase::query('UPDATE `users` SET `token` = ?, `token_time` = ? WHERE `id` = ? LIMIT 1', [self::getUserHash(), $timeToken, $user['id']]);

            return true;
        }
        else
        {
            return false;
        }
    }

    public static function getUserDataByID($id = null, $selectElement = null)
    {
        if($id === null)
        {
            $id = self::userData()['id'];
        }

        if(is_numeric($id))
        {
            if($selectElement === null)
            {
                $selectElement = '*';
            }

            $data = DataBase::query('SELECT '.$selectElement.' FROM `users` WHERE `id` = ? LIMIT 1', [$id])->fetch_assoc();

            if($data)
            {
                return $data;
            }
        }

        return false;
    }

    public static function activity()
    {
		if(self::userData() !== false)
		{
			if(self::userData()['activity'] <= time())
			{
				$time = time() + 180; // 3 минуты
				DataBase::query('UPDATE `users` SET `activity` = ? WHERE `id` = ?', [$time, self::userData()['id']]);
			}

        return true;
		
		}
        
		return false;
    }

    public static function newLevel()
    {
        if(self::userData()['exp'] >= self::levels())
        {
            DataBase::query('UPDATE `users` SET `level` = `level` + 1, `gold` = `gold` + 5, `exp` = `exp` - ? WHERE `id` = ?', [self::levels(), self::userData()['id']]);

            $_SESSION['newLevel'] = 1;
        }

        return true;
    }

    public static function levels($userLevel = null)
    {
        if($userLevel === null)
        {
            $userLevel = self::userData()['level'];
        }

        $table = include './app/configs/tableLevels.php';

        foreach ($table as $key => $value)
        {
            if($userLevel == $key)
            {
                return $value;
            }
        }

        return false;
    }

    public static function progressExp()
    {
        $UserData = DataBase::query('SELECT `exp` FROM `users` WHERE `id` = ?', [self::userData()['id']])->fetch_assoc();

        if($UserData['exp'] > 0)
        {
            return floor( 100 / (self::levels() / $UserData['exp']));
        }
        return 0;
    }

    public static function amountUsersOnline()
    {
        return DataBase::query('SELECT COUNT(*) FROM `users` WHERE `activity` > '.time())->fetch_array();
    }

    public static function maneken($Helmet, $Shoulders, $Armor, $Gloves, $Pants, $Boots, $LeftHand, $RightHand)
    {

    }
}