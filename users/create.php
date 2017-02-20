<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

if (!isset($_COOKIE["hash"])){
    $_SESSION["errors"] = array("Чтобы создать новую запись, вы должны быть залогинены");
    ApplicationHelper::redirect("../users/");
}

$currentUser = CookieHelper::GetCurrentUser($_DATABASE);

$viewPermission = $currentUser->checkPermission(2);
$setPermission = $currentUser->checkPermission(3);
$godPermission = $currentUser->checkPermission(4);

if ($viewPermission == false) {
    CookieHelper::AddSessionMessage("У Вас недостаточно прав для этого действия", CookieHelper::DANGER);
    ApplicationHelper::redirect("../users/");
}

$actionPerformed = isset($_REQUEST["actionPerformed"]) ? $_REQUEST["actionPerformed"] : "initiated";
$pageTitle = "Создание сущности NEXT.Accounts";

switch ($actionPerformed){
    case "initiated":
        Html::RenderHtmlHeader($pageTitle);
        $formAction = "create.php";

        ?>
        <div class="container">
            <div class="mt-2">
                <h1>Создание нового пользователя</h1>
            </div>
            <?php require_once $_SERVER["DOCUMENT_ROOT"]."/users/formFields.php"; ?>
        </div>
        <?php
        break;

    case "dataInput":
        $login = ApplicationHelper::ClearInputData($_REQUEST["userLogin"]);
        $password = ApplicationHelper::ClearInputData($_REQUEST["userPassword"]);

        $newInstance = User::fromUserData($login, $password);

        $result = $newInstance->insertToDatabase($_DATABASE);


        $message = null;
        $type = null;
        if ($result["result"] == true) {

            $newInstance->id = $result["data"];
            $message = "Новый пользователь ID".$newInstance->id." создан";
            $type = CookieHelper::SUCCESS;

            $url = "../users/view.php?id=".$newInstance->id;
        }
        else {
            $message = "Пользователь не был создан<br>".$result["data"];
            $type = CookieHelper::DANGER;
            $url = "../users/";
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