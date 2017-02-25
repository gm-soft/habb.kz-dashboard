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

        $instance = Tournament::getInstanceFromDatabase($_REQUEST["id"], $_DATABASE);



        if (is_null($instance)) {
            CookieHelper::AddSessionMessage("Турнир с ID".$_REQUEST["id"]." не найден в базе данных", CookieHelper::DANGER);
            ApplicationHelper::redirect("/tournaments/");
        }

        $gamers = Gamer::getInstancesFromDatabase($_DATABASE);

        $title = "Редактирование ".$instance->name;
        Html::RenderHtmlHeader("Редактирование турнира");
        $formAction = "/tournaments/edit.php";
        $formData = $instance->getAsFormArray();

        ?>
        <div class="container">
            <div class="mt-2">
                <h1>Редактирование турнира <?= $instance->name ?> [ID<?= $instance->id ?> ]</h1>
            </div>
            <?php FormSnippets::RenderTournamentsFormFields($instance, $formAction) ?>
        </div>
        <?php
        break;

    case "dataInput":

        $_REQUEST["id"]          = FormHelper::ClearInputData($_REQUEST["id"]);
        $_REQUEST["name"]           = FormHelper::ClearInputData($_REQUEST["name"]);
        $_REQUEST["description"]    = FormHelper::ClearInputData($_REQUEST["description"]);
        $_REQUEST["begin_date"]     = str_replace("T"," ",$_REQUEST["begin_date"]);
        $_REQUEST["reg_close_date"] = str_replace("T"," ",$_REQUEST["reg_close_date"]);

        $_REQUEST["participant_max_count"] = FormHelper::ClearInputData($_REQUEST["participant_max_count"]);
        $_REQUEST["participant_ids"] = ApplicationHelper::joinArray($_REQUEST["participant_ids"]);

        $_REQUEST["challonge_tournament_id"] = FormHelper::ClearInputData($_REQUEST["challonge_tournament_id"]);
        $_REQUEST["comment"]        = !empty($_REQUEST["comment"]) ? $_REQUEST["comment"] : "null";
        $_REQUEST["last_operation"] = "Пользователь ".$_COOKIE["login"]." отредактировал запись";

        $instance = Tournament::fromDatabase($_REQUEST);

        $updateResult = $instance->updateInDatabase($_DATABASE);

        $message = null;
        $type = null;

        if ($updateResult["result"] == true){
            $message = "Турнир сохранен";
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

    case "tournamentScoreAdded":
        echo "<pre>".var_export($_REQUEST, true)."</pre>";
        break;
}

?>


<?php
Html::RenderHtmlFooter();