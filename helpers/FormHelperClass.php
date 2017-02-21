<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 21.02.2017
 * Time: 19:56
 */
class FormHelper
{
    /**
     * Форматирует и очищает от спец-символов входные данные, которые вводятся в поля форм
     *
     * @param $requestVar
     * @return string
     */
    public static function ClearInputData($requestVar){
        $result = trim(htmlspecialchars(stripslashes($requestVar)));
        return $result;
    }
}