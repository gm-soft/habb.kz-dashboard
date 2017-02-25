<?php
require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$result = false;
$action = $_REQUEST["action"];
$response = array(
    "result" => $result,
    "action" => $action
);
$ip = $_SERVER['REMOTE_ADDR'];
ApplicationHelper::logEvent("ajax.php: \$_REQUEST[\"action\"]=".$action);

switch ($action) {

    case "account.search":

        header("Access-Control-Allow-Origin: *");
        $searchField = isset($_REQUEST["field"]) ? FormHelper::ClearInputData($_REQUEST["field"]) : null;
        $value = isset($_REQUEST["value"]) ? FormHelper::ClearInputData($_REQUEST["value"]) : null;

        if (is_null($searchField) || is_null($value)){
            $response["result"] = false;
            $response["error"] = "search field or value has not been received";
            break;
        }

        $account = Gamer::getInstanceFromDatabase($value, $_DATABASE, $searchField);
        if (!is_null($account)){
            $response["result"] = true;
            $response["account"] = [
                "id" => $account->id,
                "name" => $account->name. " " . $account->lastName,
                "email" => $account->email,
                "phone" => $account->phone
            ];
            break;
        }
        $response["result"] = false;
        $response["account"] = null;

        break;

    case "cities.get":
        $cities = ApplicationHelper::getCities();
        $response["result"] = $cities;
        break;

    case "select2.participants.get":

        $type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : TournamentTypes::Teams;
        $participants = null;
        if ($type == TournamentTypes::Gamers) $participants = Gamer::getInstancesFromDatabase($_DATABASE);
        else $participants = Team::getInstancesFromDatabase($_DATABASE);

        $options = [];
        foreach ($participants as $participant) {
            $option = [
                "value" => $participant->getKey(),
                "text" => $participant->getValue()
            ];
            $options[] = $option;
        }
        $response["result"] = $options;
        $response["count"] = count($options);
        break;
}

Html::RenderJson($response);