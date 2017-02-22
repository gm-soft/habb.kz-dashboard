<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$pageTitle = "Список игроков";

//$instances = Client::getClientsFromDatabase($_DATABASE);

$instances = Gamer::filterInstancesFromDatabase($_DATABASE, [], null, true);



Html::RenderHtmlHeader($pageTitle);
    ?>
    <div class="container">
        <p class="mt-2">
            <h1>Список зарегистрировавшихся (<?= count($instances) ?> записей)</h1>
        </p>

        <div class="card">
            <div class="card-block">
                <h5 class="card-title">Фильтр и сортировка</h5>
                <p class="card-text">

                </p>
            </div>
        </div>


        <div id="outputDiv">
            <table class="table table-striped table-responsive">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Полное имя</th>
                    <th>Телефон</th>
                    <th>Email</th>
                    <th>Возраст</th>
                    <th>VK</th>
                    <th>Игры 2</th>
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
                        <td><a href="../gamers/view.php?id=<?= $value->id?>" title="Открыть"><?= $value->getFullName()?></a></td>
                        <td><?= $value->phone?></td>
                        <td><?= $value->email?></td>

                        <td><?= date("d.m.Y", $value->birthday->getTimestamp())?> (<?= $value->getAge() ?> лет)</td>
                        <td><?= $value->vk?></td>
                        <td><?= $value->getSecondaryGamesString()?></td>
                        <td><?= date("d.m.Y H:i:s", $value->createdAt->getTimestamp())?></td>
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
