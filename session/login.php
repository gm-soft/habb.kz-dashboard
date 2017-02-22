<?php
require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
//---------------------------------------------

$performed = isset($_POST["performed"]) ? $_POST["performed"] : false;

if ($performed == true){
    $err = array();
    $login = FormHelper::ClearInputData($_POST["login"]);
    $password = FormHelper::ClearInputData($_POST["password"]);

    // if (preg_match("/^[a-zA-Z0-9]+$/", $login)) $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    // if (strlen($login) >30 || strlen($login) <3) $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";

    //$user = $mysql->getUser($login);
    $user = User::getInstanceFromDatabase($login, $_DATABASE, "user_login");

    if (is_null($user)) {
        $err[] = "Пользователь с таким логином отсутствует в базе";
        $_SESSION["errors"] = $err;
        ApplicationHelper::redirect("/session/login.php");
    }

    if (!$user->validatePassword($password)) {
        $err[] = "Пароль не совпадает";
    }

    if(count($err) == 0)
    {
        $user->generateNewHash();
        $res = $user->updateUserHash($_DATABASE);

        CookieHelper::SetUserSession($user);

        $_SESSION["success"] = array("Вы успешно авторизовались");
        $url = isset($_SESSION["request_url"]) ? $_SESSION["request_url"] : "/dashboard.php";
        unset($_SESSION["request_url"]);
        ApplicationHelper::redirect($url);

    } else {
        $_SESSION["errors"] = $err;
        ApplicationHelper::redirect("/session/login.php");
    }

} else {
    //require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.html");
    require_once($_SERVER["DOCUMENT_ROOT"]."/session/loginPage.php");
    //require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.html");
}

