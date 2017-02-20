
<?php
	require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
	//---------------------------------------------
	$error = "";

	if(isset($_REQUEST["code"]))
	{
		$code = $_REQUEST["code"];
		$params = ServerHelper::constructFirstAuthParams($code);
		$query_data = RequestHelper::Get("https://habb1.bitrix24.kz/oauth/token/", $params);

		if(isset($query_data["access_token"]))
		{
			
			//$_SESSION["query_data"]["ts"] = time();
			$query_data["ts"] = time();
			$json = json_encode($query_data);
			$writeResult = ApplicationHelper::writeToFile(AUTH_FILENAME, $json);

            $path = Config::getValue(Config::B24_SERVER_PATH);
			ApplicationHelper::redirect($path);
			die();
		}
		else
		{
			$error = "Произошла ошибка авторизации! ".print_r($query_data, 1);
		}
	}

	$access_source = ApplicationHelper::readFromFile(AUTH_FILENAME);
	$access_data = $access_source != "null" ? ApplicationHelper::toJson($access_source) : NULL;

	if(!is_null($access_data) && isset($_REQUEST["refresh"]))
	{
		$params = ServerHelper::constructRefreshParams($access_data["refresh_token"]);
		$query_data = RequestHelper::Get("https://habb1.bitrix24.kz/oauth/token/", $params);
		
		if(isset($query_data["access_token"]))
		{

			$query_data["ts"] = time();
			$json = json_encode($query_data);
			$writeResult = ApplicationHelper::writeToFile(AUTH_FILENAME, $json);
			$path = Config::getValue(Config::B24_SERVER_PATH);
			ApplicationHelper::redirect($path);
		}
		else
		{
			$error = "Произошла ошибка авторизации! ".print_r($query_data);
		}
	}

	if(isset($_REQUEST["clear"])) {
		$access_data = NULL;
		$writeResult = ApplicationHelper::writeToFile(AUTH_FILENAME, "null");
        $path = Config::getValue(Config::B24_SERVER_PATH);
        ApplicationHelper::redirect($path);
	}


	if( is_null($access_data) || !isset($access_data["access_token"]) || (time() - $access_data["ts"]) > 3600)
	{
		if($error) echo '<b>'.$error.'</b>';
		//setcookie("user_email", "", time() - 3600, "/");
		$link = "https://habb1.bitrix24.kz/oauth/authorize/?client_id=".Config::getValue(Config::B24_CLIENT_ID)."&state=JJHgsdgfkdaslg7lbadsfg";
        Html::RenderHtmlHeader("Сервер авторизации");
	?>

		<div class="container">
			<div class="jumbotron">
				<h1>habb1.bitrix24.kz сервер авторизации</h1>
				<p>Авторизационные данные отсутствуют или устарели. Вы можете авторизоваться <a href="<?= $link ?>">снова</a></p>
			</div>
		</div>
	<?php
	}
	else
	{
		$lifetime = $access_data["ts"] + $access_data["expires_in"] - time();
		$update_time = date("H:i", $access_data["ts"] + (60*60*6));
		$expire_time = date("H:i", ($access_data["ts"] + $access_data["expires_in"] + (60*60*6)));



        Html::RenderHtmlHeader("Сервер авторизации");
		?>
        <div class="container">
            <div class="mt-2">
                <h1>Сервер авторизации habb1.bitrix24.kz</h1>
            </div>

            <div class="card">
                <div class="card-block">
                    <h4 class="card-title">Информация о токене</h4>
                    <p class="card-text">
                        <div class="row justify-content-around">
                            <div class="col-sm-3">Время жизни: <?=$lifetime?> сек.</div>
                            <div class="col-sm-3">Обновлен: <?=$update_time?></div>
                            <div class="col-sm-3">Срок жизни: <?=$expire_time?></div>
                        </div>
                    </p>
                </div>
                <div class="card-footer">
                    <div class="float-sm-right">
                        <a href="<?=Config::getValue(Config::B24_SERVER_PATH)?>?refresh=1" class="btn btn-secondary">Обновить данные авторизации</a>
                    </div>
                </div>
            </div>

        </div>

<?php
	}
	Html::RenderHtmlFooter();
