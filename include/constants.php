<?php

    define('AUTH_FILENAME', $_SERVER["DOCUMENT_ROOT"]."/rest/auth.js");
    define('CONFIG_PATH', $_SERVER["DOCUMENT_ROOT"] . "/include/config.ini");

    define('DEBUG', false);
    define("COOKIES_EXPIRED_TIME", 3600);

    define('TABLE_CLIENTS', "gamers");
    define('TABLE_SCORES', "gamer_scores");
    define('TABLE_TEAM_SCORES', "team_scores");
    define('TABLE_USERS', "users");
    define('TABLE_TEAMS', "teams");
    define('TABLE_STATISTIC', "statistic");