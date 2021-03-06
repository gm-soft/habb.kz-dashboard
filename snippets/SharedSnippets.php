<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 22.02.2017
 * Time: 18:18
 */
abstract class SharedSnippets
{
    public static function RenderScoreFormFields($formData){
        if (isset($formData)){
            ?>
            <hr>
            <h3>Очки</h3>
            <div class="row">
                <?php
                $scoreArray = $formData["score_array"];
                foreach ($scoreArray as $item){

                    $item = $item->getAsArray();
                    ?>
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-block">
                                <h4 class="card-title"><?= $item["game_name"] ?></h4>
                                <p class="card-text">
                                    <input type="hidden" id="score_id" name="score_id[]" value="<?= $item["id"] ?>" >
                                    <input type="hidden" id="game_name" name="game_name[]" value="<?= $item["game_name"] ?>" >

                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label" for="total_value">Общее кол-во очков</label>
                                    <div class="col-sm-6">
                                        <input type="number" id="total_value" name="total_value[]" class="form-control" required maxlength="50" value="<?= $item["total_value"] ?>" >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label" for="month_value">На начало месяца</label>
                                    <div class="col-sm-6">
                                        <input type="number" id="month_value" name="month_value[]" class="form-control" required maxlength="50" value="<?= $item["month_value"] ?>" >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label" for="change_total">Последнее изменение</label>
                                    <div class="col-sm-6">
                                        <input type="number" id="change_total" name="change_total[]" class="form-control" required maxlength="50" value="<?= $item["change_total"] ?>" >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label" for="change_month">Изменение за месяц</label>
                                    <div class="col-sm-6">
                                        <input type="number" id="change_month" name="change_month[]" class="form-control" required maxlength="50" value="<?= $item["change_month"] ?>" >
                                    </div>
                                </div>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php
        }
    }

    /**
     * Рендерит поля для заполнения очков на странице просмотра
     *
     * @param Score[] $scoreArray
     * @param bool|null $teamActionPage
     */
    public static function RenderFastScoreFields($scoreArray, $teamActionPage = null){
        //$scoreArray = $instance->scoreArray;
        $actionPage = !is_null($teamActionPage) && $teamActionPage == true ? "/teams/edit.php" : "/gamers/edit.php";

        for ($i = 0; $i < count($scoreArray); $i++){
            $item = $scoreArray[$i];

            ?>
            <div class="card">
                <div class="card-block">
                    <h4 class="card-title">Очки "<?= $item->gameName ?>"</h4>
                    <div class="card-text">
                        <p>
                        <form class="from" method="post" action="<?= $actionPage ?>" >
                            <input type="hidden" name="actionPerformed" value="scoreInput">
                            <input type="hidden" name="scoreId" value="<?= $item->id ?>">
                            <input type="hidden" name="clientId" value="<?= $item->clientId ?>">
                            <input type="hidden" name="gameName" value="<?= $item->gameName ?>">
                            <input type="hidden" name="currentScore" value="<?= $item->value ?>">


                            <div class="form-group">
                                <label class="col-sm-3" for="scoreAddition"><span class="scoreValue" id="scoreValue<?=$i?>"><?= $item->value ?> </span></label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button id="scoreMinus<?=$i ?>" class="btn btn-danger" type="button"><i class="fa fa-minus"></i></button>
                                                </span>
                                        <span class="input-group-btn">
                                                    <button id="scorePlus<?=$i ?>" class="btn btn-success" type="button"><i class="fa fa-plus"></i></button>
                                                </span>
                                        <input type="number" class="form-control text-sm-center" id="scoreAddition<?=$i ?>" name="scoreAddition" value="0" placeholder="Введите значение" required>

                                        <span class="input-group-btn">
                                                    <button type="submit" id="submit-btn" class="btn btn-primary submit-btn">Сохранить</button>
                                                </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>
                        </p>

                    </div>
                </div>
            </div>

            <script>
                $('#scoreMinus<?=$i ?>').on("click", function(){
                    setValues<?=$i ?>(-1);
                });


                $('#scorePlus<?=$i ?>').on("click", function(){
                    setValues<?=$i ?>(1);
                });

                $('#scoreAddition<?=$i ?>').keyup(function(){
                    setValues<?=$i ?>();
                });

                function setValues<?=$i ?>(additionPoint){

                    additionPoint = typeof additionPoint !== 'undefined' ? additionPoint : 0;
                    var source = $('#scoreAddition<?=$i ?>').val();
                    if (source == "" && source != "-") {
                        source = 0;
                        $('#scoreValue<?=$i ?>').text(scores);
                    }
                    var addValue = parseInt(source) + additionPoint;

                    var result = <?=$item->value ?> + addValue;
                    $('#scoreValue<?=$i ?>').text(result);
                    $('#scoreAddition<?=$i ?>').val(addValue);
                }
            </script>

        <?php }  ?>


        <script>

            $('.form').submit(function(){
                submitBtn.prop('disabled', true);
            });
        </script>
        <?php
    }

    /**
     * Выводит карточку для игрока команды
     * @param Gamer $player
     * @param null $status
     * @param int $col Ширина карточки col-sm-
     */
    public static function RenderGamerDisplayCard($player, $status = null, $col = 2){
        $status = !is_null($status) ? $status : "Игрок";
        $status = $status == "Капитан" ? "<b>Капитан</b>" : $status;

        if (!is_null($player)){
            $title = $player->name. " ". $player->last_name;
            $content = "<i>$status</i><br><a href='../gamers/view.php?id=$player->id'>ID $player->id</a>";
        } else {
            $title  = "Свободная карта";
            $content = "Игрок отсутствует";
        }


        ?>
        <div class="col-sm-<?= $col ?>">
            <div class="card">
                <div class="card-block">
                    <h4 class="card-title"><?= $title ?></h4>
                    <div class="card-text">
                        <?= $content ?>

                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Выводит таблицу игроков команды
     * @param Gamer[] $gamers
     */
    public static function RenderTeamGamerTable($gamers){
        $gameName = Score::SCORE_CSGO;
        ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Позиция</th>
                    <th>Имя</th>
                    <th>Очки (<?= $gameName ?>)</th>
                    <th>Ссылка</th>
                </tr>
            </thead>
            <tbody>
            <?php
            for ($i = 0; $i < count($gamers); $i++) {
                $gamer = $gamers[$i];
                $id = $gamer->id;
                $status = $i == 0 ? "<i>Капитан</i>" : "Игрок ".($i+1);
                $name = "ID".$id." <b>".$gamer->getFullName()."</b>";
                $scoreObject = $gamer->getScoresByGame();
                $score = $scoreObject->value;
                echo "<tr><td>$status</td><td>$name</td><td>$score</td><td><a href='/gamers/view.php?id=$id'>Открыть</a></td></tr>";
            }
            ?>
            </tbody>
        </table>

        <?php
    }

    /**
     * Выводит блок фильтрации таблицы
     * @param string $currentFilename - имя файла, на который будет отправлен запрос
     */
    public static function RenderFilterIndexCard($currentFilename){
        ?>

        <div class="card">
            <div class="card-block">
                <h5 class="card-title">Фильтр и сортировка</h5>
                <div class="card-text">
                    <form class="form-inline" method="get" action="<?=$currentFilename ?>">

                        <div class="form-group mb-2 mr-sm-2 mb-sm-0">
                            <label class="form-control-label" for="sortByField">Сортировка по</label><br>
                            <select class="form-control" id="sortByField" name="sortByField" >
                                <option value="id">ID объекта</option>
                                <option value="id-desc">ID объекта (обратная)</option>
                                <option value="registration">Дата регистрации</option>
                                <option value="registration-desc">Дата регистрации (обратная)</option>
                            </select>
                        </div>

                        <div class="form-group mb-2 mr-sm-2 mb-sm-0">
                            <label class="form-control-label" for="filterByCity">Город</label><br>
                            <?= HtmlHelper::constructCitiesSelect(null, false, "filterByCity", "filterByCity", true) ?>
                        </div>




                    </form>
                </div>
            </div>
        </div>
        <?php
    }
}