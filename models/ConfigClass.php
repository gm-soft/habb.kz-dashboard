<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 10.02.2017
 * Time: 8:03
 */
class Config
{
    /**
     * @var array
     */
    private static $config;

    const KEY_UNKNOWN   = "unknown";

    const DB_USERNAME   = "db_username";
    const DB_PASSWORD   = "db_password";
    const DB_NAME       = "db_name";

    const SMTP_LOGIN    = "smtp_login";
    const SMTP_PASS     = "smtp_pass";
    const SMTP_SERVER   = "smtp_server";
    const SMTP_FROM     = "smtp_from";
    const SMTP_PORT     = "smtp_port";

    const B24_CLIENT_ID     = "client_id";
    const B24_CLIENT_SECRET = "client_secret";
    const B24_SERVER_PATH   = "server_path";
    const B24_REDIRECT_URI  = "redirect_uri";
    const B24_SCOPE         = "scope";

    const CITIES            = "cities";
    const CHALLONGE_API     = "challonge_token";

    public static function Init()
    {
        self::$config = parse_ini_file(CONFIG_PATH);
        if (self::$config == false) {
            self::$config = null;
            ApplicationHelper::processError("Отсутствует файл конфигурации", true);
        }
    }

    /**
     * Возвращает значение определенного поля
     * @param string $key
     * @return mixed|null
     */
    public static function getValue($key = self::KEY_UNKNOWN){
        if ($key == self::KEY_UNKNOWN || is_null(self::$config)) return null;

        return self::$config[$key];
    }
}