<?php
require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");


if (isset($_GET["game"]) &&
    ($_GET["game"] == Score::SCORE_DOTA  || $_GET["game"] == Score::SCORE_HEARTHSTONE) ){
    $gameName = $_GET["game"];
} else {
    $gameName = Score::SCORE_CSGO;
}

$instances = $_DATABASE->getClientRating($gameName);
$gameTitle = HtmlHelper::getGameTitle($gameName);
$sorting = HtmlHelper::sortInstancesByValue($instances);
$normal = $sorting["normal"];
$bellow = $sorting["bellow"];

$pageTitle = "Личный рейтинг HABB.KZ";

Html::RenderHtmlHeader($pageTitle);

$position = 1;

?>
    <div class="container">
        <h1 class="mt-2">Личный рейтинг <?= $gameTitle ?></h1>

        <?= HtmlHelper::RenderRatingGameButtons($gameName, "../rating/account.php"); ?>

        <table class="table table-hover">
            <thead>
            <tr>
                <th class="">Рейтинг</th>
                <th class="">Имя</th>
                <th class="">Очки</th>
                <th class="">За месяц</th>
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
                    HtmlHelper::RenderRowForPersonalInstancePublic($instance, $position, true);
                    $position++;
                }
            }

            if (count($bellow) > 0){
                for ($i = 0; $i < count($bellow); $i++){

                    if ($i == 0) HtmlHelper::RenderHeaderRow("Bellow the line");

                    $instance = $bellow[$i];
                    HtmlHelper::RenderRowForPersonalInstancePublic($instance, $position, true);
                    $position++;
                }
            }





            ?>
            </tbody>
        </table>

    </div>

<?php
Html::RenderHtmlFooter();

