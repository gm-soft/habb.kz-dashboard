<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("../statistic/");

$instance = Statistic::getInstanceFromDatabase($id, $_DATABASE);


if (is_null($instance)) {
    $_SESSION["errors"] = array("Запись с ID".$id." не найдена в базе данных");
    ApplicationHelper::redirect("../statistic/");
}

$pageTitle = "Статистика от ".$instance->getCreatedAt("d.m.Y");
Html::RenderHtmlHeader($pageTitle);
    ?>
    <div class="container">

        <div class="mt-2">
            <h1>Запись статистики #<?= $instance->id ?> от <?= $instance->getCreatedAt("d.m.Y") ?></h1>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">Информация</h4>
                        <p class="card-text">
                        <dl class="row">
                            <dt class="col-sm-3">ID:</dt>
                            <dd class="col-sm-9"><?= $instance->id ?></dd>
                            <dt class="col-sm-3">Записей:</dt>
                            <dd class="col-sm-9"><?= count($instance->content) ?></dd>
                            <dt class="col-sm-3">Тип:</dt>
                            <dd class="col-sm-9"> <?= $instance->type ?></dd>
                            <dt class="col-sm-3">Игра:</dt>
                            <dd class="col-sm-9"> <?= $instance->game ?></dd>
                            <dt class="col-sm-3">Дата регистрации:</dt>
                            <dd class="col-sm-9"><?= $instance->getCreatedAt()?></dd>
                        </dl>
                        </p>

                    </div>
                    <div class="card-footer">
                        <div class="float-sm-right">
                            <a href="../statistic/"  class="btn btn-secondary"><span class="fa fa-chevron-circle-left"></span> В список</a>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-sm-8">
                <table class='table table-sm'>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Имя</th>
                            <th>Было</th>
                            <th>Стало</th>
                            <th>Разница</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php

                    $array = $instance->content;
                    for ($i = 0; $i < count($array); $i++)  {
                        $item = $array[$i];

                        //$content .= var_export($item, true);
                        $diff = $item["currentValue"] - $item["previousValue"];
                        $row = "<tr>".
                            "<th scope='row'>".($i+1)."</th>".
                            "<td>".$item["name"]."</td>".
                            "<td>".$item["currentValue"]."</td>".
                            "<td>".$item["previousValue"]."</td>".
                            "<td>$diff</td>".
                            "</tr>\n";
                        echo $row;
                    }

                    ?>

                    </tbody>
                </table>
            </div>
        </div>


    </div>

    <?php
Html::RenderHtmlFooter();