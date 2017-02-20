<?php
	require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");


	$access_data =  ApplicationHelper::readAccessData();

	$result = false;
	$action = $_REQUEST["action"];
	$json_array = array(
		"result" => $result,
		"action" => $action
	);
	$ip = $_SERVER['REMOTE_ADDR'];
    ApplicationHelper::logEvent("control.php: \$_REQUEST[\"action\"]=".$action);

	switch ($action) {
		case 'refresh':
			if (is_null($access_data)) {

				$text = "Refresh requested, but Access data is null";
				ApplicationHelper::logEvent($text);
				break;
			}
			$params = ServerHelper::constructRefreshParams($access_data["refresh_token"]);
			$query_data = RequestHelper::Get("https://habb1.bitrix24.kz/oauth/token/", $params);

			$text_to_log = "";

			if(isset($query_data["access_token"])) {
                $query_data["ts"] = time();
                $json = json_encode($query_data);
                $json_array["result"] = ApplicationHelper::writeToFile(AUTH_FILENAME, $json);
            }
			break;

		case "getAccessToken":
			$content = ApplicationHelper::readFromFile(AUTH_FILENAME);
			$json_array = ApplicationHelper::toJson($content);
			break;

        case "mysql.dump":

            $json_array = $_DATABASE->makeBackup();
            break;

        case "test":

            $mailSMTP = SmtpEmail::getNewInstance();
            $json_array =  $mailSMTP->send("maximgorbatyuk191093@gmail.com", "Hello", "Hey! F**k you");

            /*$api_id = 5860760; // Insert here id of your application
            $secret_key = 'dNXohtkjOVawVf6QrkiI'; // Insert here secret key of your application

            $vk = new VkHelper($api_id, $secret_key);
            $json_array = $vk->api('getProfiles', array('uids' => 'maximgorbatyuk12', 'fields' =>'first_name,last_name,photo_100,status,screen_name'));*/
            break;
	}
	
	
	header('Content-Type: application/json');
	echo json_encode($json_array);