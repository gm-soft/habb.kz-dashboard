<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 06.01.2017
 * Time: 8:11
 */
abstract class ApplicationHelper
{
    /**
     * Производит перенаправление пользователя на заданный адрес
     *
     * @param string $url адрес
     */
    public static function redirect($url)
    {
        header("HTTP 302 Found");
        header("Location: ".$url);
        die();
    }

    /**
     * @param null $format
     * @param null $time
     * @return false|null|string
     */
    public static function formatTime($format = null, $time = null) {

        $time = is_null($time) ? time() + (60*60*6) : $time;
        $result = null;
        switch ($format) {
            case "atom":
                $result = date("c", $time);
                break;

            default:
                $result = date("d.m.y - H:i", $time);
                break;
        }
        return $result;
    }

    /**
     * @param $exception
     */
    public static function processException($exception){

    }

    /**
     * Дозаписывает строку в файл /log/errors.log
     *
     * @param $errorText
     * @param bool $isCritical - Если true, то генерируется страница ошибки, и действие веб-приложения останавливается
     * @return bool
     */
    public static function processError($errorText, $isCritical = false) {
        $filename = $_SERVER["DOCUMENT_ROOT"]."/log/errors.log";
        $content = "[".self::formatTime("atom")."] ".$errorText."\n";
        $append = "APPEND";
        $result = self::writeToFile($filename, $content, $append);
        if ($isCritical == true){
            Html::RenderError();
        }
        return $result;
    }

    /**
     * Дозаписывает строку в файл /log/process_events.log
     *
     * @param $eventText
     * @return bool
     */
    public static function logEvent($eventText) {
        if ($eventText == "") return false;

        $filename = $_SERVER["DOCUMENT_ROOT"]."/log/process_events.log";
        $content = "[".self::formatTime("atom")."] ".$eventText."\n";
        $append = "APPEND";
        return self::writeToFile($filename, $content, $append);
    }

    /**
     * Записывает объект/строку в файл /log/debug.log. Делает перезапись каждый раз
     *
     * @param $something
     * @return bool
     */
    public static function debug($something) {
        $filename = $_SERVER["DOCUMENT_ROOT"]."/log/debug.log";
        $content = "[".self::formatTime("atom")."]".$something."\n";
        return self::writeToFile($filename, $content);
    }

    /**
     * Записывает содержимое в файл. Возвращает результат записи
     * @param $filename - имя файла, в который будет осуществляться запись
     * @param $content - содержимое
     * @param null $append
     * @return bool
     */
    public static function writeToFile($filename, $content, $append = null){
        try {
            if (is_null($append)) file_put_contents($filename,  $content);
            else file_put_contents($filename,  $content, FILE_APPEND);
            return true;
        } catch(Exception $ex){ self::processException($ex); }
        return false;
    }

    /**
     * Читает содержимое файла. Возвращает содержимое либо NULL, если возникла какая-то ошибка
     * @param $filename - имя файла
     * @return null|string
     */
    public static function readFromFile($filename){
        try {
            $content = file_get_contents($filename);
            return $content;
        } catch(Exception $ex){ self::processException($ex); }
        return NULL;
    }

    /**
     * Переворачивает массив задом-наперед
     *
     * @param $array
     * @return array
     */
    public static function reverseArray($array) {
        $result = array();
        for($i = count($array) - 1; $i >= 0;$i--) {
            $result[] = $array[$i];
        }
        return $result;
    }

    public static function getRandomNumber($max, $min = 0){
        $num = mt_rand($min, $max);
        return $num;
    }

    /**
     * Возвращает рандомный элемент массива
     *
     * @param $array array
     * @return mixed
     */
    public static function getRandomItem(array $array){
        $max = count($array) - 1;
        $randomNumber = self::getRandomNumber($max);
        return $array[$randomNumber];
    }

    /**
     * Преобразовывает строку в формате json в объект-json. Возвратит исходный объект в случае ошибки
     * @param $content - исходная строка
     * @return array
     */
    public static function toJson($content){

        try {
            $data = json_decode($content);
            $array = (array)$data;
            foreach($array as $key => &$field){
                if(is_object($field))$field = self::toJson($field);
            }
            return $array;
        } catch(Exception $ex){

        }
        return $content;
    }

    /**
     *
     * @param $phone
     * @return mixed|null
     */
    public static function formatPhone($phone){
        if (is_null($phone)) return null;
        $phone = str_replace("+7", "8", $phone);
        $phone = str_replace("(", "", $phone);
        $phone = str_replace(")", "", $phone);
        $phone = str_replace(" ", "", $phone);
        $phone = str_replace("-", "", $phone);
        return $phone;
    }

    /**
     * Возвращает список городов из файла config.ini
     * @return array
     */
    public static function getCities(){
        $cityString = Config::getValue("cities");
        $cities = explode(",", $cityString);
        return $cities;
    }

    /**
     * Соединяет элементы массива в строку
     * @param array $array
     * @param string $joiner
     * @return string
     */
    public static function joinArray(array $array, $joiner = ","){
        return join($joiner, $array);
    }

    /**
     * Разделяет строку на массив
     * @param $source
     * @param string $delimiter
     * @return array
     */
    public static function explodeArray($source, $delimiter = ","){
        return explode($delimiter, $source);
    }
}