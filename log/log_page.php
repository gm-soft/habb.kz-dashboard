<?php

    require($_SERVER["DOCUMENT_ROOT"] . "/include/config.php");


    $access_data = isset($_SESSION["access_data"]) ? $_SESSION["access_data"] : null;


    $logType = isset($_REQUEST["type"]) ? $_REQUEST["type"] : null;

    switch ($logType) {
        case 'errors':
            $log_filename = $_SERVER["DOCUMENT_ROOT"]."/log/errors.log";
            $pageTitle = "Ошибки";
            break;
        case "process_events":
            $log_filename = $_SERVER["DOCUMENT_ROOT"]."/log/process_events.log";
            $pageTitle = "События";
            break;
        case "auth_events":
            $log_filename = $_SERVER["DOCUMENT_ROOT"]."/log/auth.log";
            break;
        //case "apache":
        //    $log_filename = "/var/log/apache2/error.log";
        //    break;
        case "debug":
            $log_filename = $_SERVER["DOCUMENT_ROOT"]."/log/debug.log";
            $pageTitle = "Дебаг";
            break;
        default:
            $log_filename = null;
            break;
    }

    $log_text = "empty log file";
    if (!is_null($log_filename)) {
        $filename = $log_filename;

        $log_text = ApplicationHelper::readFromFile($filename);

        if ($logType == "process_events" || $logType == "errors") {
            $log_text_split = $split_array = explode("\n", $log_text);
            $log_text_split = ApplicationHelper::reverseArray($log_text_split);
            $log_text = join("\n", $log_text_split);
        }

    }

    $page_header = !is_null($log_filename) ? "Файл ".$log_filename : "Открыть файл логов";
    $link_to_file = str_replace("/var/www/newb24.next.kz", '', $log_filename);
    Html::RenderHtmlHeader($pageTitle);

?>

<div class="container">
    <h1><?= $page_header ?></h1>

    <p>Выберите из списка файл логов, чтобы открыть его</p>
    <div class="btn-group" role="group" aria-label="Log files">
        <a href="log_page.php?type=errors" class="btn btn-secondary">errors.log</a>
        <a href="log_page.php?type=process_events" class="btn btn-secondary">events.log</a>
        <a href="log_page.php?type=debug" class="btn btn-secondary">debug.log</a>
    </div>
    <p>
        <pre><?= $log_text ?></pre>
    </p>


</div>



<?php
     Html::RenderHtmlFooter();

