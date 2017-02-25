<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 22.02.2017
 * Time: 18:18
 */
abstract class FormSnippets
{
    /**
     * Выводит поля формы для игроков
     * @param null $formData
     * @param string $formAction
     */
    public static function RenderGamerFormFields($formData = null, $formAction = ""){
        ?>
        <form id="form" method="post" action="<?= $formAction ?>">

            <input type="hidden" name="actionPerformed" value="dataInput">

            <div class="row">
                <div class="col-sm-6">

                    <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">Личная информация</h4>
                            <p class="card-text">
                                <?php
                                if (!is_null($formData)){
                                ?>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="id">Habb ID</label>
                                <div class="col-sm-9">
                                    <input type="number" id="id" class="form-control" value="<?= $formData["id"] ?>" disabled>
                                    <input type="hidden" name="id" value="<?= $formData["id"] ?>" >
                                    <input type="hidden" name="client_id" value="<?= $formData["id"] ?>" >
                                </div>
                            </div>
                            <?php
                            }
                            ?>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="name">Имя</label>
                                <div class="col-sm-9">
                                    <input type="text" id="name" name="name" class="form-control" required placeholder="Введите имя (50)" maxlength="50" value="<?= $formData["name"] ?>" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="last_name">Фамилия</label>
                                <div class="col-sm-9">
                                    <input type="text" id="last_name" name="last_name" class="form-control" required placeholder="Введите фамилию (50)" maxlength="50" value="<?= $formData["last_name"] ?>">
                                </div>
                            </div>



                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="birthday">Дата рождения</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" id="birthday" name="birthday" required  value="<?= $formData["birthday"] ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="phone">Телефон</label>
                                <div class="col-sm-9">
                                    <input type="tel" class="form-control" id="phone" name="phone" pattern="^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7}$" required placeholder="Введите мобильный телефон"  value="<?= $formData["phone"] ?>">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="email">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="email" name="email" required placeholder="Введите email" maxlength="50" value="<?= $formData["email"] ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="city">Город</label>
                                <div class="col-sm-9">
                                    <?php SharedSnippets::RenderCitiesSelect($formData["city"]) ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="comment">Коментарий</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="comment" name="comment" placeholder="Максимум 250 символов"  maxlength="250"><?= $formData["comment"] ?></textarea>
                                </div>
                            </div>

                            <?php
                            if (!is_null($formData)){
                                ?>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="lead_id">Лид в Б24</label>
                                    <div class="col-sm-9">
                                        <input type="number" id="lead_id" name="lead_id" class="form-control" maxlength="50" value="<?= $formData["lead_id"] ?>" >
                                    </div>
                                </div>
                                <?php
                            } ?>
                            </p>
                        </div>
                    </div>
                </div>


                <div class="col-sm-6">

                    <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">Соц-сети</h4>
                            <p class="card-text">

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label" for="vk">Профиль vk</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="vk" name="vk" required placeholder="Ссылка на профиль vk" maxlength="40" value="<?= $formData["vk"] ?>">
                                </div>
                            </div>
                            </p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">Статус</h4>
                            <p class="card-text">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="status">Статус</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="status" name="status" required>
                                        <?php
                                        $status = $formData["status"];
                                        ?>
                                        <option value="" disabled <?=$status == "" ? "selected" : "" ?>>Статус</option>
                                        <option value="student" <?=$status == "student" ? "selected" : "" ?>>Студент</option>
                                        <option value="pupil" <?=$status == "pupil" ? "selected" : "" ?>>Школьник</option>
                                        <option value="employee" <?=$status == "employee" ? "selected" : "" ?>>Работаю</option>
                                        <option value="dumbass" <?=$status == "dumbass" ? "selected" : "" ?>>В активном поиске себя</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label" for="institution">Название учреждения</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="institution" name="institution" placeholder="Название учреждения" required maxlength="50" value="<?= $formData["institution"] ?>">
                                </div>
                            </div>
                            </p>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">Игры</h4>
                            <p class="card-text">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="primary_game">Активно играет</label>
                                <div class="col-sm-9">

                                    <?= HtmlHelper::constructGameSelectField("primary_game", "primary_game", $formData["primary_game"], true, false) ?>

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="secondary_games">Другие игры</label>
                                <div class="col-sm-9">
                                    <?= HtmlHelper::constructGameSelectField("secondary_games", "secondary_games", $formData["secondary_games"], true, true) ?>
                                </div>
                            </div>
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <?php SharedSnippets::RenderScoreFormFields($formData)  ?>
            <div class="form-group row">
                <button type="submit" id="submit-btn" class="btn btn-primary">Сохранить</button>

            </div>
        </form>

        <script type="text/javascript">
            $(".select2-multiple").select2({
                placeholder: "Иногда играю (можно выбрать несколько)",
            });

            $(".select2-single").select2({
                placeholder: "Играю активно",
            });

            $('#form').submit(function(){
                $("#submit-btn").prop('disabled',true);
            });
        </script>
    <?php
    }

