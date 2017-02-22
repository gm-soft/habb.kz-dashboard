<?php

/**
 * Хедпер для рендера отдельных страниц html
 */
abstract class Html
{
    const HTML_BACK = "back";
    const HTML_FRONT = "front";

    /**
     * Функция рендерит страницу, расположение которой передается по аргументу
     * @param $filename
     * @param bool $withDeath
     */
    public static function Render($filename, $withDeath = false){

        $content = ApplicationHelper::readFromFile($filename);
        if (!is_null($content)){
            echo $content;
        } else {
            ApplicationHelper::processError("Файл $filename отсутствует");
        }

        if ($withDeath == true) die();
    }

    /**
     * Рендерит ошибку по запросу.
     * @param int $code
     */
    public static function RenderError($code = 500){
        switch ($code){
            case 404:
                $filename = $_SERVER["DOCUMENT_ROOT"]."/shared/error404.html";
                break;
            default:
                $filename = $_SERVER["DOCUMENT_ROOT"]."/shared/error.html";
                break;
        }
        header("HTTP $code");
        self::Render($filename, true, $code);
    }

    /**
     * Выводит на экран заголовочный текст страницы. Опционально выводит панель навигации
     *
     * @param string $pageTitle Заголовок страницы
     * @param bool $withNavbar Выводить ли навигацию
     * @param string $type Тип футера: фронт или бэк
     */
    public static function RenderHtmlHeader($pageTitle = "Панель управления HABB.KZ ", $withNavbar = true, $type = self::HTML_BACK){

        $filename = $_SERVER["DOCUMENT_ROOT"]."/shared/";
        $filename .= $type == self::HTML_FRONT ? "front/" : "back/";
        $filename .= "header.html";

        $header = ApplicationHelper::readFromFile($filename);
        $header = str_replace("{pageTitle}", $pageTitle, $header);
        echo $header;
        if ($withNavbar == true) {
            self::RenderHtmlNavbar();
        }
        CookieHelper::RenderSessionMessages();

    }

    /**
     * Выводит на экран текст полосы навигации
     */
    public static function RenderHtmlNavbar(){
        $filename = $_SERVER["DOCUMENT_ROOT"]."/shared/back/navbar.html";
        $navbar = ApplicationHelper::readFromFile($filename);
        $navbar = str_replace("{username}", CookieHelper::GetSavedUsername(), $navbar);
        echo $navbar;
    }

    /**
     * Выводит на экран текст футера
     * @param bool $withNavbar Выводить ли тег footer
     * @param string $type Тип футера: фронт или бэк
     */
    public static function RenderHtmlFooter($withNavbar = true, $type = self::HTML_BACK){

        $filename = $_SERVER["DOCUMENT_ROOT"]."/shared/";
        $filename .= $type == self::HTML_FRONT ? "front/" : "back/";
        $filename .= "footer.html";


        $footer = ApplicationHelper::readFromFile($filename);

        if (DEBUG == true) {
            ?>
            <div class='container'>
                <pre>
                    <?= var_export($_SERVER, true) ?>
                </pre>
            </div>
            <?php

        }

        if ($withNavbar == true) {

            ?>
            <footer class='footer'>
                <div class='container'>
                    Habb.KZ - Управление аккаунтами habb
                    <span class='float-sm-right'>2017</span>
                </div>
            </footer>


            <?php
        }
        echo $footer;
    }

    /**
     * Возвращает json-представление объекта вместе с указанием application/json в хеадах
     *
     * @param $object mixed
     */
    public static function RenderJson($object){
        header('Content-Type: application/json');
        echo json_encode($object);
    }

    /**
     * Выводит некий объект через функцию var_export(). Может вернуть контент либо зарендерить его
     * @param $object mixed
     * @param bool $toReturn
     * @return mixed
     */
    public static function RenderDebug($object, $toReturn = false){
        $result = var_export($object, true);
        if ($toReturn == true) {
            return $result;
        } else {
            echo $result;
        }

    }

}






















