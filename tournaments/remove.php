<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("../tournaments/");


$currentUser = CookieHelper::GetCurrentUser($_DATABASE);
if (!$currentUser->checkPermission(1)) {

    CookieHelper::AddSessionMessage("У вас недостаточно прав для совершения этого действия", CookieHelper::DANGER);
    $id = $_REQUEST["id"];
    ApplicationHelper::redirect("../tournaments/view.php?id=$id");
}


$instance = Tournament::getInstanceFromDatabase($id, $_DATABASE);

if (is_null($instance)) {
    CookieHelper::AddSessionMessage("Команда с ID".$_REQUEST["id"]." не найдена в базе данных", CookieHelper::DANGER);
    ApplicationHelper::redirect("../tournaments/");
}

if (!isset($_POST["confirmed"])){
    Html::RenderHtmlHeader("Удаление турнира");
    ?>
    <div class="container">
        <div class="mt-2">
            <h1>Удаление записи <?= $instance->name ?> (ID <?= $instance->id ?>)</h1>
        </div>
        <p>
            <h3>Информация</h3>
            <dl class="row">
                <dt class="col-sm-3">Название</dt> <dd class="col-sm-9"><?= $instance->name ?></dd>
                <dt class="col-sm-3">ID</dt> <dd class="col-sm-9"><?= $instance->id ?></dd>
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
        ApplicationHelper::redirect("/tournaments/");
    }

    $deleteResult = $instance->deleteFromDatabase($_DATABASE);
    //$deleteResult = $instance->setActiveStatus($_DATABASE);

    $message = null;
    $type = null;
    if ($deleteResult["result"] == true){
        $message = "Турнир ".$instance->name." (ID".$instance->id.") удален успешно";
        $type = CookieHelper::SUCCESS;
    } else {
        //$message = "Возникла неожиданная ошибка при удалении сущности";
        $message = $deleteResult["data"];
        $type = CookieHelper::SUCCESS;
    }
    CookieHelper::AddSessionMessage($message, $type);
    ApplicationHelper::redirect("/tournaments/");
}