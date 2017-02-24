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
$displayHeaderImage = isset($_GET["iframed"]);
Html::RenderFrontHtmlHeader($pageTitle, !$displayHeaderImage);
?>

    <div class="container-fluid">
        <h1 class="mt-2">Личный рейтинг <?= $gameTitle ?></h1>

        <?= HtmlHelper::RenderRatingGameButtons($gameName, "../rating/personalPublic.php"); ?>

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

                    if ($i == 0) HtmlHelper::RenderHeaderRow("Premium");
                    if ($i == 5) HtmlHelper::RenderHeaderRow("Дивизион 1");
                    if ($i == 25) HtmlHelper::RenderHeaderRow("Дивизион 2");
                    if ($i == 45) HtmlHelper::RenderHeaderRow("Дивизион 3");
                    if ($i == 65) HtmlHelper::RenderHeaderRow("Дивизион 4");
                    if ($i == 85) HtmlHelper::RenderHeaderRow("Дивизион 5");

                    $instance = $normal[$i];
                    HtmlHelper::RenderRowForPersonalInstancePublic($instance, $position);
                    $position++;
                }
            } ?>

            <?php

            if (count($bellow) > 0){

                HtmlHelper::RenderHeaderRow("Bellow the line");
                for ($i = 0; $i < count($bellow); $i++){

                    $instance = $bellow[$i];

                    HtmlHelper::RenderRowForPersonalInstancePublic($instance, $position);
                    $position++;
                }
            } ?>

            </tbody>
        </table>
    </div>

<?php
Html::RenderFrontFooter();

