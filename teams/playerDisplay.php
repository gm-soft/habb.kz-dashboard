
<?php
    $status = isset($status) ? $status : "Игрок";
    $status = $status == "Капитан" ? "<b>Капитан</b>" : $status;

    if (!is_null($player)){
        $title = $player->name. " ". $player->last_name;
        $content = "<i>$status</i><br><a href='../clients/view.php?id=$player->id'>ID $player->id</a>";
    } else {
        $title  = "Свободная карта";
        $content = "Игрок отсутствует";
    }


?>
<div class="col-sm-2">
    <div class="card">
        <div class="card-block">
            <h4 class="card-title"><?= $title ?></h4>
            <div class="card-text">
                <?= $content ?>

            </div>
        </div>
    </div>
</div>