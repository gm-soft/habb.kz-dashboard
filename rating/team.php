<?php
require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");


if (isset($_GET["game"]) &&
    ($_GET["game"] == Score::SCORE_DOTA || $_GET["game"] == Score::SCORE_HEARTHSTONE) ){
    $gameName = $_GET["game"];
} else {
    $gameName = Score::SCORE_CSGO;
}

$instances = $_DATABASE->getTeamsForRating($gameName);

$gameTitle = HtmlHelper::getGameTitle($gameName);
$sorting = HtmlHelper::sortInstancesByValue($instances, "teams");
$normal = $sorting["normal"];
$bellow = $sorting["bellow"];

$pageTitle = "Командный рейтинг HABB.KZ";

Html::RenderHtmlHeader($pageTitle);

?>
<div class="container">
    <h1 class="mt-2">Командный рейтинг  <?= $gameTitle ?></h1>

    <?= HtmlHelper::getRatingGameButtons($gameName, "../rating/team.php"); ?>

    <table class="table table-hover">
        <thead>
        <tr>
            <th class="text-primary">Рейтинг</th>
            <th class="text-primary">Название</th>
            <th class="text-primary">Очки</th>

            <th class="text-primary">Капитан</th>

            <th class="text-primary">Игрок</th>
            <th class="text-primary">Игрок</th>
            <th class="text-primary">Игрок</th>
            <th class="text-primary">Игрок</th>
        </tr>

        </thead>
        <tbody>
        <?php
        for ($i = 0; $i < count($instances); $i++){

            if ($i == 0) echo HtmlHelper::constructRow("Premium", 8);
            if ($i == 5) echo HtmlHelper::constructRow("Дивизион 1", 8);
            if ($i == 25) echo HtmlHelper::constructRow("Дивизион 2", 8);
            if ($i == 45) echo HtmlHelper::constructRow("Дивизион 3", 8);
            if ($i == 65) echo HtmlHelper::constructRow("Дивизион 4", 8);
            if ($i == 85) echo HtmlHelper::constructRow("Дивизион 5", 8);

            $instance = $instances[$i];
            $team = $instance["team"];
            $players = $instance["players"];

            $changeWrap= HtmlHelper::WrapScoreValueChange($team["change"]);
            $changeMonth= HtmlHelper::WrapScoreValueChange($team["monthChange"]);
            ?>

            <tr>
                <td><?= ($i+1) ?></td>
                <td><a href='../teams/view.php?id=<?= $team["id"] ?>'><?= $team["name"] ?></a></td>
                <td><?= $team["value"] ?> (<?= $changeWrap ?>)</td>

                <td class="">
                    <?php
                    echo "<b><a href='../clients/view.php?id=".$players[0]["id"]."'>".$players[0]["name"]."</a></b><br>Рейтинг ".$players[0]["value"]."";
                    ?>
                </td>

                <?php
                for ($n = 1; $n < count($players); $n++){
                    if (!is_null($players[$n]["id"])) {
                        echo "<td><a href='../clients/view.php?id=".$players[$n]["id"]."'>".$players[$n]["name"]."</a><br>Рейтинг ".$players[$n]["value"]."</td>";
                    }
                    else {
                        echo "<td>Отсутствует</td>";
                    }
                } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>

<?php
Html::RenderHtmlFooter();
