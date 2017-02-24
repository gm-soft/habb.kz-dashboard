<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$instances = Tournament::getInstancesFromDatabase($_DATABASE);
$pageTitle = "Список турниров HABB.KZ";

Html::RenderHtmlHeader($pageTitle);
    ?>
    <div class="container">

        <div class="mt-2">
            <h1>Список турниров</h1>
        </div>

        <div class="float-sm-right">
            <a class="btn btn-secondary" href="/tournaments/create.php">Создать новую запись</a>
        </div>

        <div id="outputDiv">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Дата начала</th>
                    <th>Макс. участников</th>
                    <th>Участников всего</th>
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
                        <td><b><a href='/teams/view.php?id=<?= $team["id"] ?>'><?= $team["name"] ?></a></b></td>
                        <td><?= $team["value"] ?> (<?= HtmlHelper::WrapScoreValueChange($team["change"]) ?>)</td>

                        <td class="">
                            <?php
                            echo "<i><a href='/gamers/view.php?id=".$players[0]["id"]."'>".$players[0]["name"]."</a></i><br>Рейтинг ".$players[0]["value"]."";
                            ?>
                        </td>

                        <?php
                        for ($n = 1; $n < count($players); $n++){
                            if (!is_null($players[$n]["id"])) {
                                echo "<td><a href='/gamers/view.php?id=".$players[$n]["id"]."'>".$players[$n]["name"]."</a><br>Рейтинг ".$players[$n]["value"]."</td>";
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
