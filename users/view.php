<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("/");


$currentUser = CookieHelper::GetCurrentUser($_DATABASE);


$viewPermission = $currentUser->checkPermission(2);
$setPermission = $currentUser->checkPermission(3);
$godPermission = $currentUser->checkPermission(4);

if ($viewPermission == false){
    CookieHelper::AddSessionMessage("У Вас недостаточно прав для этого действия", CookieHelper::DANGER);
    ApplicationHelper::redirect("../users/");
}

$pageTitle = "Просмотр сущности NEXT.Accounts";
Html::RenderHtmlHeader($pageTitle);

$instance = User::getInstanceFromDatabase($id, $_DATABASE, "user_id");
?>
    <div class="container">
        <div class="mt-2">
             <h1>Информация о пользователе</h1>
        </div>

       
        <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/users/viewFields.php"; ?>

        <div class="float-sm-left">
            <a href="../users/"  class="btn btn-secondary"><i class="fa fa-chevron-circle-left"  aria-hidden="true"></i> В список</a>
        </div>
        <div class="float-sm-right">

            <a href="../users/edit.php?id=<?= $instance->id?>"  class="btn btn-secondary"><i class="fa fa-pencil"  aria-hidden="true"></i> Редактировать</a>
            <a href="../users/remove.php?id=<?= $instance->id?>"  class="btn btn-danger"><i class="fa fa-remove"  aria-hidden="true"></i> Удалить</a>
        </div>

    </div>

<?php
Html::RenderHtmlFooter();