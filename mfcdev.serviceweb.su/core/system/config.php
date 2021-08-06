<?php

class config{
    private static $configArr;

    public static function load(){
        if(file_exists('./config.ini')){
            self::$configArr = parse_ini_file('./config.ini', true);
        }
        else{ echo '<h1>Отсутствует конфигурационный файл либо система не установлена'; exit;}
    }

    public static function get($section, $parameter){
        if(isset(self::$configArr[$section][$parameter])){
            return self::$configArr[$section][$parameter];
        }
        else{ return false;}
    }
}