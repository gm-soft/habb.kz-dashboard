<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$pageTitle = "Список игроков";

//$instances = Client::getClientsFromDatabase($_DATABASE);

$instances = Gamer::filterInstancesFromDatabase($_DATABASE, [], null, true);



Html::RenderHtmlHeader($pageTitle);
    ?>
    <div class="container">
        <div class="mt-2  ">
            <h1>Список игроков (<?= count($instances) ?> записей)</h1>
        </div>
        <p>
        <table class="table table-striped datatable">
            <thead>
            <tr>
                <th>ID</th>
                <th>Полное имя</th>
                <th>Телефон</th>
                <th>Email</th>
                <th>Возраст</th>
                <th>VK</th>
                <th>Игра</th>
                <th>Регистрация</th>
            </tr>
            </thead>
            <tbody>
            <?php

            for ($i = 0; $i < count($instances); $i++){

                $value = $instances[$i];
                ?>
                <tr>
                    <td><?= $value->id ?></td>
                    <td><a href="/gamers/view.php?id=<?= $value->id?>" title="Открыть"><?= $value->getFullName()?></a></td>
                    <td><?= $value->phone?></td>
                    <td><?= $value->email?></td>

                    <td><?= date("d.m.Y", $value->birthday->getTimestamp())?> (<?= $value->getAge() ?> лет)</td>
                    <td><?= $value->vk?></td>
                    <td><?= $value->primaryGame?></td>
                    <td><?= date("d.m.Y H:i:s", $value->createdAt->getTimestamp())?></td>
                </tr>

                <?php
            }
            ?>

            </tbody>

        </table>
        </p>
    </div>
    <?php

Html::RenderHtmlFooter();
