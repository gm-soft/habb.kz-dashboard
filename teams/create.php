<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$actionPerformed = isset($_REQUEST["actionPerformed"]) ? $_REQUEST["actionPerformed"] : "initiated";

switch ($actionPerformed){
    case "initiated":

        $gamers = Gamer::getInstancesFromDatabase($_DATABASE);

        $pageTitle = "Создание новой команды";
        Html::RenderHtmlHeader($pageTitle);
        $formAction = "../teams/create.php";

        ?>
        <div class="container">
            <div class="mt-2">
                <h1><?= $title ?></h1>
            </div>
            <?php FormSnippets::RenderTeamFormFields(null, $gamers, $formAction) ?>
        </div>
        <?php
        break;

    case "dataInput":
        $_REQUEST["id"]             = -1;
        $_REQUEST["name"]           = FormHelper::ClearInputData($_REQUEST["name"]);
        $_REQUEST["score"]          = FormHelper::ClearInputData($_REQUEST["score"]);
        $_REQUEST["score_change"]   = ApplicationHelper:: formatPhone($_REQUEST["score_change"]);
        $_REQUEST["captain_id"]     = FormHelper::ClearInputData($_REQUEST["captain_id"]);
        $_REQUEST["player_2_id"]    = FormHelper::ClearInputData($_REQUEST["player_2_id"]);
        $_REQUEST["player_3_id"]    = FormHelper::ClearInputData($_REQUEST["player_3_id"]);
        $_REQUEST["player_4_id"]    = FormHelper::ClearInputData($_REQUEST["player_4_id"]);
        $_REQUEST["player_5_id"]    = FormHelper::ClearInputData($_REQUEST["player_5_id"]);
        $_REQUEST["player_2_id"]    = !empty($_REQUEST["player_2_id"]) ? $_REQUEST["player_2_id"] : "null";
        $_REQUEST["player_3_id"]    = !empty($_REQUEST["player_3_id"]) ? $_REQUEST["player_3_id"] : "null";
        $_REQUEST["player_4_id"]    = !empty($_REQUEST["player_4_id"]) ? $_REQUEST["player_4_id"] : "null";
        $_REQUEST["player_5_id"]    = !empty($_REQUEST["player_5_id"]) ? $_REQUEST["player_5_id"] : "null";



        $instance = Team::fromRequest($_REQUEST);
        $instance->last_operation = "Пользователь ".$_COOKIE["login"]." создал запись";

        $updateResult = $instance->insertToDatabase($_DATABASE);

        $message = null;
        $type = null;

        if ($updateResult["result"] == true){
            $message = "Команда сохранена";
            $type = CookieHelper::SUCCESS;

            $url = "../teams/view.php?id=".$updateResult["data"];
        } else {
            $message = $updateResult["data"];
            $type = CookieHelper::DANGER;
            $url = "../teams/create.php";
        }
        CookieHelper::AddSessionMessage($message, $type);
        ApplicationHelper::redirect($url);

        break;
}

?>


<?php
Html::RenderHtmlFooter();