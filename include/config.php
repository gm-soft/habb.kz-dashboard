<?php

    session_start();

    require ($_SERVER["DOCUMENT_ROOT"]."/include/constants.php");
    require ($_SERVER["DOCUMENT_ROOT"] . "/include/lib/ChallongeAPIClass.php");


    require ($_SERVER["DOCUMENT_ROOT"]."/models/IDatabaseObject.php");
    require ($_SERVER["DOCUMENT_ROOT"]."/models/BaseInstanceClass.php");

    require($_SERVER["DOCUMENT_ROOT"] . "/models/Gamer.php");
    require ($_SERVER["DOCUMENT_ROOT"]."/models/UserClass.php");
    require ($_SERVER["DOCUMENT_ROOT"]."/models/TeamClass.php");

    require ($_SERVER["DOCUMENT_ROOT"]."/models/ScoreClass.php");
    require ($_SERVER["DOCUMENT_ROOT"]."/models/StatisticClass.php");
    require($_SERVER["DOCUMENT_ROOT"] . "/include/ConfigClass.php");

    require ($_SERVER["DOCUMENT_ROOT"] . "/helpers/RequestHelper.php");
    require ($_SERVER["DOCUMENT_ROOT"] . "/helpers/FormHelper.php");
    require ($_SERVER["DOCUMENT_ROOT"] . "/helpers/SmtpEmailClass.php");
    require ($_SERVER["DOCUMENT_ROOT"] . "/helpers/Challonge.php");
    require ($_SERVER["DOCUMENT_ROOT"] . "/helpers/Html.php");
    require ($_SERVER["DOCUMENT_ROOT"] . "/helpers/CollectionHelper.php");
    require ($_SERVER["DOCUMENT_ROOT"] . "/helpers/HtmlHelper.php");
    require ($_SERVER["DOCUMENT_ROOT"] . "/helpers/VkHelper.php");
    require ($_SERVER["DOCUMENT_ROOT"] . "/helpers/ServerHelper.php");
    require ($_SERVER["DOCUMENT_ROOT"] . "/helpers/ApplicationHelper.php");
    require ($_SERVER["DOCUMENT_ROOT"] . "/helpers/CookieHelper.php");
    require ($_SERVER["DOCUMENT_ROOT"] . "/helpers/MysqlHelper.php");
    require ($_SERVER["DOCUMENT_ROOT"] . "/helpers/BitrixHelper.php");


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





