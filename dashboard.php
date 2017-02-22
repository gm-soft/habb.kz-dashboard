
<?php
    require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
    //ApplicationHelper::redirect("../clients/");

    $clients = Gamer::getInstancesFromDatabase($_DATABASE);

    $teams = Team::getInstancesFromDatabase($_DATABASE);

    $clientsForDay = [];
    $clientsForWeek = [];

    $teamsForWeek = [];
    $teamsForMonth = [];
    $now = new DateTime();
    foreach ($clients as $client){

        $difference = $now->diff($client->createdAt);
        if ($difference->days <= 1) {
            $clientsForDay[] = $client;
        }

        if ($difference->days <= 7) {
            $clientsForWeek[] = $client;
        }

    }

foreach ($teams as $team){

    $difference = $now->diff($team->createdAt);
    if ($difference->days <= 7) {
        $teamsForWeek[] = $team;
    }

    if ($difference->days <= 31) {
        $teamsForMonth[] = $team;
    }
}

$statistic = Statistic::getInstancesFromDatabase($_DATABASE);


    Html::RenderHtmlHeader();
    ?>

    <div class="container">
        <h1 class="mt-1">Страница администрирования системы Habb.kz</h1>

        <div class="row">

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">Участники</h4>
                        <p class="card-text">
                            Количество аккаунтов: <b><?= count($clients) ?></b><br>
                            Аккаунты за последний день: <b><?= count($clientsForDay) ?></b><br>
                            Аккаунты за неделю: <b><?= count($clientsForWeek) ?></b><br>
                            <a href="/gamers/" class="btn btn-outline-primary float-sm-right">Открыть</a>
                        </p>
                    </div>
                </div>

            </div>

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">Команды</h4>
                        <p class="card-text">
                            Количество команд: <?= count($teams) ?><br>
                            Команды за неделю: <b><?= count($teamsForWeek) ?></b><br>
                            Команды за месяц: <b><?= count($teamsForMonth) ?></b><br>
                            <a href="/teams/" class="btn btn-outline-primary float-sm-right">Открыть</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">Статистика игроков</h4>
                        <p class="card-text">
                            <?= CollectionHelper::constructStatisticFor($statistic, Statistic::CLIENT_TYPE) ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">Статистика команд</h4>
                        <p class="card-text">
                            <?= CollectionHelper::constructStatisticFor($statistic, Statistic::TEAM_TYPE) ?>
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
<?php

html::RenderHtmlFooter();
