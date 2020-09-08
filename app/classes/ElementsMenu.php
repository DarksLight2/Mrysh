<?php


namespace app\classes;


class ElementsMenu
{

    private function __construct()
    {
    }

    public static function getElement($Element, $AccessType, $AccessValue, $UserID = null)
    {
        if(file_exists('./'.$Element.'.php'))
        {
            $UserData = User::getUserDataByID($UserID);

            if($UserData !== null)
            {
                if($UserData[$AccessType] >= $AccessValue)
                {
                    echo '<a class="mbtn mb2" href="/'.$Element.'"><img class="icon" src="http://144.76.127.94/view/image/icons/'.$Element.'.png"> '.self::getNameElements($Element).'</a>';
                }
            }
        }

        return false;
    }

    private static function getNameElements($Element)
    {
        $Elements = [
            'home' => 'Главная',
            'shop' => 'Магазин',
            'hero' => 'Мой Герой',
            'clan' => 'Мой клан',
            'travel' => 'Набег',
            'pvp' => 'Колизей',
            'maze' => 'Лабиринт',
            'task' => 'Задания',
            'train' => 'Тренировка',
            'best' => 'Лучшие',
            'paylist' => 'Купить золото',

        ];

        foreach ($Elements as $key => $value)
        {
            if($key == $Element)
            {
                return $value;
            }
        }

        return false;
    }
}