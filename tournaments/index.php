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

        <div class="float-sm-right mb-1 mt-1">
            <a class="btn btn-secondary" href="/tournaments/create.php">Создать новую запись</a>
        </div>

        <table class="table table-striped dataTable">
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Начало</th>
                <th>Тип</th>
                <th>Дисциплина</th>
                <th>Макс. участников</th>
                <th>Участников всего</th>
            </tr>
            </thead>
            <tbody>
            <?php

            for ($i = 0; $i < count($instances); $i++){

                $instance = $instances[$i];
                ?>

                <tr>
                    <td><?= $instance->id ?></td>
                    <td><b><a href='/tournaments/view.php?id=<?= $instance->id ?>'><?= $instance->name ?></a></b></td>
                    <td><?= date("H:i d-m-Y", $instance->beginDate->getTimestamp()) ?></td>
                    <td><?= $instance->tournamentType ?></td>
                    <td><?= $instance->gameName ?></td>
                    <td><?= $instance->participantMaxCount ?></td>
                    <td><?= $instance->participantCount ?></td>
                </tr>

                <?php
            }
            ?>

            </tbody>

        </table>
    </div>
    <?php

Html::RenderHtmlFooter();