    /**
     * Выводит поля формы для команды
     * @param null $formData
     * @param Gamer[] $gamers
     * @param string $formAction
     */
    public static function RenderTeamFormFields($formData = null, $gamers, $formAction = ""){
        ?>
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
                                    <input type="number" id="id"  class="form-control" required maxlength="50" value="<?= $formData["id"] ?>" disabled>
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
                                    <?php SharedSnippets::RenderCitiesSelect($formData["city"]) ?>
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

                                    <?php
                                        $fieldName = "captain_id";
                                        SharedSnippets::RenderGamerSelectField($gamers, $fieldName, $fieldName, $formData[$fieldName], true) ;
                                    ?>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="player_2_id">Игрок 2</label>
                                <div class="col-sm-9">
                                    <?php
                                    $fieldName = "player_2_id";
                                    SharedSnippets::RenderGamerSelectField($gamers, $fieldName, $fieldName, $formData[$fieldName]) ;
                                    ?>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="player_3_id">Игрок 3</label>
                                <div class="col-sm-9">
                                    <?php
                                    $fieldName = "player_3_id";
                                    SharedSnippets::RenderGamerSelectField($gamers, $fieldName, $fieldName, $formData[$fieldName]) ;
                                    ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="player_4_id">Игрок 4</label>
                                <div class="col-sm-9">
                                    <?php
                                    $fieldName = "player_4_id";
                                    SharedSnippets::RenderGamerSelectField($gamers, $fieldName, $fieldName, $formData[$fieldName]) ;
                                    ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="player_5_id">Игрок 5</label>
                                <div class="col-sm-9">
                                    <?php
                                    $fieldName = "player_5_id";
                                    SharedSnippets::RenderGamerSelectField($gamers, $fieldName, $fieldName, $formData[$fieldName]) ;
                                    ?>
                                </div>
                            </div>

                            </p>
                        </div>
                    </div>
                </div>

            </div>



            <?php
                SharedSnippets::RenderScoreFormFields($formData);
            ?>

            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Сохранить</button>

            </div>
        </form>

        <script type="text/javascript">

            /*$(".select2-single").select2({
                placeholder: "Выберите участника",
            });*/

            $('#form').submit(function(){
                $("#submit-btn").prop('disabled',true);
            });
        </script>
        <?php
    }

    /**
     * @param BaseInstance $instance
     */
    public static function RenderDeleteFormFields($instance){
        ?>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?= $instance->id ?>">
            <input type="hidden" name="confirmed" value="true">
            <div class="checkbox">
                <label><input type="checkbox" required> Подтвердить удаление</label>
            </div>
            <button type="submit" class="btn btn-danger">Удалить запись</button>
        </form>
        <?php
    }


