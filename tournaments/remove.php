<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("../teams/");


$currentUser = CookieHelper::GetCurrentUser($_DATABASE);
if (!$currentUser->checkPermission(1)) {

    CookieHelper::AddSessionMessage("У вас недостаточно прав для совершения этого действия", CookieHelper::DANGER);
    $id = $_REQUEST["id"];
    ApplicationHelper::redirect("../teams/view.php?id=$id");
}


$instance = Team::getInstanceFromDatabase($id, $_DATABASE);

if (is_null($instance)) {
    CookieHelper::AddSessionMessage("Команда с ID".$_REQUEST["id"]." не найдена в базе данных", CookieHelper::DANGER);
    ApplicationHelper::redirect("../teams/");
}

if (!isset($_POST["confirmed"])){
    Html::RenderHtmlHeader("Удаление команды");
    ?>
    <div class="container">
        <div class="mt-2">
            <h1>Удаление записи <?= $instance->name ?> (ID <?= $instance->id ?>)</h1>
        </div>
        <p>
            <h3>Личная информация</h3>
            <dl class="dl-horizontal">
                <dt>Название</dt> <dd><?= $instance->name ?></dd>
                <dt>ID</dt> <dd><?= $instance->id ?></dd>
            </dl>
        </p>
        <?php FormSnippets::RenderDeleteFormFields($instance) ?>

    </div>

    <?php
    Html::RenderHtmlFooter();

} else {

    $user = CookieHelper::GetCurrentUser($_DATABASE);

    if (!$user->checkPermission(1)) {

        CookieHelper::AddSessionMessage("У вас недостаточно прав для совершения этого действия", CookieHelper::DANGER);
        ApplicationHelper::redirect("/teams/");
    }

    $deleteResult = $instance->deleteFromDatabase($_DATABASE);
    //$deleteResult = $instance->setActiveStatus($_DATABASE);

    $message = null;
    $type = null;
    if ($deleteResult["result"] == true){
        $message = "Команда ".$instance->name." (ID".$instance->id.") удалена успешно";
        $type = CookieHelper::SUCCESS;
    } else {
        //$message = "Возникла неожиданная ошибка при удалении сущности";
        $message = $deleteResult["data"];
        $type = CookieHelper::SUCCESS;
    }
    CookieHelper::AddSessionMessage($message, $type);
    ApplicationHelper::redirect("/teams/");
}