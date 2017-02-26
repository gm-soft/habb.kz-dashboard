<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

if (!isset($_COOKIE["hash"])){
    $_SESSION["errors"] = array("Чтобы создать новую запись, вы должны быть залогинены");
    ApplicationHelper::redirect("../users/");
}

$currentUser = CookieHelper::GetCurrentUser($_DATABASE);



if (!$currentUser->checkPermission(2)) {
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
            <?php FormSnippets::RenderUserFormFields($currentUser, null, $formAction) ?>
        </div>
        <?php
        break;

    case "dataInput":
        $login      = FormHelper::ClearInputData($_REQUEST["userLogin"]);
        $password   = FormHelper::ClearInputData($_REQUEST["userPassword"]);

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