    /**
     * @param Tournament|null $instance
     * @param string $formAction
     */
    public static function RenderTournamentsFormFields($instance = null, $formAction = ""){
        $formData = !is_null($instance) ? $instance->getAsFormArray() : null;
        $maxParticipantCount = !is_null($formData) ? $formData["participant_max_count"] : 16;
        $participantIds = !is_null($formData) ? ApplicationHelper::joinArray($formData["participant_ids"]) : "";

        $type = isset($formData["type"]) ? $formData["type"] : TournamentTypes::Teams;
        $options = RequestHelper::Get("http://registration.habb.kz/rest/ajax.php", ["action" => "select2.participants.get", "type" => $type]);
        $options = $options["result"];

        ?>
        <form id="form" method="post" action="<?= $formAction ?>">
            <input type="hidden" name="actionPerformed" value="dataInput">
            <?php
            if (isset($formData)){
                ?>
                <div class="form-group">
                    <label for="id">Tournament ID</label>
                    <input type="number" id="id"  class="form-control" required maxlength="50" value="<?= $formData["id"] ?>" disabled>
                    <input type="hidden" name="id" value="<?= $formData["id"] ?>" >
                </div>
                <?php
            } else {
                echo "<input type='hidden' name='id' value='-1' >";
            }
            ?>

            <div class="form-group">
                <label for="name">Название турнира</label>
                <input type="text" class="form-control" maxlength="100" name="name" id="name" required value="<?= $formData["id"] ?>">
                <small>Максимальное кол-во символов: 100</small>
            </div>

            <div class="form-group">
                <label for="description">Публичное описание</label>
                <textarea class="form-control" maxlength="300" name="description" id="description" required><?= $formData["description"] ?></textarea>
                <small>Описание будет отображаться на странице регистрации на турнир. Максимальное кол-во символов: 300</small>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="begin_date">Дата начала</label>
                        <input type="datetime-local" class="form-control" name="begin_date" id="begin_date" required value="<?= $formData["begin_date"] ?>">
                        <small>Дата будет отображаться на странице регистрации на турнир</small>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="reg_close_date">Дата закрытия регистрации</label>
                        <input type="datetime-local" class="form-control" name="reg_close_date" id="reg_close_date" required value="<?= $formData["reg_close_date"] ?>">
                        <small>После этой даты заявки не принимаются, а игрокам будет высвечено соответствующее собщение об этом</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="participant_max_count">Максимальное кол-во участников</label>
                        <input type="number" class="form-control" name="participant_max_count" id="participant_max_count" required value="<?= $maxParticipantCount ?>">
                        <small>Максимальное кол-во символов: 300</small>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="type">Тип турнира</label>
                        <select type="datetime" class="form-control" id="type" name="type" required>
                            <option value="<?= TournamentTypes::Teams ?>"  <?= $formData["type"] == TournamentTypes::Teams ? "selected" : ""?> >
                                Командный
                            </option>
                            <option value="<?= TournamentTypes::Gamers ?>" <?= $formData["type"] == TournamentTypes::Gamers ? "selected" : ""?> >
                                Индивидуальные игроки
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="challonge_tournament_id">ID турнира в Challonge.com</label>
                        <input type="text" class="form-control" name="challonge_tournament_id" id="challonge_tournament_id" value="<?= $formData["challonge_tournament_id"] ?>">
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="game_name">Игра</label>
                        <?php SharedSnippets::RenderGameNameSelect($formData["game_name"], true, "game_name", "game_name"); ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="participants">Список участников</label>
                <select class="form-control" id="participant_ids" name="participant_ids[]" multiple="multiple">
                    <?php
                    foreach ($options as $option){
                        $selected = !is_null($instance) && in_array($option["value"], $instance->participantIdS) ? "selected" : "";
                        $op = "<option value='".$option["value"]."' $selected>".$option["text"]."</option>";
                        echo $op;
                    }
                    ?>
                </select>
                <small id="participants_hint">Можно выбрать <?= $maxParticipantCount ?> участников</small>
            </div>

            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Сохранить</button>
            </div>

            <div class="form-group">
                <label for="comment">Комментарий пользователя</label>
                <textarea class="form-control" maxlength="300" name="comment" id="comment"><?= $formData["comment"] ?></textarea>
                <small>Максимальное кол-во символов: 300</small>
            </div>


        </form>
        <script type="text/javascript">

            var participantSelect = $("#participant_ids");
            participantSelect.select2({
                placeholder: "Для поиска участника начните вводить его имя/название",
                allowClear: true,
                maximumSelectionLength: <?= $maxParticipantCount ?>

            });

            var maxParticipantCountInput = $('#participant_max_count');
            maxParticipantCountInput.on('input', function() {
                var value = parseInt($(this).val());
                participantSelect.select2({
                    maximumSelectionLength : value,
                    placeholder: "Для поиска участника начните вводить его имя/название",
                    allowClear: true
                });
                $('#participants_hint').text("Можно выбрать "+value+" участников");
            });

            var tournamentTypeSelect = $('#type');
            tournamentTypeSelect.on('change', function(){
                var value = $(this).val();
                formHelpers.RequestDataToSelect(participantSelect, value, $(this));
            });


            $('#form').submit(function(){
                $("#submit-btn").prop('disabled',true);
            });
        </script>

        <?php
    }
}




















