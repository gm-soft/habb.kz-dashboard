<?php

    session_start();
    $_DocumentRoot = $_SERVER["DOCUMENT_ROOT"];

    require "$_DocumentRoot/include/constants.php";
    require("$_DocumentRoot/include/ConfigClass.php");

    /*
     * Инициализация всех вспомогательных файлов, храниящихся в указанных директориях
     */
    $dirs = ["interfaces", "models", "snippets","helpers"];
    foreach ($dirs as $dir){
        $dir = $_DocumentRoot."/".$dir;
        $files = scandir($dir);
        if ($files == false) continue;
        foreach ($files as $file) {

            if ($file == "." | $file == "..") continue;

            $filename = "$dir/$file";
            require $filename;
        }
    }


    // Html::RenderError();


    Config::Init();
    Challonge::Init(Config::getValue(Config::CHALLONGE_API));
    $_DATABASE = MysqlHelper::getInstance();

    $username = isset($_COOKIE["login"]) ? $_COOKIE["login"] : null;
    $expired = isset($_COOKIE["expired"]) ? $_COOKIE["expired"] : 3601;

    if (!CookieHelper::IsAuthorized() /*|| time() > $expired*/){



        if ($_SERVER['REQUEST_URI'] == "/" ||
            $_SERVER['REQUEST_URI'] == "/session/login.php" ||
            strpos($_SERVER['REQUEST_URI'], '/rest/') !== false ||
            strpos($_SERVER['REQUEST_URI'], '/server/') !== false ||
            strpos($_SERVER['REQUEST_URI'], '/public/') !== false

        ) {
            ApplicationHelper::logEvent("requested url: ".$_SERVER['REQUEST_URI']);
            return;
        }


        $_SESSION["request_url"] = $_SERVER['REQUEST_URI'];
        ApplicationHelper::redirect("/session/login.php");
    }





