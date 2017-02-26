<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("/tournaments/");

$instance = Tournament::getInstanceFromDatabase($id, $_DATABASE);


if (is_null($instance)) {
    CookieHelper::AddSessionMessage("Турнир ID".$_REQUEST["id"]." не найден в базе данных", CookieHelper::DANGER);
    ApplicationHelper::redirect("/tournaments/");
}

$pageTitle = "Турнир ".$instance->name;
Html::RenderHtmlHeader($pageTitle);
ApplicationHelper::debug($instance->getVarExport())
    ?>
    <div class="container">

        <div class="mt-2">
            <h1><?= $instance->name ?> (ID <?= $instance->id ?>)</h1>
        </div>

        <div class="row">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">Информация о турнире</h4>
                        <p class="card-text">
                            <dl class="row">
                                <dt class="col-sm-3">Описание:</dt>
                                <dd class="col-sm-9"><?= $instance->description ?></dd>

                                <dt class="col-sm-3">Комментарий пользователя:</dt>
                                <dd class="col-sm-9"><?= $instance->comment ?></dd>

                                <dt class="col-sm-3">Последняя операция:</dt>
                                <dd class="col-sm-9"> <?= $instance->lastOperation ?></dd>

                                <dt class="col-sm-3">Дата начала:</dt>
                                <dd class="col-sm-9"><?= date("d.m.Y H:i", $instance->beginDate->getTimestamp()) ?></dd>

                                <dt class="col-sm-3">Дата закрытия регистрации:</dt>
                                <dd class="col-sm-9"><?= date("d.m.Y H:i", $instance->registrationCloseDate->getTimestamp()) ?></dd>
                            </dl>
                        </p>

                    </div>
                    <div class="card-footer">
                        <div class="float-sm-right">
                            <a href="/tournaments/"  class="btn btn-secondary"><span class="fa fa-chevron-circle-left"></span> В список</a>
                            <a href="/tournaments/edit.php?id=<?= $instance->id?>"  class="btn btn-secondary"><span class="fa fa-pencil"></span> Редактировать</a>
                            <a href="/tournaments/remove.php?id=<?= $instance->id?>"  class="btn btn-danger"><span class="fa fa-remove"></span> Удалить</a>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-sm-4">
                <div class="card">
                    <div class="card-block">
                        <div class="card-text">
                            <dl class="row">
                                <dt class="col-sm-6">Тип:</dt>
                                <dd class="col-sm-6"><?= $instance->tournamentType ?></dd>

                                <dt class="col-sm-6">Игра:</dt>
                                <dd class="col-sm-6"><?= $instance->gameName ?></dd>

                                <dt class="col-sm-6">Максимум:</dt>
                                <dd class="col-sm-6"> <?= $instance->participantMaxCount ?> участников</dd>

                                <dt class="col-sm-6">Всего:</dt>
                                <dd class="col-sm-6"> <?= $instance->participantCount ?> участников</dd>

                                <dt class="col-sm-6">Обновлена:</dt>
                                <dd class="col-sm-6"><?= $instance->getUpdatedAtString() ?></dd>

                                <dt class="col-sm-6">Создана:</dt>
                                <dd class="col-sm-6"><?= $instance->getCreatedAtString() ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="pb-1">
            <?php FormSnippets::RenderTournamentParticipants($instance); ?>
        </div>

    </div>

    <?php
Html::RenderHtmlFooter();