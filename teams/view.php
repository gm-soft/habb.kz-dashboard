<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("../teams/");

$instance = Team::getInstanceFromDatabase($id, $_DATABASE);


if (is_null($instance)) {
    CookieHelper::AddSessionMessage("Команда с ID".$_REQUEST["id"]." не найдена в базе данных", CookieHelper::DANGER);
    ApplicationHelper::redirect("../teams/");
}

$captain = Gamer::getInstanceFromDatabase($instance->captain_id, $_DATABASE);

$player2 = Gamer::getInstanceFromDatabase($instance->player_2_id, $_DATABASE);
$player3 = Gamer::getInstanceFromDatabase($instance->player_3_id, $_DATABASE);
$player4 = Gamer::getInstanceFromDatabase($instance->player_4_id, $_DATABASE);
$player5 = Gamer::getInstanceFromDatabase($instance->player_5_id, $_DATABASE);

$pageTitle = "HABB.KZ - ".$instance->name;
Html::RenderHtmlHeader($pageTitle);
    ?>
    <div class="container">

        <div class="mt-2">
            <h1><?= $instance->name ?> (ID <?= $instance->id ?>)</h1>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">Информация о команде</h4>
                        <p class="card-text">
                            <dl class="row">
                                <dt class="col-sm-3">ID:</dt>
                                <dd class="col-sm-9"><?= $instance->id ?></dd>
                                <dt class="col-sm-3">Город:</dt>
                                <dd class="col-sm-9"><?= $instance->city ?></dd>
                                <dt class="col-sm-3">Последняя операция:</dt>
                                <dd class="col-sm-9"> <?= $instance->last_operation ?></dd>
                                <dt class="col-sm-3">Обновлена:</dt>
                                <dd class="col-sm-9"><?= date("d.m.Y H:i:s", $instance->updatedAt->getTimestamp()) ?></dd>
                                <dt class="col-sm-3">Создана:</dt>
                                <dd class="col-sm-9"><?= date("d.m.Y H:i:s", $instance->createdAt->getTimestamp()) ?></dd>
                                <dt class="col-sm-3">Комментарий:</dt>
                                <dd class="col-sm-9"><?= $instance->comment ?></dd>
                            </dl>
                        </p>

                    </div>
                    <div class="card-footer">
                        <div class="float-sm-right">
                            <a href="../teams/"  class="btn btn-secondary"><span class="fa fa-chevron-circle-left"></span> В список</a>
                            <a href="../teams/edit.php?id=<?= $instance->id?>"  class="btn btn-secondary"><span class="fa fa-pencil"></span> Редактировать</a>
                            <a href="../teams/remove.php?id=<?= $instance->id?>"  class="btn btn-danger"><span class="fa fa-remove"></span> Удалить</a>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-sm-6">
                <?php
                    $teamActionPage = true;
                    SharedSnippets::RenderFastScoreFields($instance->scoreArray, $teamActionPage);
                ?>
            </div>

        </div>

        <div class="row">

            <?php
            SharedSnippets::RenderTeamGamerTable([$captain, $player2, $player3, $player4, $player5]);

            ?>

        </div>


    </div>

    <?php
Html::RenderHtmlFooter();