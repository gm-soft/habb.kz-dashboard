<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("../users/");

$currentUser = CookieHelper::GetCurrentUser($_DATABASE);
if (!$currentUser->checkPermission(1)) {

    CookieHelper::AddSessionMessage("У вас недостаточно прав для совершения этого действия", CookieHelper::DANGER);
    $id = $_REQUEST["id"];
    ApplicationHelper::redirect("../users/view.php?id=$id");
}

$viewPermission = $currentUser->checkPermission(2);
$setPermission = $currentUser->checkPermission(3);
$godPermission = $currentUser->checkPermission(4);

$actionPerformed = isset($_REQUEST["actionPerformed"]) ? $_REQUEST["actionPerformed"] : "initiated";
$pageTitle = "Редактирование сущности NEXT.Accounts";

switch ($actionPerformed){
    case "initiated":

        $instance = User::getInstanceFromDatabase($id, $_DATABASE, "user_id");

        if (is_null($instance)) {
            CookieHelper::AddSessionMessage("Пользователь с ID".$id." не найден в базе данных", CookieHelper::DANGER);
            ApplicationHelper::redirect("../users/");
        }

        if ($currentUser->permission <= $instance->permission && $instance->id != $currentUser->id){

            CookieHelper::AddSessionMessage("У вас недостаточно прав для этого действия", CookieHelper::DANGER);
            ApplicationHelper::redirect("../users/");
        }



        Html::RenderHtmlHeader($pageTitle);
        $formAction = "edit.php";
        $formData = $instance->getAsFormArray();

        ?>
        <div class="container">
            <div class="mt-2">
                <h1>Редактирование пользователя <?= $instance->login ?></h1>
            </div>
            <?php require_once $_SERVER["DOCUMENT_ROOT"]."/users/formFields.php"; ?>
        </div>
        <?php
        break;

    case "dataInput":
        $id = $_REQUEST["id"];
        $login = FormHelper::ClearInputData($_REQUEST["userLogin"]);


        $instance = User::getInstanceFromDatabase($id, $_DATABASE, "user_id");

        $instance->permission = intval($_REQUEST["permission"]);
        $instance->login = $login;

        if (!empty($_REQUEST["userPassword"])){
            $password = FormHelper::ClearInputData($_REQUEST["userPassword"]);
            $instance->resetPassword($password);
        }

        $updateResult = $instance->updateInDatabase($_DATABASE);

        $message = null;
        $type = null;

        if ($updateResult["result"] == false) {

            $message = "Возникла ошибка при сохранении данных<br>".$updateResult["data"];
            $type = CookieHelper::DANGER;

            $url = "../users/edit.php?id=".$_REQUEST["id"];
        } else {
            $message = "Данные успешно обновлены";
            $type = CookieHelper::SUCCESS;

            $url = "../users/view.php?id=".$_REQUEST["id"];

        }
        CookieHelper::AddSessionMessage($message, $type);
        ApplicationHelper::redirect($url);
        break;

    default:
        Html::RenderHtmlHeader($pageTitle);
        echo "<div class='container'>Неизвестное действие</div>";
        echo "<pre>".var_export($_REQUEST, true)."</pre>";
        break;
}

?>


<?php
Html::RenderHtmlFooter();