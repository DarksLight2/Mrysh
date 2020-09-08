<?php

/*
 Singleton подключение к базе данных.
 By DarksLight2
 */

namespace app\classes;

use mysqli;

define('HOST', 'localhost');      // Сервер подключения
define('USERNAME', 'artem_lazar');  // Имя пользователя
define('PASSWORD', 'Mrysh.mobiDataBase');  // Пароль
define('DBNAME', 'mrysh');    // Название базы данных
define('CHARSET', 'utf8');        // Кодировка

class DataBase
{
    private static $instance = null;

    private function __construct() {}

    private function __clone() {}

    public static function getInstance()
    {
        if(self::$instance === null)
        {
            self::$instance = new mysqli(HOST, USERNAME, PASSWORD, DBNAME);
            self::$instance->set_charset(CHARSET);
        }
        return self::$instance;
    }

    public static function getParamsAndTypesInArray($params) // Разделяем полученый массив, и получаем типы данных
    {
        $result = [];
        $types = '';

        foreach($params as $key => $value)
        {
            $result[$key] = &$params[$key];

            if(is_numeric($value))
            {
                $types .= 'i';
            }
            else
            {
                $types .= 's';
            }
        }

        array_unshift($result, $types);
        return $result;
    }

    public static function lastInsertID()
    {
        return self::getInstance()->insert_id;
    }

    public static function query($query, $params = null) // Выполнение запроса
    {
        if($params != null)
        {
            $stmt = self::getInstance()->prepare($query); // Подготавливаем запрос

            call_user_func_array([$stmt, 'bind_param'], self::getParamsAndTypesInArray($params));

            $stmt->execute(); // Выполняем запрос

            if(!empty($stmt->error) || !empty(self::getInstance()->error)) // Проверка на ошибки
            {
                echo 'Ошибка в запросе к базе данных. <small><i>['.self::getInstance()->error.']</i></small>';
            }

            return $stmt->get_result(); // Получаем результат
        }
        else
        {
            return self::getInstance()->query($query);
        }
    }
}