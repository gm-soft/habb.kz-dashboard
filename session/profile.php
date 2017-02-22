<?php
require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
//---------------------------------------------

$user = User::getInstanceFromDatabase($_COOKIE["hash"], $_DATABASE, "user_hash");

if (isset($_POST["actionPerformed"])) {
    // Если прислан запрос на изменение пароля
    $currentPass    = FormHelper::ClearInputData($_REQUEST["currentPassword"]);
    $newPass        = FormHelper::ClearInputData($_REQUEST["newPassword"]);
    $confirmPass    = FormHelper::ClearInputData($_REQUEST["confirmPassword"]);

    if (!$user->validatePassword($currentPass)) {
        CookieHelper::AddSessionMessage("Неверно указан текущий пароль", CookieHelper::DANGER);
        ApplicationHelper::redirect("/session/profile.php");
    }

    if ($newPass != $confirmPass) {
        CookieHelper::AddSessionMessage("Пароли не совпадают", CookieHelper::DANGER);
        ApplicationHelper::redirect("/session/profile.php");
    }

    $user->resetPassword($newPass);
    $result = $user->updateInDatabase($_DATABASE);

    $message = "Произошла ошибка при обновлении аккаунта";
    $type = CookieHelper::DANGER;
    if ($result["result"] == true) {
        $message = "Пароль изменен";
        $type = CookieHelper::SUCCESS;
    }

    CookieHelper::AddSessionMessage($message, $type);
    ApplicationHelper::redirect("/session/profile.php");


} else {
    $pageTitle = "Профиль";
    Html::RenderHtmlHeader($pageTitle);
    ?>
    <div class="container">

        <div class="mt-2">
            <h1>Профайл пользователя</h1>
        </div>


        <dl class="row">
            <dt class="col-sm-3">Логин</dt><dd class="col-sm-9"><?= $user->login?></dd>
            <dt class="col-sm-3">Группа</dt><dd class="col-sm-9"><?= $user->getPermissionTitle()?></dd>
            <dt class="col-sm-3">Дата регистрации</dt><dd class="col-sm-9"><?= date("Y-m-d H:i:s", $user->createdAt->getTimestamp())?></dd>
        </dl>

        <hr>
        <div>
            <h3>Изменить пароль</h3>
            <form action="profile.php" method="post">
                <input type="hidden" name="userId" value="<?= $user->id ?>">
                <input type="hidden" name="actionPerformed" value="passwordChange">

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="currentPassword">Прежний пароль</label>
                    <div class="col-sm-10">
                        <input type="password" id="currentPassword" name="currentPassword" class="form-control" placeholder="Введите прежний пароль" required>
                    </div>

                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="newPassword">Новый пароль</label>
                    <div class="col-sm-10">
                        <input type="password" id="newPassword" name="newPassword" class="form-control" placeholder="Введите новый пароль" required>
                    </div>

                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="confirmPassword">Подтверждение пароля</label>
                    <div class="col-sm-10">
                        <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="Введите новый пароль снова" required>
                        <span id='passwordHint'></span>
                    </div>

                </div>

                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" value="" required> Подтвердить изменение
                    </label>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <button type="submit" id="submit-btn" class="btn btn-primary">Сохранить</button>
                    </div>

                </div>

            </form>
        </div>
    </div>

    <script>
        $('#newPassword, #confirmPassword').on('keyup', function () {
            if ($('#newPassword').val() == $('#confirmPassword').val()) {
                $('#passwordHint').html('Пароли совпадают').css('color', 'green');
            } else
                $('#passwordHint').html('Пароли не совпадают').css('color', 'red');
        });

    </script>

    <?php
    Html::RenderHtmlFooter();
}



