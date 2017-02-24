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



            <?php require_once $_SERVER["DOCUMENT_ROOT"]."/shared/scoreEditFields.php"  ?>

            <div class="form-group row">
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
}