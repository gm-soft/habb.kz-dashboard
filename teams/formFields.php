<form id="form" method="post" action="<?= $formAction ?>">

    <input type="hidden" name="actionPerformed" value="dataInput">

    <div class="row">
        <div class="col-sm-6">

            <div class="card">
                <div class="card-block">
                    <h4 class="card-title">Информация о команде</h4>
                    <p class="card-text">
                        <?php
                        if (isset($formData)){
                        ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="id">TEAM ID</label>
                            <div class="col-sm-9">
                                <input type="number" id="id"  class="form-control" required maxlength="50" value="<?= $formData["id"] ?>" >
                                <input type="hidden" name="id" value="<?= $formData["id"] ?>" >
                            </div>
                        </div>
                        <?php
                        }
                        ?>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="name">Название</label>
                            <div class="col-sm-9">
                                <input type="text" id="name" name="name" class="form-control"
                                       required placeholder="Введите название (50)" maxlength="50" value="<?= isset($formData) ? $formData["name"] : "" ?>" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="city">Город</label>
                            <div class="col-sm-9">
                                <?= HtmlHelper::constructCitiesSelect($formData["city"]) ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="comment">Коментарий</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="comment" name="comment" placeholder="Максимум 250 символов"  maxlength="250"><?= $formData["comment"] ?></textarea>
                            </div>
                        </div>


                    </p>
                </div>
            </div>
        </div>


        <div class="col-sm-6">

            <div class="card">
                <div class="card-block">
                    <h4 class="card-title">Игроки</h4>
                    <p class="card-text">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="player_2_id">Капитан</label>
                        <div class="col-sm-9">
                            <?= HtmlHelper::constructClientSelectField($clients, "captain_id", "captain_id", $formData, true) ?>
                        </div>
                    </div>


                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="player_2_id">Игрок 2</label>
                            <div class="col-sm-9">
                                <?= HtmlHelper::constructClientSelectField($clients, "player_2_id", "player_2_id", $formData) ?>

                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="player_3_id">Игрок 3</label>
                            <div class="col-sm-9">
                                <?= HtmlHelper::constructClientSelectField($clients, "player_3_id", "player_3_id", $formData) ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="player_4_id">Игрок 4</label>
                            <div class="col-sm-9">
                                <?= HtmlHelper::constructClientSelectField($clients, "player_4_id", "player_4_id", $formData) ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="player_5_id">Игрок 5</label>
                            <div class="col-sm-9">
                                <?= HtmlHelper::constructClientSelectField($clients, "player_5_id", "player_5_id", $formData) ?>
                            </div>
                        </div>

                    </p>
                </div>
            </div>
        </div>

    </div>



    <?php require_once $_SERVER["DOCUMENT_ROOT"]."/shared/scoreEditFields.php"  ?>

		<div class="form-group row">
            <button type="submit" id="submit-btn" class="btn btn-primary">Сохранить</button>
			
		</div>
	</form>

	<script type="text/javascript">

		$(".select2-single").select2({
			placeholder: "Выберите участника",
		});

		$('#form').submit(function(){
	    	$("#submit-btn").prop('disabled',true);
		});
	</script>