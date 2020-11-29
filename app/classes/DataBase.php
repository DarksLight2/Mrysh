<?php

namespace app\classes;

use mysqli;

define('HOST', 'localhost');      // Сервер подключения
define('USERNAME', 'root');       // Имя пользователя
define('PASSWORD', 'root');       // Пароль
define('DBNAME', 'mrush');        // Название базы данных
define('CHARSET', 'utf8');        // Кодировка

class DataBase
{
    private static $instance = null;

    private function __construct() {}

    private function __clone() {}

    private static function getInstance()
    {
        if(self::$instance === null) {
            self::$instance = new mysqli(HOST, USERNAME, PASSWORD, DBNAME);
            self::$instance->set_charset(CHARSET);
        }

        return self::$instance;
    }

    private static function getParamsAndTypesInArray($params) // Разделяем полученый массив, и получаем типы данных
    {
        $result = [];
        $types = '';

        foreach($params as $key => $value) {
            $result[$key] = &$params[$key];

            if(is_numeric($value))
                $types .= 'i';
            else
                $types .= 's';
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

            if( ! call_user_func_array([$stmt, 'bind_param'], self::getParamsAndTypesInArray($params))) {
                echo '<br>';
                echo '<br>';
                var_dump($stmt);
                echo '<br>';
                var_dump(self::getParamsAndTypesInArray($params));
                echo '<br>';

                echo 'Запрос '.$query;
                echo '<br>';
                echo '<br>';
            }

            $stmt->execute(); // Выполняем запрос

            if(!empty($stmt->error) || !empty(self::getInstance()->error)) // Проверка на ошибки
                echo 'Ошибка в запросе к базе данных. <small><i>['.self::getInstance()->error.']</i></small>';

            return $stmt->get_result(); // Получаем результат
        }
        else
            return self::getInstance()->query($query);
    }
}