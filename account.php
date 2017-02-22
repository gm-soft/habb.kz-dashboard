<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

if (isset($_POST["processed"]) && (
        !empty($_REQUEST["name"]) &&
        !empty($_REQUEST["last_name"]) &&
        !empty($_REQUEST["phone"]) &&
        !empty($_REQUEST["email"]) )) {

    $_REQUEST["action"] = "account.create";
    $createResponse = RequestHelper::query("http://registration.habb.kz/rest/account.php", $_REQUEST);
    if ($createResponse["result"] == true) {

        $name = $createResponse["name"];
        $id = $createResponse["id"];
        $email = $createResponse["email"];
        $content = "<h1 class='text-sm-center'>Регистрация на habb.kz</h1>\n".
                    "<p>\n".
                        "$name, Вы успешно зарегистрированы на системе HABB.KZ!<br>\n".
                        "Ваш HABB ID: <b>$id</b><br>\n".
                        "Полная информация о регистрации выслана на Ваш email <b>$email</b></br>\n".
                    "</p>\n";
    } else {
        $errorMessage = $createResponse["message"];
        $content = "<h1 class='text-center'>Ошибка регистрации</h1>".
                    "<p>$errorMessage</p>\n";
    }
    Html::RenderHtmlHeader("Регистрация участника HABB.KZ", false, Html::HTML_FRONT);
    ?>

    <div class="row margin-50">
        <div class="col-sm-6 offset-sm-3">
            <div class="form-container process-block">
                <?= $content ?>
            </div>
        </div>
    </div>

    <?php
    Html::RenderHtmlFooter(false, Html::HTML_FRONT);
}
else
{
    $iOsDevice = CookieHelper::IsIosDevice();
    $cities = ApplicationHelper::getCities();

    Html::RenderHtmlHeader("Регистрация участника HABB.KZ", false, Html::HTML_FRONT);

    ?>

    <div class="container">
        <div class="row">
            <div class="">
                <div class="form-container">
                    <div class="text-sm-center">
                        <h1 class="mt-1">Регистрация</h1>
                    </div>


                    <form id="form" method="post" action="account.php">
                        <input type="hidden" name="processed" value="true"/>

                        <div class="row">
                            <div class="col-sm-6">

                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" id="name" name="name" class="form-control" required placeholder="Имя" maxlength="50" >
                                        <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">

                                        <input type="text" id="last_name" name="last_name" class="form-control" required placeholder="Фамилия" maxlength="50">
                                        <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <?php
                                        if ($iOsDevice) {
                                            ?>
                                            <input type="date" class="form-control" id="birthday" name="birthday" required placeholder="Дата рождения">
                                            <span class="input-group-addon">Дата рождения <i class="fa fa-calendar" aria-hidden="true"></i></span>
                                            <?php
                                        } else {
                                            ?>
                                            <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control" id="birthday" name="birthday" required placeholder="Дата рождения">
                                            <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="input-group">
                                        <select class="form-control" name="city" required>
                                            <option value="" disabled selected>Город</option>
                                            <?php

                                            for ($i = 0; $i<count($cities); $i++) {
                                                ?>
                                                <option value='<?= $cities[$i] ?>'><?= $cities[$i] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="input-group-addon"><i class="fa fa-building" aria-hidden="true"></i></span>

                                    </div>
                                </div>

                            </div>

                            <div class="col-sm-6">
                                <div id="divPhone" class="form-group">
                                    <div class="input-group">
                                        <input type="tel" class="form-control" id="phone" name="phone" pattern="^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7}$" required placeholder="Мобильный телефон">
                                        <span class="input-group-addon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
                                    </div>
                                </div>

                                <div id="divEmail" class="form-group">
                                    <div class="input-group">
                                        <input type="email" class="form-control" id="email" name="email" pattern="^([A-Za-z0-9_\.-]+)@([A-Za-z0-9_\.-]+)\.([a-z\.]{2,10})$" required placeholder="yourname@example.com" maxlength="50">
                                        <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="input-group">

                                        <input type="text" class="form-control" id="vk" name="vk" pattern="^(https:\/\/)?(vk\.com)([\/\w \.-]{1,50})*\/?$" required placeholder="https://vk.com/" maxlength="40">
                                        <span class="input-group-addon"><i class="fa fa-vk" aria-hidden="true"></i></span>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="">
                            <h4 class="text-sm-center">Статус</h4>
                        </div>
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <select class="form-control" name="status" required>
                                            <option value="" disabled selected>Статус</option>
                                            <option value="student">Студент</option>
                                            <option value="pupil">Школьник</option>
                                            <option value="employee">Работаю</option>
                                            <option value="dumbass">В активном поиске себя</option>
                                        </select>
                                        <span class="input-group-addon"><i class="fa fa-users" aria-hidden="true"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group ">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="institution" name="institution" placeholder="Название учреждения" required maxlength="50">
                                        <span class="input-group-addon"><i class="fa fa-university" aria-hidden="true"></i></span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="text-sm-center">
                            <h4>Я играю</h4>
                        </div>

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <select class="form-control select2 select2-single" name="primary_game" required>
                                            <option value="" selected disabled>Играю активно</option>
                                            <option value="dota">Dota</option>
                                            <option value="cs:go">CS:GO</option>
                                            <option value="lol">League of Legends</option>
                                            <option value="hearthstone">Hearthstone</option>
                                            <option value="wot">World of Tanks</option>
                                            <option value="overwatch">Overwatch</option>
                                            <option value="cod">Call of Duty (серия игр)</option>
                                        </select>
                                        <span class="input-group-addon"><i class="fa fa-gamepad" aria-hidden="true"></i></span>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <select class="form-control select2 select2-multiple" name="secondary_games[]" required multiple="multiple" >
                                            <option value="dota">Dota</option>
                                            <option value="cs:go">CS:GO</option>
                                            <option value="lol">League of Legends</option>
                                            <option value="hearthstone">Hearthstone</option>
                                            <option value="wot">World of Tanks</option>
                                            <option value="overwatch">Overwatch</option>
                                            <option value="cod">Call of Duty (серия игр)</option>
                                        </select>
                                        <span class="input-group-addon"><i class="fa fa-gamepad" aria-hidden="true"></i></span>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" id="inqured" name="inqured" required> Ознакомлен с <a href="#" data-toggle="modal" data-target="#exampleModalLong">условиями</a> и даю согласие на обработку моих данных</label>
                            </div>

                        </div>

                        <div class="form-group">
                            <button type="submit" id="submit-btn" class="btn btn-primary btn-block">Отправить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>




        <!-- Modal -->
        <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Согласие на действия с персональными данными</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Я принимаю решение о предоставлении моих персональных данных и даю согласие на действия с моими персональными данными свободно, своей волей и в своем интересе.
                        </p>

                        <p>
                            Наименование и e-mail адрес, получающего согласие субъекта персональных данных: «Киберспортивная Организация HABB»,
                            со следующей целью сбора и обработки персональных данных: рассылка сообщений на e-mail.
                        </p>

                        <p>
                            Перечень персональных данных, на сбор и обработку которых дается согласие субъекта персональных данных:
                            фамилия, имя; дата рождения; номер контактного телефона; электронный адрес; Steam аккаунт; ссылка на профиль ВК; пол; статус; предпочитаемые дисциплины.
                            Перечень действий с персональными данными, на совершение которых дается согласие: сбор, систематизация,
                            накопление, хранение, уточнение (обновление, изменение, дополнение), использование, распространение, передача, обезличивание, блокирование,
                            уничтожение персональных данных.
                        </p>

                        <p>
                            Срок, в течение которого действует согласие субъекта персональных данных,
                            если иное не установлено законодательством РК составляет – 5 лет с
                            даты предоставления персональных данных. На основании письменного обращения
                            субъекта персональных данных с требованием о прекращении обработки его персональных данных оператор прекратит
                            обработку таких персональных данных в течение 24 (двадцати четырех) часов. В порядке предусмотренном действующим
                            законодательством Республики Казахстан, согласие может быть отозвано субъектом персональных данных путем письменного
                            обращения к оператору, получающему согласие субъекта персональных данных.
                        </p>

                        <p>
                            Я согласен (на) с тем, что по моему письменному требованию все уведомления о персональных данных
                            будут вручаться мне (моему представителю) по месту нахождения подразделения.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="modalConfirmButton" class="btn btn-primary" data-dismiss="modal">Согласен</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="accountModalTitle">Обнаружено совпадение данных</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <!--span aria-hidden="true">&times;</span-->
                        </button>
                    </div>
                    <div id="accountModalBody" class="modal-body">
                        <p>Скорее всего, у Вас уже есть HABB ID. Чтобы его узнать, обратитесь к администрации HABB.KZ <a href="https://vk.com/habbkz">https://vk.com/habbkz</a></p>
                        <p class="text-sm-center">
                            <a href="https://vk.com/habbkz" class=""></a>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <a href="https://vk.com/habbkz" class="btn btn-primary" >Перейти</a>
                        <!--button type="button" class="btn btn-primary" data-dismiss="modal">Перейти</button-->
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php
    Html::RenderHtmlFooter(false, Html::HTML_FRONT);
}


