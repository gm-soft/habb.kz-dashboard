<?php
require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$result = false;
$action = $_REQUEST["action"];
$response = array(
    "result" => $result,
    "action" => $action
);
$ip = $_SERVER['REMOTE_ADDR'];
ApplicationHelper::logEvent("account.php: \$_REQUEST[\"action\"]=".$action);

switch ($action) {

    case "account.create":
        $_REQUEST["name"] =         FormHelper::ClearInputData($_REQUEST["name"]);
        $_REQUEST["last_name"] =    FormHelper::ClearInputData($_REQUEST["last_name"]);
        $_REQUEST["phone"] =        ApplicationHelper::formatPhone($_REQUEST["phone"]);
        $_REQUEST["email"] =        FormHelper::ClearInputData($_REQUEST["email"]);
        //$_REQUEST["steam"] =        ApplicationHelper::ClearInputData($_REQUEST["steam"]);
        $_REQUEST["vk"] =           FormHelper::ClearInputData($_REQUEST["vk"]);
        $_REQUEST["institution"] =  FormHelper::ClearInputData($_REQUEST["institution"]);
        $_REQUEST["secondary_games"] = join(", ", $_REQUEST["secondary_games"]);

        //-------------------------------
        $client = Gamer::fromRequest($_REQUEST);

        $added_result = $client->insertToDatabase($_DATABASE);
        //$added_result = $mysql->addClient($client);

        if ($added_result["result"] == true){
            $response["result"] = true;
            $client->id = $added_result["data"];

            $email_subject = "Регистрация на HABB.KZ";
            $email_message = constructEmailBody($client->getFullName(), $client->id);
            $email_result = sendEmail($email_subject, $email_message);

            $addResult = BitrixHelper::addLead($client);
            if (!is_null($addResult) && isset($addResult["result"])) {
                $client->lead_id = $addResult["result"];
                $_DATABASE->updateLeadId($client);
            }
            //$addToGoogleResult = addToTable($client);

            $response["name"] = $client->getFullName();
            $response["id"] = $client->id;
            $response["email"] = $client->email;

        } else {
            $response["result"] = false;
            $error_desc = $added_result["data"];
            $error_to_show = "Возникла ошибка при регистрации. Попробуйте позже или свяжитесь с администрацией habb.kz";
            if (strpos( $error_desc, 'Duplicate' ) !== false) {

                $duplicated_field = "телефоном (".$_REQUEST["phone"].")";
                if (strpos($error_desc, "\\'email\\'") !== false) $duplicated_field = "электронным адресом (".$_REQUEST["email"].")";

                $error_to_show = "Извините но участник с указанным ".$duplicated_field." уже существует. Свяжитесь с администрацией habb.kz для восстановления своего HABB ID";
            }
            $response["message"] = $error_to_show;

        }
        break;

    case "month.score.init":

        $now = new DateTime();
        $day = intval(date("j"));
        $hour = intval(date("G"));

        $condition = $day == 1 && ($hour >= 1 && $hour <= 2);
        //$condition = true;

        if ($condition == true){



            $statClient = RequestHelper::Get("http://registration.habb.kz/rest/account.php", ["action" => "month.statistic.client"]);
            if ($statClient["result"] != true) {
                $response["stat_error"] = "Возникли проблемы при записи статистики для клиентов";
            }

            $statClient = RequestHelper::Get("http://registration.habb.kz/rest/account.php", ["action" => "month.statistic.team"]);
            if ($statClient["result"] != true) {
                $response["stat_error"] = "Возникли проблемы при записи статистики для команд";
            }

            $response["result"] = $_DATABASE->setMonthStartingRate();

            $backupResult = RequestHelper::Get("http://registration.habb.kz/rest/control.php", ["action" => "mysql.dump"]);
            if ($backupResult["result"] != true) {
                $response["backup_error"] = "Возникли проблемы при записи бэкапа";
            }

        } else {
            $response["result"] = false;
            $response["error"] = "Инициирование мес.очков происходит в первый день месяца в период между 7 и 8 часами утра";
        }
        break;

    case "month.statistic.client":
        $response["result"] = CollectionHelper::makeAStatistic($_DATABASE, Statistic::CLIENT_TYPE);
        break;

    case "month.statistic.team":
        $response["result"] = CollectionHelper::makeAStatistic($_DATABASE, Statistic::TEAM_TYPE);
        break;
}


header('Content-Type: application/json');
echo json_encode($response);





function sendEmail($subject, $message){
    $mailSMTP = SmtpEmail::getInstance();

    $result =  $mailSMTP->send($_REQUEST["email"], $subject, $message); // отправляем письмо
    return $result;
}

function constructEmailBody($clientName, $habbId){
    $message = "";
    //$message .= "< image ><br>";
    $message .= "<h1>Регистрация на HABB.KZ</h1>";
    $message .= "<p>Добрый день, <b>".$clientName."</b><br>".
        "Вы успешно зарегистрировались в сети habb.kz</p>";
    $message .= "<p>Ваш HABB ID: <b>".$habbId."</b></p>";
    $message .= "<p>HABB ID позволяет Вам участвовать в любых турнирах в рамках HABB, и даёт возможность отслеживать Вашу позицию в Рейтинге.</p>";
    $message .= "<p>С уважением, команда HABB.</p>";
    return $message;
}