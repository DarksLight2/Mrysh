<?php

namespace app\classes;

class Travel
{

    private function __construct() {}

    public static function CreateTravel($PlaceID): void
    {
        if( ! isset($_SESSION['travel']['Start']))
        {
            $Query = DataBase::query('SELECT * FROM `travel_places` WHERE `id` = ? LIMIT 1', [$PlaceID]);

            if($Query->num_rows === 1)
            {
                DataBase::query('INSERT INTO `travel_users` (`place`, `time`) VALUES (?, ?)', [$PlaceID, $Query->fetch_assoc()['time']]);
            }

            $Query->close();
        }
    }

    public static function ChangeStartStatus($PlaceID, $Status): void
    {
        if($Status === 'end')
        {
            unset($_SESSION['travel']['Start']);
        }
        else
        {
            $Query = DataBase::query('SELECT * FROM `travel_places` WHERE `id` = ? LIMIT 1', [$PlaceID]);

            if($Query->num_rows === 1)
            {
                $PlaceData = $Query->fetch_assoc();

                if($PlaceData['need_strength'] <= User::userData()['strength'])
                {
                    $_SESSION['travel']['Start'] = 1;

                    self::CreateTravel($PlaceID);

                    //header('Location: /travel');
                }
                else
                {
                    header('Location: /travel');
                }
            }
            else
            {
                header('Location: /travel');
            }
        }
    }

    public static function ShowAllPlaces(): array
    {
        $Arr = [];

        $Query = DataBase::query('SELECT * FROM `travel_places` WHERE `need_strength` <= ? ORDER BY `need_strength`', [User::userData()['strength']]);

        if($Query->num_rows > 1)
        {
            while($Row = $Query->fetch_assoc())
            {
                $Arr[] = $Row;
            }
        }
        else
        {
            $Arr[0] = $Query->fetch_assoc();
        }

        return $Arr;
    }

    public static function ShowNextNotAvailablePlace()
    {
        return DataBase::query('SELECT * FROM `travel_places` WHERE `need_strength` > ? ORDER BY `need_strength` LIMIT 1', [User::userData()['strength']]);
    }

    public static function Enemy()
    {

    }
}