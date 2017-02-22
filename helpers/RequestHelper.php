<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 17.02.2017
 * Time: 19:52
 */
abstract class RequestHelper
{

    const METHOD_GET = "GET";
    const METHOD_POST = "POST";
    const METHOD_PUT = "PUT";
    const METHOD_DELETE = "DELETE";

    /**
     * Совершает GET запрос с заданными данными по заданному адресу. В ответ ожидается JSON
     *
     * @param string $url адрес
     * @param array|null $data параметры запроса: Post или Get аргументы
     * @return array
     */
    public static function Get($url, $data = null){
        return self::query($url, $data, self::METHOD_GET);
    }

    /**
     * Совершает POST запрос с заданными данными по заданному адресу. В ответ ожидается JSON
     *
     * @param string $url адрес
     * @param array|null $data параметры запроса: Post или Get аргументы
     * @return array
     */
    public static function Post($url, $data = null){
        return self::query($url, $data, self::METHOD_POST);
    }

    /**
     * Совершает PUT запрос с заданными данными по заданному адресу. В ответ ожидается JSON
     *
     * @param string $url адрес
     * @param array|null $data параметры запроса: Post или Get аргументы
     * @return array
     */
    public static function Put($url, $data = null){
        return self::query($url, $data, self::METHOD_PUT);
    }

    /**
     * Совершает DELETE запрос с заданными данными по заданному адресу. В ответ ожидается JSON
     *
     * @param string $url адрес
     * @param array|null $data параметры запроса: Post или Get аргументы
     * @return array
     */
    public static function Delete($url, $data = null){
        return self::query($url, $data, self::METHOD_DELETE);
    }


    /**
     * Совершает запрос с заданными данными по заданному адресу. В ответ ожидается JSON
     *
     * @param string $url адрес
     * @param array|null $data параметры запроса: Post или Get аргументы
     * @param string $method GET|POST - тип запроса
     * @return array
     */
    public static function query($url, $data = null, $method = "POST")
    {
        $query_data = "";

        $curlOptions = array(
            CURLOPT_RETURNTRANSFER => true
        );

        if($method == "POST")
        {
            $curlOptions[CURLOPT_POST] = true;
            $curlOptions[CURLOPT_POSTFIELDS] = http_build_query($data);
        }
        elseif(!empty($data))
        {
            $url .= strpos($url, "?") > 0 ? "&" : "?";
            $url .= http_build_query($data);
        }

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt_array($curl, $curlOptions);
        $result = curl_exec($curl);
        //ApplicationHelper::debug($result);
        return json_decode($result, 1);
    }
}