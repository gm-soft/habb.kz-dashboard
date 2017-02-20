<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 18.11.2016
 * Time: 8:46
 */
class BitrixHelper
{

    public static function getAuth() {
        $url = "http://newb24.next.kz/rest/control.php";
        $params = array("action" => "getAccessToken");
        $result = RequestHelper::Get($url, $params);
        $result = isset($result["access_token"]) ? $result["access_token"] : null;
        return $result;
    }


    /**
     * @param $client Client
     * @param null $auth
     * @return array|null
     */
    public static function addLead($client, $auth = null) {
        if (is_null($client)) return null;

        $auth = is_null($auth) ? self::getAuth() : $auth;
        if (is_null($auth)) return null;

        $params = array(
            "fields[TITLE]" => "Участник HABB.KZ ".$client->getFullName(),
            "fields[NAME]" => $client->name,
            "fields[LAST_NAME]" => $client->last_name,
            "fields[PHONE][0][VALUE]" => $client->phone,
            "fields[EMAIL][0][VALUE]" => $client->email,
            "fields[UF_CRM_1479438123]" => $client->id, // habb id
            "fields[STATUS_ID]"=> "NEW",
            "fields[OPENED]"=> "Y",
            "fields[ASSIGNED_BY_ID]" => "16",
            "fields[CREATED_BY_ID]" => "16",
            "auth" => $auth
        );

        return self::callMethod("crm.lead.add", $params);
    }

    /**
     * Вызов метода REST.
     *
     * @param string $method вызываемый метод
     * @param array $params параметры вызова метода
     *
     * @return array
     */
    public static function callMethod($method, $params)
    {
        return RequestHelper::Get("https://habb1.bitrix24.kz/rest/".$method, $params);
    }

    /**
     * Вызов методов REST способом BATCH.
     *
     * @param string $commands массив GET-запросов
     * @param string $access_token токен авторизации
     *
     * @return array
     */
    public static function batch($commands, $access_token){
        $batch_params = array("auth" => $access_token, "halt" => 0, "cmd" => $commands);
        $call_result = self::callMethod("batch", $batch_params);
        return $call_result;
    }

    /**
     * @param $commands
     * @param $access_token
     * @return array|null
     */
    public static function batch_commands($commands, $access_token){
        $result = array();
        $command_to_execute = array();
        $temp_array = array();

        for ($i = 0; $i < count($commands); $i++) {
            $temp_array[] = $commands[$i];

            if (count($temp_array) == 49){
                $command_to_execute[] = $temp_array;
                $temp_array = array();
            }
            if ($i == (count($commands) -1)) $command_to_execute[] = $temp_array;
        }

        foreach ($command_to_execute as $cmd) {
            $batch_result = self::batch($cmd, $access_token);
            $data = isset($batch_result["result"]) ? $batch_result["result"] : $batch_result;
            $result = array_merge($result, $data);
        }
        return count($result) > 0 ? $result : null;
    }
}