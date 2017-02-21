<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$actionPerformed = isset($_REQUEST["actionPerformed"]) ? $_REQUEST["actionPerformed"] : "initiated";

$currentUser = CookieHelper::GetCurrentUser($_DATABASE);
if (!$currentUser->checkPermission(1)) {

    CookieHelper::AddSessionMessage("У вас недостаточно прав для совершения этого действия", CookieHelper::DANGER);
    $id = isset($_REQUEST["clientId"]) ? $_REQUEST["clientId"] : $_REQUEST["id"];
    ApplicationHelper::redirect("../clients/view.php?id=$id");
}


switch ($actionPerformed){
    case "initiated":

        //$instance = $mysql->getClient($_REQUEST["id"]);
        $instance = Client::getFromDatabase($_DATABASE, $_REQUEST["id"]);

        if (is_null($instance)) {
            CookieHelper::AddSessionMessage("Клиент с ID".$_REQUEST["id"]." не найден в базе данных", CookieHelper::DANGER);
            ApplicationHelper::redirect("/");
        }

        $pageTitle = "Редактирование ".$instance->getFullName();
        Html::RenderHtmlHeader($pageTitle);
        $formAction = "edit.php";
        $formData = $instance->getAsArray();


        ?>
        <div class="container">
            <div class="mt-2">
                <h1>Редактирование записи <?= $instance->getFullName() ?></h1>
            </div>
            <?php require_once $_SERVER["DOCUMENT_ROOT"]."/clients/formFields.php"; ?>
        </div>

        <pre>
            <?= Html::RenderDebug($instance) ?>
        </pre>
        <?php
        break;

    case "dataInput":

        $_REQUEST["id"]         = FormHelper::ClearInputData($_REQUEST["id"]);
        $_REQUEST["name"]       = FormHelper::ClearInputData($_REQUEST["name"]);
        $_REQUEST["last_name"]  = FormHelper::ClearInputData($_REQUEST["last_name"]);
        $_REQUEST["phone"]      = ApplicationHelper:: formatPhone($_REQUEST["phone"]);
        $_REQUEST["email"]      = FormHelper::ClearInputData($_REQUEST["email"]);

        $_REQUEST["steam"]      = FormHelper::ClearInputData($_REQUEST["steam"]);
        $_REQUEST["vk"]         = FormHelper::ClearInputData($_REQUEST["vk"]);

        $_REQUEST["institution"] = FormHelper::ClearInputData($_REQUEST["institution"]);
        $_REQUEST["secondary_games"] = join(", ", $_REQUEST["secondary_games"]);

        $instance = Client::fromRequest($_REQUEST);

        $updateResult = $instance->updateInDatabase($_DATABASE);

        $message = null;
        $type = null;

        if ($updateResult["result"] == true){
            $url = "../clients/view.php?id=".$_REQUEST["id"];
            $message = "Запись сохранена";
            $type = CookieHelper::SUCCESS;
        } else {
            $message = $updateResult["data"];
            $type = CookieHelper::DANGER;
            $url = "../clients/edit.php?id=".$_REQUEST["id"];

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


        if ($changed == 0) ApplicationHelper::redirect("../clients/view.php?id=".$_REQUEST["clientId"]);


        $scoreChangeText =  $changed > 0 ? "+$changed" : "$changed";
        $newScore = $score + $changed;

        $updateResult = $_DATABASE->updateScore($scoreId, $clientId, $gameName, $newScore, $scoreChangeText);
        $message = null;
        $type = null;

        if ($updateResult["result"] == true){
            $url = "../clients/view.php?id=".$_REQUEST["id"];
            $message = "Очки записаны";
            $type = CookieHelper::SUCCESS;
        } else {
            $message = $updateResult["data"];
            $type = CookieHelper::DANGER;
            $url = "../clients/edit.php?id=".$_REQUEST["id"];

        }
        CookieHelper::AddSessionMessage($message, $type);
        ApplicationHelper::redirect("../clients/view.php?id=".$_REQUEST["clientId"]);
        break;

}

?>


<?php
Html::RenderHtmlFooter();