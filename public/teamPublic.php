<?php
require($_SERVER["DOCUMENT_ROOT"] . "/include/config.php");


if (isset($_GET["game"]) &&
    ($_GET["game"] == Score::SCORE_DOTA || $_GET["game"] == Score::SCORE_HEARTHSTONE) ){
    $gameName = $_GET["game"];
} else {
    $gameName = Score::SCORE_CSGO;
}
$instances = $_DATABASE->getTeamsForRating($gameName, 0);

$gameTitle = HtmlHelper::getGameTitle($gameName);
$sorting = HtmlHelper::sortInstancesByValue($instances, "teams");
$normal = $sorting["normal"];
$bellow = $sorting["bellow"];

$withNavBar = false;
$pageTitle = "Командный рейтинг HABB.KZ";
$position = 1;



$displayHeaderImage = isset($_GET["iframed"]);
Html::RenderFrontHtmlHeader($pageTitle, !$displayHeaderImage);

?>
    <div class="container-fluid">

        <h1 class="mt-2">Командный рейтинг <?= $gameTitle ?></h1>

        <?= HtmlHelper::RenderRatingGameButtons($gameName, "../public/teamPublic.php"); ?>

        <table class="table table-striped">
            <thead>
            <tr>
                <th class="text-primary">Позиция</th>
                <th class="text-primary">Название</th>
                <th class="text-primary">Очки</th>
                <th class="text-primary">За месяц</th>

                <th class="text-primary">Капитан</th>
                <th class="text-primary">Игрок</th>
                <th class="text-primary">Игрок</th>
                <th class="text-primary">Игрок</th>
                <th class="text-primary">Игрок</th>
            </tr>

            </thead>
            <tbody>


            <?php

            if (count($normal) > 0){


                for ($i = 0; $i < count($normal); $i++){

                    if ($i == 0) HtmlHelper::RenderHeaderRow("Premium", 9);
                    if ($i == 5) HtmlHelper::RenderHeaderRow("Дивизион 1", 9);
                    if ($i == 25) HtmlHelper::RenderHeaderRow("Дивизион 2", 9);
                    if ($i == 45) HtmlHelper::RenderHeaderRow("Дивизион 3", 9);
                    if ($i == 65) HtmlHelper::RenderHeaderRow("Дивизион 4", 9);
                    if ($i == 85) HtmlHelper::RenderHeaderRow("Дивизион 5", 9);


                    $instance = $normal[$i];

                    echo HtmlHelper::constructRowForTeamInstancePublic($instance, $position);
                    $position++;
                }
            }

            if (count($bellow) > 0){
                HtmlHelper::RenderHeaderRow("Bellow the line", 9);
                for ($i = 0; $i < count($bellow); $i++){
                    $instance = $bellow[$i];
                    echo HtmlHelper::constructRowForTeamInstancePublic($instance, $position);
                    $position++;
                }
            } ?>

            </tbody>
        </table>
    </div>

<?php
Html::RenderFrontFooter();
