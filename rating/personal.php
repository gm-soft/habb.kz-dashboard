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

        <?= HtmlHelper::getRatingGameButtons($gameName, "../rating/account.php"); ?>

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

                    if ($i == 0) echo HtmlHelper::constructRow("Premium");
                    if ($i == 5) echo HtmlHelper::constructRow("Дивизион 1");
                    if ($i == 25) echo HtmlHelper::constructRow("Дивизион 2");
                    if ($i == 45) echo HtmlHelper::constructRow("Дивизион 3");
                    if ($i == 65) echo HtmlHelper::constructRow("Дивизион 4");
                    if ($i == 85) echo HtmlHelper::constructRow("Дивизион 5");

                    $instance = $normal[$i];

                    echo constructRowForInstance($instance, $position);
                    $position++;
                }
            }

            if (count($bellow) > 0){
                for ($i = 0; $i < count($bellow); $i++){

                    if ($i == 0) echo HtmlHelper::constructRow("Bellow the line");

                    $instance = $bellow[$i];

                    echo constructRowForInstance($instance, $position);
                    $position++;
                }
            }





            ?>
            </tbody>
        </table>

    </div>

<?php
Html::RenderHtmlFooter();

function constructRowForInstance($instance, $position){

    $content = "";
    $content .= "<tr>";
    $content .= "<td>".($position)."</td>";
    $content .= "<td><a href='../clients/view.php?id=".$instance["id"]."'>ID".$instance["id"]." <b>".$instance["name"]."</b></a></td>";

    $changeWrap= HtmlHelper::WrapScoreValueChange($instance["change"]);
    $content .= "<td>".$instance["value"]." (".$changeWrap.")</td>";

    $text = HtmlHelper::WrapScoreValueChange($instance["monthChange"]);
    $content .= "<td>".$text."</td>\n";

    $content .= "</tr>";
    return $content;
}

