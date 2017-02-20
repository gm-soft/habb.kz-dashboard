<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$pageTitle = "Динамика изменений очков";

$instances = Statistic::getInstancesFromDatabase($_DATABASE);



Html::RenderHtmlHeader($pageTitle);
    ?>
    <div class="container">
        <p class="mt-2">
            <h1>Статистика (<?= count($instances) ?> записей)</h1>
        </p>

        <div class="card">
            <div class="card-block">
                <h5 class="card-title">Фильтр и сортировка</h5>
                <p class="card-text">

                </p>
            </div>
        </div>


        <div id="outputDiv">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Игра</th>
                    <th>Тип</th>
                    <th>Контент</th>
                    <th>Создан</th>
                </tr>
                </thead>
                <tbody>
                <?php

                for ($i = 0; $i < count($instances); $i++){

                    $value = $instances[$i];
                    ?>
                    <tr>
                        <td><a href="../statistic/view.php?id=<?= $value->id?>" title="Открыть"><?= $value->id ?></a></td>
                        <td><?= $value->game?></td>
                        <td><?= $value->type?></td>
                        <td>Записей: <?= count($value->content) ?></td>
                        <td><?= $value->getCreatedAt()?></td>
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
