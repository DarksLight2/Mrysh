<?php

$Gender = $_GET['gender'];
$Head = $_GET['head'];
$Shoulders = $_GET['shoulders'];
$Armor = $_GET['armor'];
$Gloves = $_GET['gloves'];
$Pants = $_GET['pants'];
$Boots = $_GET['boots'];
$LeftHand = $_GET['left_hand'];
$RightHand = $_GET['right_hand'];

$ArrItems = [$Head, $Armor, $Shoulders, $Pants, $Gloves, $Boots, $LeftHand, $RightHand];
$PathImages = $_SERVER['DOCUMENT_ROOT'].'/view/image/maneken/'.$Gender.'/';

if(isset($_GET))
{
    $Human  = imageCreateFromPng($_SERVER['DOCUMENT_ROOT'].'/view/image/maneken/'.$Gender.'.png');

    $Height  = imagesy($Human); // высота изображения
    $Width  = imagesx($Human); // ширина изображения

    foreach ($ArrItems as $Key => $Value)
    {
        if($Value != 0)
        {
            if(file_exists($PathImages.$Value.'.png'))
            {
                $Item = imageCreateFromPng($PathImages.$Value.'.png');
                $HeightItem  = imagesy($Item); // высота изображения
                $WidthItem  = imagesx($Item); // ширина изображения

                imagecopy($Human, $Item,0,0,0,0,$WidthItem,$HeightItem);
            }
            else
            {
                continue;
            }
        }
    }


    header("Content-type: image/png");

    imagepng($Human); // Вывод гототовой картинке в браузер

}
else
{
    header('Location: /');
}