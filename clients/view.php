<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("/");

$instance = Client::getFromDatabase($_DATABASE, $id);


if (is_null($instance)) {
    CookieHelper::AddSessionMessage("Клиент с ID".$_REQUEST["id"]." не найден в базе данных", CookieHelper::DANGER);
    ApplicationHelper::redirect("/");
}


$teams = $instance->getTeamsForClient($_DATABASE, Score::SCORE_CSGO);
$pageTitle = "HABB.KZ - ".$instance->getFullName();
Html::RenderHtmlHeader($pageTitle);
    ?>
    <div class="container">

        <div class="mt-2">
            <h1><?= $instance->getFullName() ?> (Habb ID <?= $instance->id ?>)</h1>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">Личная информация</h4>
                        <p class="card-text">
                        <dl class="row">
                            <dt class="col-sm-3">ID:</dt>
                            <dd class="col-sm-9"><?= $instance->id ?></dd>
                            <dt class="col-sm-3">День рождения:</dt>
                            <dd class="col-sm-9"><?= date("d.m.Y", $instance->birthday->getTimestamp()) ?> (<?= $instance->getAge() ?> лет)</dd>
                            <dt class="col-sm-3">Телефон:</dt>
                            <dd class="col-sm-9"> <?= $instance->phone ?></dd>
                            <dt class="col-sm-3">Email:</dt>
                            <dd class="col-sm-9"><?= $instance->email ?></dd>
                            <dt class="col-sm-3">Город:</dt>
                            <dd class="col-sm-9"><?= $instance->city ?></dd>
                        </dl>
                        <hr>
                        <dl class="row">
                            <dt class="col-sm-3">Профиль vk:</dt>
                            <dd class="col-sm-9"><?= $instance->vk ?></dd>
                            <dt class="col-sm-3">Статус:</dt>
                            <dd class="col-sm-9"><?= $instance->status ?></dd>
                            <dt class="col-sm-3">Учреждение:</dt>
                            <dd class="col-sm-9"><?= $instance->institution ?></dd>
                            <dt class="col-sm-3">Играет активно:</dt>
                            <dd class="col-sm-9"><?= $instance->primary_game ?></dd>
                            <dt class="col-sm-3">Играет второстепенно:</dt>
                            <dd class="col-sm-9"><?= $instance->getSecondaryGamesString() ?></dd>
                            <dt class="col-sm-3">ID Лида в Б24:</dt>
                            <dd class="col-sm-9"><a href="https://habb1.bitrix24.kz/crm/lead/show/<?= $instance->lead_id?>/" title="Открыть в Битрикс24">ID<?= $instance->lead_id?></a></dd>
                            <dt class="col-sm-3">Дата регистрации:</dt>
                            <dd class="col-sm-9"><?= date("d.m.Y H:i:s", $instance->created_at->getTimestamp())?></dd>
                            <dt class="col-sm-3">Комментарий:</dt>
                            <dd class="col-sm-9"><?= $instance->comment ?></dd>
                        </dl>
                        </p>

                    </div>
                    <div class="card-footer">
                        <div class="float-sm-right">
                            <a href="../clients/"  class="btn btn-secondary"><span class="fa fa-chevron-circle-left"></span> В список</a>
                            <a href="../clients/edit.php?id=<?= $instance->id?>"  class="btn btn-secondary"><span class="fa fa-pencil"></span> Редактировать</a>
                            <a href="../clients/remove.php?id=<?= $instance->id?>"  class="btn btn-danger"><span class="fa fa-remove"></span> Удалить</a>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-sm-6">
                <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/shared/scoreFields.php" ?>
            </div>
        </div>

        <div class="card">
            <div class="card-block">
                <h3 class="card-title">Команды игрока</h3>
                <p class="card-text">
                    <?php
                    if (count($teams) > 0){
                    ?>
                <table class="table table-hover">
                    <thead>
                        <tr><th>ID</th><th>Название</th><th>Очки</th></tr>

                    </thead>
                    <tbody>
                    <?php
                    foreach ($teams as $team){
                        echo "<tr>";

                        echo "<td>".$team["id"]."</td><td><a href='../teams/view.php?id=".$team["id"]."'><b>".$team["name"]."</b></a></td>";
                        $scoreChangeValue = intval($team["change"]);
                        $class = $scoreChangeValue >= 0 ? "text-success" : "text-danger";
                        $textChanged = $scoreChangeValue >= 0 ? "+".$team["change"] : $team["change"];

                        echo "<td><b>".$team["value"]."</b> (<span class='$class'>$textChanged</span>)</td>";
                        echo "</tr>";

                    }
                    ?>
                    </tbody>
                </table>

                <?php
                } else {
                    ?>
                    <h4>Игрок не участвует ни в одной команде</h4>
                <?php } ?>


                </p>
            </div>
        </div>


    </div>

    <?php
Html::RenderHtmlFooter();