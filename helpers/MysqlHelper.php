<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 17.11.2016
 * Time: 16:32
 */
class MysqlHelper
{


    /** @var mysqli */
    private $context = null;
    const DB_HOST           = "localhost";


    function __construct($username, $password, $db_name) {
        $this->context = mysqli_connect(self::DB_HOST, $username, $password, $db_name);

        if (!$this->context){
            $this->context = null;
            $error = mysqli_connect_error();
            ApplicationHelper::processError($error, true);
            return $error;

        } else {
            $this->context->set_charset("utf8");

            //mysqli_set_charset($this->context, "utf8");
            return true;
        }
    }

    /**
     * @return mysqli MySQL контекст
     */
    public function getContext(){
        return $this->context;
    }

    /**
     * Возвращает последний ID, использованный в запросе
     * @return int|string
     */
    public function getLastInsertedId(){
        return mysqli_insert_id($this->context);
    }

    /**
     * Аналог синглтона, но создает новый объект вместо возврата уже существующего
     *
     * @return MysqlHelper
     */
    public static function getNewInstance(){
        $instance = new self(Config::getValue(Config::DB_USERNAME), Config::getValue(Config::DB_PASSWORD), Config::getValue(Config::DB_NAME));
        return $instance;
    }

    public function getTeamsForStatistic($gameName = Score::SCORE_CSGO){
        $query2 = "SELECT ".TABLE_TEAMS.".id, ".TABLE_TEAMS.".team_name, ".TABLE_TEAM_SCORES.".total_value, ".TABLE_TEAM_SCORES.".change_total, ".TABLE_TEAM_SCORES.".month_value, 
        
          ".TABLE_TEAMS.".last_operation,
          ".TABLE_TEAMS.".updated_at,
          ".TABLE_TEAMS.".created_at
        FROM
          ".TABLE_TEAMS."  
        LEFT JOIN 
          ".TABLE_TEAM_SCORES." ON ".TABLE_TEAMS.".id = ".TABLE_TEAM_SCORES.".team_id AND ".TABLE_TEAM_SCORES.".game_name='$gameName'  ".
            " ORDER BY ".TABLE_TEAM_SCORES.".total_value DESC ";

        $query_result = $this->selectData($query2);
        if ($query_result["result"] == true) {
            $instances = array();
            foreach ($query_result["data"] as $key => $value) {

                $monthChange = intval($value["total_value"]) - intval($value["month_value"]);
                $team = [
                    "id" => $value["id"],
                    "name" => $value["team_name"],
                    "value" => intval($value["total_value"]),
                    "change" => $value["change_total"],
                    "monthValue" => intval($value["month_value"]),
                    "monthChange" => $monthChange
                ];

                $instances[] = $team;
            }
            $query_result = $instances;
        }

        return $query_result;
    }

