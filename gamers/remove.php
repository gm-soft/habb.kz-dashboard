<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("/gamers/");

$currentUser = CookieHelper::GetCurrentUser($_DATABASE);
if (!$currentUser->checkPermission(4)) {

    CookieHelper::AddSessionMessage("У вас недостаточно прав для совершения этого действия", CookieHelper::DANGER);
    $id = isset($_REQUEST["clientId"]) ? $_REQUEST["clientId"] : $_REQUEST["id"];
    ApplicationHelper::redirect("/gamers/view.php?id=$id");
}

$instance = Gamer::getInstanceFromDatabase($id, $_DATABASE);

if (is_null($instance)) {
    CookieHelper::AddSessionMessage("Сущность с ID".$_REQUEST["id"]." не найдена в базе данных", CookieHelper::DANGER);
    ApplicationHelper::redirect("/gamers/");
}

$pageTitle = "Удаление аккаунта";
if (!isset($_POST["confirmed"])){
    Html::RenderHtmlHeader($pageTitle);
    ?>
    <div class="container">
        <div class="page-header">
            <h1>Удаление записи <?= $instance->getFullName() ?></h1>
        </div>
        <p>
        <h3>Личная информация</h3>
        <dl class="dl-horizontal">
            <dt>Имя клиента</dt> <dd><?= $instance->getFullName() ?></dd>
            <dt>ID</dt> <dd><?= $instance->id ?></dd>
            <dt>День рождения</dt> <dd><?= date("d.m.Y", $instance->birthday->getTimestamp()) ?> (<?= $instance->getAge() ?> лет)</dd>
            <dt>Номер телефона</dt> <dd><?= $instance->phone ?></dd>
            <dt>Email</dt> <dd><?= $instance->email ?></dd>
            <dt>ID Лида в Б24</dt> <dd><?= $instance->lead_id?></dd>
            <dt>Дата создания</dt> <dd><?= date("d.m.Y H:i:s", $instance->createdAt->getTimestamp())?></dd>
        </dl>
        </p>
        <?php FormSnippets::RenderDeleteFormFields($instance) ?>

    </div>

    <?php
    Html::RenderHtmlFooter();

} else {


    //$deleteResult = $instance->deleteFromDatabase($_DATABASE);
    $deleteResult = $instance->setActiveStatus($_DATABASE);
    $message = null;
    $type = null;

    if ($deleteResult["result"] == true){
    	$message = "Клиент ".$instance->getFullName()." (ID".$instance->id.") удален успешно";
        $type = CookieHelper::SUCCESS;
    } else {
        $message = $deleteResult["data"];
        $type = CookieHelper::DANGER;
    }
    CookieHelper::AddSessionMessage($message, $type);
    ApplicationHelper::redirect("/gamers/");
}