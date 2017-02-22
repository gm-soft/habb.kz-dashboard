<?php

/**
 * Хедпер для работы с кукисами и сессией как объектом
 */
abstract class CookieHelper
{

    const SUCCESS = "success";
    const DANGER = "danger";
    const WARNING = "warning";
    const INFO = "info";

    const TYPES = [self::SUCCESS, self::DANGER, self::WARNING, self::INFO];

    /**
     * Очищает установленные куки
     *
     * @return bool
     */
    public static function ClearCookies(){
        $expired = time() - 3600;
        setcookie("login", "", $expired, "/");
        setcookie("hash", "", $expired, "/");
        setcookie("expired", "", $expired, "/");
        return true;
    }

    /**
     * Возвращает логин, записанный в кукисах
     * @return string
     */
    public static function GetSavedUsername(){
        $username = isset($_COOKIE["login"]) ? $_COOKIE["login"] : "Не известен";
        return $username;
    }


    /**
     * Устанавливает необходимые куки
     *
     * @param $user User
     * @return bool
     */
    public static function SetUserSession($user){
        $ts = time();
        $expired  = time()+COOKIES_EXPIRED_TIME;
        
        setcookie("login", $user->login, $expired, "/");
        setcookie("hash", $user->hash, $expired, "/");
        setcookie("expired", $expired, $expired, "/");
        return true;
    }

    /**
     * Возвращает текущего залогиненного пользователя
     *
     * @param MysqlHelper $mysql
     * @return null|User
     */
    public static function GetCurrentUser($mysql){
        $hash = isset($_COOKIE["hash"]) ? $_COOKIE["hash"] : null;

        if (is_null($hash)) return null;
        $user = User::getInstanceFromDatabase($hash, $mysql, "user_hash");
        return $user;
    }

    /**
     * Возвращает результат проверки на присутствие уникальтного хэша авторизации в кукисах
     * @return bool
     */
    public static function IsAuthorized(){
        $result = isset($_COOKIE) && isset($_COOKIE["hash"]);
        return $result;
    }

    /**
     * Добавляет сообщение для юзера, которое будет выведено при перезагрузке страницы. У сообщения есть тип, который соответствует классу Bootstrap 4
     * @param $message
     * @param string $type
     */
    public static function AddSessionMessage($message, $type = self::INFO) {
        $_SESSION[$type][] = $message;
    }

    /**
     * Выводит все накопившиеся сообщения в сессии
     */
    public static function RenderSessionMessages(){

        if (!self::HasSessionMessages()) return;
        //----------------------------------------

        foreach (self::TYPES as $type) {
            if (!isset($_SESSION[$type])) continue;
            self::RenderSessionAlert($_SESSION[$type], $type);
            self::ClearSessionMessage($type);
        }
    }

    /**
     * Рендерит сообщение определенного типа с определенным текстом
     * @param array $messages
     * @param string $type
     */
    private static function RenderSessionAlert(array $messages, $type = self::SUCCESS){
        ?>
        <div class='alert alert-<?=$type?> alert-dismissible fade in' role='alert'>
            <div class="container">
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>

                <?php
                if (count($messages) == 1){
                    echo $messages[0];
                } else {
                    echo "<ul>";
                    foreach ($messages as $item) {
                        echo "<li>$item</li>";
                    }
                    echo "</ul>";
                }

                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Проверяет наличие в сессии сохраненных сообщения для вывода
     * @return bool
     */
    public static function HasSessionMessages(){
        return isset($_SESSION[self::SUCCESS]) || isset($_SESSION[self::DANGER]) || isset($_SESSION[self::WARNING]) || isset($_SESSION[self::INFO]);
    }

    /**
     * Очищает сессионные сообщения
     * @param string $type
     */
    public static function ClearSessionMessage($type = self::INFO){
        unset($_SESSION[$type]);
    }
}