    /**
     * Возвращает массив команд и игроков для рейтинга
     * array(
     *   "team" : {
     *           "id" : id,
     *           "name" : полное имя,
     *           "value" : очки,
     *           "change" ,
     *           "monthValue" : ,
     *           "monthChange" :
     *
     *      },
     *   "players" : [
     *          {
     *              "id" : id,
     *              "name" : полное имя,
     *              "value" : очки
     *          },
     *          {
     *              "id" : id,
     *              "name" : полное имя,
     *              "value" : очки
     *          }
     *      ]
     * )
     * @param string $gameName
     * @param int $greaterThan
     * @return array|null
     */
    public function getTeamsForRating($gameName = Score::SCORE_CSGO, $greaterThan = -1){


        $query2 = "SELECT ".TABLE_TEAMS.".id, ".TABLE_TEAMS.".team_name, ".TABLE_TEAM_SCORES.".total_value, ".TABLE_TEAM_SCORES.".change_total, ".TABLE_TEAM_SCORES.".month_value, 
        
          `captain_id`,
          c0.`name` AS captain_name,
          c0.`last_name` AS captain_last_name,
          s0.`total_value` AS captain_score,
        
          `player_2_id`,
          c1.`name` AS player_2_name,
          c1.`last_name` AS player_2_last_name,
          s1.`total_value` AS player_2_score,
        
          `player_3_id`,
          c2.`name` AS player_3_name,
          c2.`last_name` AS player_3_last_name,
          s2.`total_value` AS player_3_score,
        
          `player_4_id`,
          c3.`name` AS player_4_name,
          c3.`last_name` AS player_4_last_name,
          s3.`total_value` AS player_4_score,
        
          `player_5_id`,
          c4.`name` AS player_5_name,
          c4.`last_name` AS player_5_last_name,
          s4.`total_value` AS player_5_score,
        
          ".TABLE_TEAMS.".last_operation,
          ".TABLE_TEAMS.".updated_at,
          ".TABLE_TEAMS.".created_at
        FROM
          ".TABLE_TEAMS."
        LEFT JOIN
          ".TABLE_CLIENTS." AS c0 ON captain_id = c0.id
        LEFT JOIN
          ".TABLE_CLIENTS." AS c1 ON player_2_id = c1.id
        LEFT JOIN
          ".TABLE_CLIENTS." AS c2 ON player_3_id = c2.id
        LEFT JOIN
          ".TABLE_CLIENTS." AS c3 ON player_4_id = c3.id
        LEFT JOIN
          ".TABLE_CLIENTS." AS c4 ON player_5_id = c4.id
        LEFT JOIN 
          ".TABLE_SCORES." as s0 ON captain_id = s0.client_id AND s0.game_name='$gameName'
        LEFT JOIN 
          ".TABLE_SCORES." as s1 ON player_2_id = s1.client_id AND s1.game_name='$gameName'
        LEFT JOIN 
          ".TABLE_SCORES." as s2 ON player_3_id = s2.client_id AND s2.game_name='$gameName'
        LEFT JOIN 
          ".TABLE_SCORES." as s3 ON player_4_id = s3.client_id AND s3.game_name='$gameName'
        LEFT JOIN 
          ".TABLE_SCORES." as s4 ON player_5_id = s4.client_id AND s4.game_name='$gameName' 
          
        LEFT JOIN 
          ".TABLE_TEAM_SCORES." ON ".TABLE_TEAMS.".id = ".TABLE_TEAM_SCORES.".team_id AND ".TABLE_TEAM_SCORES.".game_name='$gameName'  ".
          " WHERE ".TABLE_TEAM_SCORES.".total_value>$greaterThan  ORDER BY ".TABLE_TEAM_SCORES.".total_value DESC ";

        $query_result = $this->selectData($query2);



        if ($query_result["result"] == true) {
            $instances = array();
            foreach ($query_result["data"] as $key => $value) {

                $monthChange = intval($value["total_value"]) - intval($value["month_value"]);
                $team = [
                    "id" => $value["id"],
                    "name" => $value["team_name"],
                    "value" => intval($value["total_value"]),
                    "change" => $value["change_total"],
                    "monthValue" => intval($value["month_value"]),
                    "monthChange" => $monthChange
                ];
                $players = array();
                $players[] = [
                    "id" => $value["captain_id"],
                    "name" => $value["captain_name"]." ".$value["captain_last_name"],
                    "value" => intval($value["captain_score"])
                ];
                $players[] = [
                    "id" => $value["player_2_id"],
                    "name" => $value["player_2_name"]." ".$value["player_2_last_name"],
                    "value" => intval($value["player_2_score"])
                ];
                $players[] = [
                    "id" => $value["player_3_id"],
                    "name" => $value["player_3_name"]." ".$value["player_3_last_name"],
                    "value" => intval($value["player_3_score"])
                ];
                $players[] = [
                    "id" => $value["player_4_id"],
                    "name" => $value["player_4_name"]." ".$value["player_4_last_name"],
                    "value" => intval($value["player_4_score"])
                ];
                $players[] = [
                    "id" => $value["player_5_id"],
                    "name" => $value["player_5_name"]." ".$value["player_5_last_name"],
                    "value" => intval($value["player_5_score"])
                ];



                $instances[] =  [
                    "team" => $team,
                    "players" => $players
                ];
            }
            $query_result = $instances;
        }

        return $query_result;
    }


