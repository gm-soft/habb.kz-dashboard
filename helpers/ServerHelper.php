<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 19.01.2017
 * Time: 19:14
 */
abstract class ServerHelper
{
    /**
     * Возвращает набор параметров для обновения токена авторизации
     * @param $refresh_token
     * @return array
     */
    public static function constructRefreshParams($refresh_token){
        /*
        https://oauth.bitrix.info/oauth/token/?
        grant_type=refresh_token
        &client_id=app.573ad8a0346747.09223434
        &client_secret=LJSl0lNB76B5YY6u0YVQ3AW0DrVADcRTwVr4y99PXU1BWQybWK
        &refresh_token=nfhxkzk3gvrg375wl7u7xex9awz6o3k8
        */
        $params = array(
            "grant_type" => "refresh_token",
            "client_id" => Config::getValue(Config::B24_CLIENT_ID),
            "client_secret" => Config::getValue(Config::B24_CLIENT_SECRET),
            "redirect_uri" => Config::getValue(Config::B24_REDIRECT_URI),
            "scope" => Config::getValue(Config::B24_SCOPE),
            "refresh_token" => $refresh_token,
        );
        return $params;

    }

    /**
     * Конструирует первый запрос для получения авторизации
     * @param $code - передаваемый код авторизации от сервера
     * @return array - возвращает массив значений
     */
    public static function constructFirstAuthParams($code) {
        $params = array(
            "grant_type" => "authorization_code",
            "client_id" => Config::getValue(Config::B24_CLIENT_ID),
            "client_secret" => Config::getValue(Config::B24_CLIENT_SECRET),
            "redirect_uri" => Config::getValue(Config::B24_REDIRECT_URI),
            "scope" => Config::getValue(Config::B24_SCOPE),
            "code" => $code,
        );
        return $params;
    }

    /**
     * Возвращает массив авторизационных данных, содержащийся в файле
     *
     * @param bool $token_only
     * @return array|null
     */
    public static function readAccessData($token_only = false) {
        $access_source = ApplicationHelper::readFromFile(AUTH_FILENAME);
        if ($access_source == "null") return null;
        $access_data = ApplicationHelper::toJson($access_source);

        if ($token_only == true) {
            $access_data = isset($access_data["access_token"]) ? $access_data["access_token"] : $access_data;
        }

        return $access_data;
    }
}