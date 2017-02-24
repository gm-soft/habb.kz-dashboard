<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
// TODO нужно отредактировать. Пока базовая редакция
$actionPerformed = isset($_REQUEST["actionPerformed"]) ? $_REQUEST["actionPerformed"] : "initiated";

switch ($actionPerformed){
    case "initiated":

        $gamers = Gamer::getInstancesFromDatabase($_DATABASE);

        $pageTitle = "Создание нового турнира";
        Html::RenderHtmlHeader($pageTitle);
        $formAction = "/tournaments/create.php";

        ?>
        <div class="container">
            <div class="mt-2">
                <h1><?= $pageTitle ?></h1>
            </div>
            <?php FormSnippets::RenderTournamentsFormFields(null, $formAction) ?>
        </div>
        <?php
        break;

    case "dataInput":

        // TODO Нужно посмотреть заполнение формы, если редактируется, в частности
        // TODO ибо там заполоняется каждый раз выбранные элементы.
        // TODO Как вариант, нужно передавать массив айдишников и проверять вхождение того или иного элемента в него
        var_export($_REQUEST);
        die();

        $_REQUEST["id"]             = -1;
        $_REQUEST["name"]           = FormHelper::ClearInputData($_REQUEST["name"]);
        $_REQUEST["description"]    = FormHelper::ClearInputData($_REQUEST["description"]);
        $_REQUEST["begin_date"]     = str_replace("T"," ",$_REQUEST["begin_date"]);
        $_REQUEST["reg_close_date"] = str_replace("T"," ",$_REQUEST["reg_close_date"]);
        $_REQUEST["captain_id"]     = FormHelper::ClearInputData($_REQUEST["captain_id"]);
        $_REQUEST["player_2_id"]    = FormHelper::ClearInputData($_REQUEST["player_2_id"]);
        $_REQUEST["player_3_id"]    = FormHelper::ClearInputData($_REQUEST["player_3_id"]);
        $_REQUEST["player_4_id"]    = FormHelper::ClearInputData($_REQUEST["player_4_id"]);
        $_REQUEST["player_5_id"]    = FormHelper::ClearInputData($_REQUEST["player_5_id"]);
        $_REQUEST["player_2_id"]    = !empty($_REQUEST["player_2_id"]) ? $_REQUEST["player_2_id"] : "null";
        $_REQUEST["player_3_id"]    = !empty($_REQUEST["player_3_id"]) ? $_REQUEST["player_3_id"] : "null";
        $_REQUEST["player_4_id"]    = !empty($_REQUEST["player_4_id"]) ? $_REQUEST["player_4_id"] : "null";
        $_REQUEST["player_5_id"]    = !empty($_REQUEST["player_5_id"]) ? $_REQUEST["player_5_id"] : "null";



        $instance = Tournament::fromDatabase($_REQUEST);
        $instance->lastOperation = "Пользователь ".$_COOKIE["login"]." создал запись";

        $updateResult = $instance->insertToDatabase($_DATABASE);

        $message = null;
        $type = null;

        if ($updateResult["result"] == true){
            $message = "Команда сохранена";
            $type = CookieHelper::SUCCESS;

            $url = "../tournaments/view.php?id=".$updateResult["data"];
        } else {
            $message = $updateResult["data"];
            $type = CookieHelper::DANGER;
            $url = "../tournaments/create.php";
        }
        CookieHelper::AddSessionMessage($message, $type);
        ApplicationHelper::redirect($url);

        break;
}

?>


<?php
Html::RenderHtmlFooter();