    /**
     * На выходе имеем массив данных: [{id, fullName, value, change}]
     *
     * @param int $greaterThan = -1
     * @param string $gameName
     * @param bool $withLimit
     * @param int $limit = 10
     * @return array|null
     */
    public function getClientRating($gameName = Score::SCORE_CSGO, $greaterThan = -1, $withLimit = false, $limit = 10){
        $query = "SELECT ".TABLE_CLIENTS.".id, ".TABLE_CLIENTS.".name, ".TABLE_CLIENTS.".last_name, ".TABLE_SCORES.".total_value, ".TABLE_SCORES.".change_total, ".TABLE_SCORES.".month_value FROM ".TABLE_CLIENTS." ".
            "LEFT JOIN 
              ".TABLE_SCORES." ON ".TABLE_CLIENTS.".id=".TABLE_SCORES.".client_id AND ".TABLE_SCORES.".game_name='$gameName' ".
            "WHERE ".TABLE_SCORES.".total_value>$greaterThan ORDER BY ".TABLE_SCORES.".total_value DESC ";
        if ($withLimit == true){
            $query .= "LIMIT $limit";
        }

        $query_result = $this->selectData($query);
        if ($query_result["result"] == true) {
            $instances = [];

            foreach ($query_result["data"] as $key => $value) {
                //$client = new Client();

                $monthChange = intval($value["total_value"]) - intval($value["month_value"]);
                $instance = [
                    "id" => $value["id"],
                    "name" => $value["name"]. " ".$value["last_name"],
                    "value" => intval($value["total_value"]),
                    "change" => intval($value["change_total"]),
                    "monthValue" => intval($value["month_value"]),
                    "monthChange" => $monthChange
                ];
                $instances[] = $instance;
            }
            $query_result = $instances;
        } else {
            $query_result = null;
        }

        return $query_result;
    }


    /**
     * Обновляет значение очков игрока
     *
     * @param $scoreId - ID записи очков
     * @param $gamerId - ID геймера
     * @param $gameName - Название дисциплины
     * @param $scoreValue - значение очков
     * @param $changeText - значение измененного текста
     * @return array
     */
    public function updateScore($scoreId, $gamerId, $gameName, $scoreValue, $changeText){
        $query = "UPDATE `".TABLE_SCORES."` SET ".
            "`total_value`=$scoreValue,".
            "`change_total`='$changeText'".
            " WHERE gamer_id=$gamerId AND game_name='$gameName'";
        $query_result = $this->executeQuery($query);

        return $query_result;
    }

    /**
     * Обновляет значение очков команды
     *
     * @param $teamId - ID клиента
     * @param $gameName - Название дисциплины
     * @param $scoreValue - значение очков
     * @param $changeText - значение измененного текста
     * @return array
     */
    public function updateTeamScore($teamId, $gameName, $scoreValue, $changeText){
        $query = "UPDATE `".TABLE_TEAM_SCORES."` SET ".
            "`total_value`=$scoreValue,".
            "`change_total`='$changeText'".
            " WHERE team_id=$teamId AND game_name='$gameName'";

        $query_result = $this->executeQuery($query);
        return $query_result;
    }

    /**
     * @param array $playerIds
     * @param int $scoreValue
     * @param string $gameName
     * @return array|bool
     */
    public function updateTeamPlayersScore(array $playerIds, $scoreValue, $gameName){
        $result = true;
        foreach ($playerIds as $id) {
            if (is_null($id) || $id == "null") continue;

            $operation = intval($scoreValue) > 0 ? "+" : "";
            $query = "UPDATE ".TABLE_SCORES." SET total_value=total_value$operation$scoreValue WHERE gamer_id=$id AND game_name='$gameName' ";
            $query_result = $this->executeQuery($query);
            $result = $result && $query_result["result"];
        }

        return $result;
    }


    public function deleteInstance($searchable, $field, $tableName){
        $query = "delete from ".$tableName." where ".$field."='".$searchable."'";
        return $this->executeQuery($query);
    }


    /**
     * Переносит значения очков на текущий момент в показатель на начало месяца
     * @return bool
     */
    public function setMonthStartingRate() {
        $queries = [];
        $queries[] = "UPDATE ".TABLE_SCORES." SET month_value=total_value";
        $queries[] = "UPDATE ".TABLE_TEAM_SCORES." SET month_value=total_value";

        $result = true;
        foreach ($queries as $q){
            $query_result = $this->executeQuery($q);
            $result = $result&& $query_result["result"];
        }


        return $result;
    }

