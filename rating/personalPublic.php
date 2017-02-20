<?php
require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

if (isset($_GET["game"]) &&
    ($_GET["game"] == Score::SCORE_DOTA || $_GET["game"] == Score::SCORE_HEARTHSTONE) ){
    $gameName = $_GET["game"];
} else {
    $gameName = Score::SCORE_CSGO;
}

$instances = $_DATABASE->getClientRating($gameName, 0);

$gameTitle = HtmlHelper::getGameTitle($gameName);
$sorting = HtmlHelper::sortInstancesByValue($instances);
$normal = $sorting["normal"];
$bellow = $sorting["bellow"];



$withNavBar = false;
$pageTitle = "Личный рейтинг HABB.KZ";

$position = 1;

Html::RenderHtmlHeader($pageTitle, false);
?>

    <div class="container-fluid">
        <h1 class="mt-2">Личный рейтинг <?= $gameTitle ?></h1>

        <?= HtmlHelper::getRatingGameButtons($gameName, "../rating/personalPublic.php"); ?>

        <table class="table table-striped">
            <thead>
            <tr>
                <td class="text-primary"><b>Позиция</b></td>
                <td class="text-primary"><b>Имя</b></td>
                <td class="text-primary"><b>Очки</b></td>
                <td class="text-primary"><b>За месяц</b></td>
            </tr>
            </thead>

            <tbody>
            <?php

            if (count($normal) > 0){
                for ($i = 0; $i < count($normal); $i++){

                    if ($i == 0) echo HtmlHelper::constructRow("Premium");
                    if ($i == 5) echo HtmlHelper::constructRow("Дивизион 1");
                    if ($i == 25) echo HtmlHelper::constructRow("Дивизион 2");
                    if ($i == 45) echo HtmlHelper::constructRow("Дивизион 3");
                    if ($i == 65) echo HtmlHelper::constructRow("Дивизион 4");
                    if ($i == 85) echo HtmlHelper::constructRow("Дивизион 5");

                    $instance = $normal[$i];
                    echo HtmlHelper::constructRowForPersonalInstancePublic($instance, $position);
                    $position++;
                }
            } ?>

            <?php

            if (count($bellow) > 0){

                echo HtmlHelper::constructRow("Bellow the line");
                for ($i = 0; $i < count($bellow); $i++){

                    $instance = $bellow[$i];

                    echo HtmlHelper::constructRowForPersonalInstancePublic($instance, $position);
                    $position++;
                }
            } ?>

            </tbody>
        </table>
    </div>

<?php
Html::RenderHtmlFooter(false);

