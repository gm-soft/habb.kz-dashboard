<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$title = "Список команд";

$instances = $_DATABASE->getTeamsForRating();
$pageTitle = "Список команд HABB.KZ";

Html::RenderHtmlHeader($pageTitle);
    ?>
    <div class="container">

        <div class="mt-2">
            <h1>Список команд</h1>
        </div>

        <div class="float-sm-right">
            <a class="btn btn-secondary" href="/teams/create.php">Создать новую запись</a>
        </div>

        <div id="outputDiv">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Очки</th>
                    <th>Капитан</th>
                    <th>Игрок 2</th>
                    <th>Игрок 3</th>
                    <th>Игрок 4</th>
                    <th>Игрок 5</th>
                </tr>
                </thead>
                <tbody>
                <?php

                for ($i = 0; $i < count($instances); $i++){

                    $instance = $instances[$i];
                    $team = $instance["team"];
                    $players = $instance["players"];

                    ?>

                    <tr>
                        <td><?= $team["id"] ?></td>
                        <td><a href='/teams/view.php?id=<?= $team["id"] ?>'><?= $team["name"] ?></a></td>
                        <td><?= $team["value"] ?> (<?= HtmlHelper::WrapScoreValueChange($team["change"]) ?>)</td>

                        <td class="">
                            <?php
                            echo "<b><a href='/clients/view.php?id=".$players[0]["id"]."'>".$players[0]["name"]."</a></b><br>Рейтинг ".$players[0]["value"]."";
                            ?>
                        </td>

                        <?php
                        for ($n = 1; $n < count($players); $n++){
                            if (!is_null($players[$n]["id"])) {
                                echo "<td><a href='/clients/view.php?id=".$players[$n]["id"]."'>".$players[$n]["name"]."</a><br>Рейтинг ".$players[$n]["value"]."</td>";
                            }
                            else {
                                echo "<td>Отсутствует</td>";
                            }
                        } ?>
                    </tr>

                    <?php
                }
                ?>

                </tbody>

            </table>
        </div>
    </div>
    <?php

Html::RenderHtmlFooter();
