<?php
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
<?php }  ?>