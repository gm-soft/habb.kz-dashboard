<?php
require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
//---------------------------------------------


$performed = isset($_POST["performed"]) ? $_POST["performed"] : false;

if ($performed == true) {
    $err            = array();
    $login          = FormHelper::ClearInputData($_POST["login"]);
    $password       = FormHelper::ClearInputData($_POST["password"]);
    $password_conf  = FormHelper::ClearInputData($_POST["password_confirm"]);

    //if (preg_match("/^[a-zA-Z0-9]+$/", $login)) $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    if ($password != $password_conf) $err[] = "Введенные пароли не совпадают";
    if (strlen($login) >30 || strlen($login) <3) $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";


    $instance = User::getInstanceFromDatabase($login, "username", $_DATABASE);

    if (!is_null($instance)) {
        $err[] = "Пользователь с таким логином уже существует в базе данных";
    }
    if(count($err) == 0)
    {
        $newUser = User::fromUserData($login, $password);
        $res = $newUser->insertToDatabase($_DATABASE);

        if ($res["result"] == true) {
            CookieHelper::SetUserSession($newUser);

            $_SESSION["success"] = array("Вы успешно зарегистрировались на сайте");
            ApplicationHelper::redirect("../index.php");
        } else {

            $_SESSION["errors"] = array($res["data"]);
            ApplicationHelper::redirect("../session/register.php");
        }
    } else {
        $_SESSION["errors"] = $err;
        ApplicationHelper::redirect("../session/register.php");
    }

} else {
    Html::RenderHtmlHeader("Регистрация пользователя в системе управления");
    require_once($_SERVER["DOCUMENT_ROOT"]."/session/registerPage.php");
    Html::RenderHtmlFooter();
}