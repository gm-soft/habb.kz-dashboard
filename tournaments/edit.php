<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$actionPerformed = isset($_REQUEST["actionPerformed"]) ? $_REQUEST["actionPerformed"] : "initiated";

$currentUser = CookieHelper::GetCurrentUser($_DATABASE);
if (!$currentUser->checkPermission(1)) {

    CookieHelper::AddSessionMessage("У вас недостаточно прав для совершения этого действия", CookieHelper::DANGER);
    $id = $_REQUEST["id"];
    ApplicationHelper::redirect("/tournaments/view.php?id=$id");
}

switch ($actionPerformed){
    case "initiated":

        $instance = Team::getInstanceFromDatabase($_REQUEST["id"], $_DATABASE);



        if (is_null($instance)) {
            CookieHelper::AddSessionMessage("Турнир с ID".$_REQUEST["id"]." не найден в базе данных", CookieHelper::DANGER);
            ApplicationHelper::redirect("/tournaments/");
        }

        $gamers = Gamer::getInstancesFromDatabase($_DATABASE);

        $title = "Редактирование ".$instance->name;
        Html::RenderHtmlHeader("Редактирование турнира");
        $formAction = "../teams/edit.php";
        $formData = $instance->getAsFormArray();

        ?>
        <div class="container">
            <div class="mt-2">
                <h1>Редактирование турнира <?= $instance->name ?></h1>
            </div>
            <?php FormSnippets::RenderTeamFormFields($formData, $gamers, $formAction) ?>
        </div>
        <?php
        break;

    case "dataInput":

        $_REQUEST["id"]          = FormHelper::ClearInputData($_REQUEST["id"]);
        $_REQUEST["name"]        = FormHelper::ClearInputData($_REQUEST["name"]);

        $_REQUEST["captain_id"]  = FormHelper::ClearInputData($_REQUEST["captain_id"]);
        $_REQUEST["player_2_id"] = FormHelper::ClearInputData($_REQUEST["player_2_id"]);
        $_REQUEST["player_3_id"] = FormHelper::ClearInputData($_REQUEST["player_3_id"]);
        $_REQUEST["player_4_id"] = FormHelper::ClearInputData($_REQUEST["player_4_id"]);
        $_REQUEST["player_5_id"] = FormHelper::ClearInputData($_REQUEST["player_5_id"]);

        $_REQUEST["player_2_id"] = !empty($_REQUEST["player_2_id"]) ? $_REQUEST["player_2_id"] : "null";
        $_REQUEST["player_3_id"] = !empty($_REQUEST["player_3_id"]) ? $_REQUEST["player_3_id"] : "null";
        $_REQUEST["player_4_id"] = !empty($_REQUEST["player_4_id"]) ? $_REQUEST["player_4_id"] : "null";
        $_REQUEST["player_5_id"] = !empty($_REQUEST["player_5_id"]) ? $_REQUEST["player_5_id"] : "null";



        $instance = Team::fromRequest($_REQUEST);
        $instance->lastOperation = "Пользователь ".$_COOKIE["login"]." отредактировал запись";

        $updateResult = $instance->updateInDatabase($_DATABASE);

        $message = null;
        $type = null;

        if ($updateResult["result"] == true){
            $message = "Команда сохранена";
            $type = CookieHelper::SUCCESS;
            $url = "/tournaments/view.php?id=".$_REQUEST["id"];
        } else {
            $message = $updateResult["data"];
            $type = CookieHelper::DANGER;
            $url = "/tournaments/edit.php?id=".$_REQUEST["id"];
        }
        CookieHelper::AddSessionMessage($message, $type);
        ApplicationHelper::redirect($url);
        break;

    case "scoreInput":
        $score = intval($_REQUEST["currentScore"]);
        $changed = intval($_REQUEST["scoreAddition"]);
        $gameName = $_REQUEST["gameName"];
        $scoreId = $_REQUEST["scoreId"];
        $clientId = $_REQUEST["clientId"];

        if ($changed == 0) ApplicationHelper::redirect("/tournaments/view.php?id=$clientId");


        $scoreChangeText =  $changed > 0 ? "+$changed" : "$changed";
        $newScore = $score + $changed;
        $lastOperation = "Пользователь ".$_COOKIE["login"]." установил новое значение очков: $newScore ($scoreChangeText)";
        $updateResult = $_DATABASE->updateTeamScore($clientId, $gameName, $newScore, $scoreChangeText);

        $team = Team::getInstanceFromDatabase($_REQUEST["clientId"], $_DATABASE);
        $playerScoreUpdate = $_DATABASE->updateTeamPlayersScore($team->getPlayersIdAsArray(), $changed, $gameName);



        $message = null;
        $type = null;

        if ($updateResult["result"] == true && $playerScoreUpdate == true){
            $url = "/tournaments/view.php?id=$clientId";
            $message = "Очки записаны";
            $type = CookieHelper::SUCCESS;
        } else {
            $message = $updateResult["data"];
            $type = CookieHelper::DANGER;
            $url = "/tournaments/edit.php?id=$clientId";

        }
        CookieHelper::AddSessionMessage($message, $type);

        ApplicationHelper::redirect($url);
        break;

}

?>


<?php
Html::RenderHtmlFooter();