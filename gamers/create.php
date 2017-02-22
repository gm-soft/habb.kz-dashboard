<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$actionPerformed = isset($_REQUEST["actionPerformed"]) ? $_REQUEST["actionPerformed"] : "initiated";
$pageTitle = "Создание новой записи о геймере";

switch ($actionPerformed){
    case "initiated":


        Html::RenderHtmlHeader($pageTitle);
        $formAction = "create.php";

        ?>
        <div class="container">
            <div class="mt-2">
                <h1>Создание новой записи о геймере</h1>
            </div>
            <?php FormSnippets::RenderGamerFormFields(); ?>
        </div>
        <?php
        break;

    case "dataInput":

    default:
        Html::RenderHtmlHeader($pageTitle);
        echo "<pre class='container'>".var_export($_REQUEST, true)."</pre>";
        break;
}

?>


<?php
Html::RenderHtmlFooter();