    /**
     * Сбрасывает показатель очков на начало месяца на определенное значение
     * @param int $value
     * @return bool
     */
    public function resetMonthStartingRate($value = 0) {
        $queries = [];
        $queries[] = "UPDATE ".TABLE_SCORES." SET month_value=$value";
        $queries[] = "UPDATE ".TABLE_TEAM_SCORES." SET month_value=$value";

        $result = true;
        foreach ($queries as $q){
            $query_result = $this->executeQuery($q);
            $result = $result&& $query_result["result"];
        }


        return $result;
    }





    function selectData($query) {
        if (is_null($this->context )) return null;
        $data = mysqli_query($this->context, $query);

        if ($data) {
            $rows = array();
            while ($row = mysqli_fetch_assoc($data)) {
                array_push($rows, $row);
            }
            $data = $rows;

            $result = true;

        } else {

            $data = mysqli_error($this->context);
            ApplicationHelper::processError("Ошибка выполнения запроса [".$query."] (Select): ".$data);
            $result = false;
        }
        return array(
            "result" => $result,
            "data" => $data);
    }

    /**
     * Выполняет SQL запрос
     *
     * @param $query
     * @return array("result" => true/false, "data" => array())|null
     */
    public function executeQuery($query) {

        if (is_null($this->context )) return null;
        $data = mysqli_query($this->context, $query);

        if ($data) {

            $data = !is_bool($data) ? mysqli_fetch_assoc($data) : $data;
            //ApplicationHelper::debug("query = ".$query." data = ".var_export($data, true));
            $result = true;

        } else {
            $data = mysqli_error($this->context);
            ApplicationHelper::processError("Ошибка выполнения запроса [".$query."] (Execute): ".$data);
            $result = false;
        }
        return array("result" => $result, "data" => $data);

    }

    public function getCharset(){
        return $this->context->character_set_name();
    }

    /**
     * Обновляет номер лида в CRM
     * @param $client Gamer
     * @return array
     */
    public function updateLeadId($client) {
        $query = "update ".TABLE_CLIENTS." set lead_id='".$client->lead_id."' where id=".$client->id;
        $query_result = $this->executeQuery($query);
        // if ($query_result["result"] != true) return $query_result;
        // $query_result["data"] = mysqli_insert_id($this->context);;
        // $id = mysqli_insert_id($this->context);
        return $query_result;
    }

    public function makeBackup(){

        //get all of the tables
        $returnResult = [
            "result" => true
        ];

        $tables = array();
        $result = mysqli_query($this->context, 'SHOW TABLES');
        while($row = mysqli_fetch_row($result))
        {
            $tables[] = $row[0];
        }
        $return = "";
        $returnResult["tablesCount"] = count($tables);
        //cycle through
        foreach($tables as $table)
        {
            $result = mysqli_query($this->context, 'SELECT * FROM '.$table);
            $num_fields = mysqli_num_fields($result);

            $return.= 'DROP TABLE '.$table.';';
            $row2 = mysqli_fetch_row(mysqli_query($this->context, 'SHOW CREATE TABLE '.$table));
            $return.= "\n\n".$row2[1].";\n\n";

            for ($i = 0; $i < $num_fields; $i++)
            {
                while($row = mysqli_fetch_row($result))
                {
                    $return.= "INSERT INTO ".$table." VALUES (";
                    for($j=0; $j < $num_fields; $j++)
                    {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = str_replace("\n","\\n", $row[$j]);
                        if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                        if ($j < ($num_fields-1)) { $return.= ','; }
                    }
                    $return.= ");\n";
                }
            }
            $return.="\n\n\n";
        }

        //save file
        $date = date("Y-m-d");
        $filename = $_SERVER["DOCUMENT_ROOT"] . "/backup/db_$date.sql";
        $handle = fopen($filename,'w+');
        fwrite($handle,$return);
        fclose($handle);
        return $returnResult;
    }
}