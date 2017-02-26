<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("../users/");


$currentUser = CookieHelper::GetCurrentUser($_DATABASE);


$viewPermission = $currentUser->checkPermission(2);
$setPermission = $currentUser->checkPermission(3);
$godPermission = $currentUser->checkPermission(4);

if ($viewPermission == false){
    CookieHelper::AddSessionMessage("У Вас недостаточно прав для этого действия", CookieHelper::DANGER);
    ApplicationHelper::redirect("../users/");
}

$instance = User::getInstanceFromDatabase($id, $_DATABASE, "user_id");
$pageTitle = "Удаление сущности NEXT.Accounts";

if (!isset($_POST["confirmed"])){
    Html::RenderHtmlHeader($pageTitle);
    ?>
    <div class="container">
        <div class="mt-2">
            <h1>Удаление пользователя <?= $instance->login ?> (<?= $instance->id ?>)</h1>
        </div>
        <?php SharedSnippets::RenderUserView($instance) ?>

        <form method="post" action="">
            <input type="hidden" name="id" value="<?= $instance->id ?>">
            <input type="hidden" name="confirmed" value="true">
            <div class="checkbox">
                <label><input type="checkbox" required> Подтвердить удаление</label>
            </div>
            <button type="submit" class="btn btn-danger">Удалить запись</button>
        </form>

    </div>

    <?php
    Html::RenderHtmlFooter();

} else {

    $deleteResult = $instance->deleteFromDatabase($_DATABASE);

    $message = null;
    $type = null;

    if ($deleteResult["result"] == true){
        $message = "Пользователь ".$instance->login." (ID".$instance->id.") удален успешно";
        $type = CookieHelper::SUCCESS;

    } else {
        $message = "Возникла неожиданная ошибка при удалении сущности";
        $type = CookieHelper::DANGER;
    }
    CookieHelper::AddSessionMessage($message, $type);
    ApplicationHelper::redirect("../users/");
}