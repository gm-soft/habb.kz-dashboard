<?php

    session_start();
    $_DocumentRoot = $_SERVER["DOCUMENT_ROOT"];

    require "$_DocumentRoot/include/constants.php";

    /** Snippets */
    require "$_DocumentRoot/snippets/SharedSnippets.php";
    require "$_DocumentRoot/snippets/FormSnippets.php";

    require ("$_DocumentRoot/include/lib/ChallongeAPIClass.php");
    require("$_DocumentRoot/models/IDatabaseObject.php");
    require("$_DocumentRoot/models/BaseInstance.php");

    require("$_DocumentRoot/models/Gamer.php");
    require("$_DocumentRoot/models/User.php");
    require("$_DocumentRoot/models/Team.php");

    require("$_DocumentRoot/models/Score.php");
    require("$_DocumentRoot/models/Statistic.php");
    require("$_DocumentRoot/include/ConfigClass.php");

    require("$_DocumentRoot/models/TournamentTypes.php");
    require("$_DocumentRoot/models/Tournament.php");

    require ("$_DocumentRoot/helpers/RequestHelper.php");
    require ("$_DocumentRoot/helpers/FormHelper.php");
    require ("$_DocumentRoot/helpers/SmtpEmailClass.php");
    require ("$_DocumentRoot/helpers/Challonge.php");
    require ("$_DocumentRoot/helpers/Html.php");
    require ("$_DocumentRoot/helpers/CollectionHelper.php");
    require ("$_DocumentRoot/helpers/HtmlHelper.php");
    require ("$_DocumentRoot/helpers/VkHelper.php");
    require ("$_DocumentRoot/helpers/ServerHelper.php");
    require ("$_DocumentRoot/helpers/ApplicationHelper.php");
    require ("$_DocumentRoot/helpers/CookieHelper.php");
    require ("$_DocumentRoot/helpers/MysqlHelper.php");
    require ("$_DocumentRoot/helpers/BitrixHelper.php");


    // Html::RenderError();


    Config::Init();
    Challonge::Init(Config::getValue(Config::CHALLONGE_API));
    $_DATABASE = MysqlHelper::getNewInstance();

    $username = isset($_COOKIE["login"]) ? $_COOKIE["login"] : null;
    $expired = isset($_COOKIE["expired"]) ? $_COOKIE["expired"] : 3601;

    if (!CookieHelper::IsAuthorized() /*|| time() > $expired*/){



        if ($_SERVER['REQUEST_URI'] == "/session/login.php" ||
            strpos($_SERVER['REQUEST_URI'], '/rest/') !== false ||
            strpos($_SERVER['REQUEST_URI'], '/server/') !== false ||
            strpos($_SERVER['REQUEST_URI'], 'personalPublic.php') !== false ||
            strpos($_SERVER['REQUEST_URI'], 'teamPublic.php') !== false ||
            strpos($_SERVER['REQUEST_URI'], '/account.php') !== false

        ) {
            ApplicationHelper::logEvent("requested url: ".$_SERVER['REQUEST_URI']);
            return;
        }


        $_SESSION["request_url"] = $_SERVER['REQUEST_URI'];
        ApplicationHelper::redirect("/session/login.php");
    }





