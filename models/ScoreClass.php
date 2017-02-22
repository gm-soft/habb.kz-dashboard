<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 01.02.2017
 * Time: 13:26
 */
class Score extends BaseInstance
{

    /** @var int ID клиента */
    public $clientId = -1;

    /** @var string|null */
    public $gameName = null;

    /** @var int Общее значение очков */
    public $value = 0;

    /** @var int Значение очков на первое число месяца */
    public $monthValue = 0;

    /** @var int Последнее изменение очков */
    public $valueChange = 0;

    /** @var int Прирост очков за месяц */
    public $monthChange = 0;


    const SCORE_CSGO = "cs:go";
    const SCORE_DOTA = "dota";
    const SCORE_HEARTHSTONE = "hearthstone";
    const GAME_ARRAY = "cs:go,dota,hearthstone";

    /**
     * Score constructor.
     * @param int $clientId
     * @param string $gameName
     */
    function __construct($clientId = -1, $gameName = null)
    {
        $this->clientId = $clientId;
        $this->gameName = $gameName;
        $this->createdAt = new DateTime();
        $this->updatedAt = $this->createdAt;
    }

    public function fill( array $row )
    {
        $this->id           = isset($row["id"]) ?         intval($row["id"]) : $this->id;

        if (isset($row["gamer_id"])){
            $this->clientId = intval($row["gamer_id"]);
        } elseif (isset($row["team_id"])){
            $this->clientId = intval($row["team_id"]);
        }
        //$this->clientId     = isset($row["client_id"]) ?    $row["client_id"] : $this->clientId;


        $this->gameName     = isset($row["game_name"]) ?    $row["game_name"] : $this->gameName;
        $this->value        = isset($row["total_value"]) ?  intval($row["total_value"]) : $this->value;
        $this->monthValue   = isset($row["month_value"]) ?  intval($row["month_value"]) : $this->monthValue;
        $this->valueChange  = isset($row["change_total"]) ? intval($row["change_total"]) : $this->valueChange;
        $this->monthChange  = isset($row["change_month"]) ? intval($row["change_month"]) : $this->monthChange;
        $this->createdAt    = isset($row["created_at"]) ? DateTime::createFromFormat("Y-m-d H:i:s", $row["created_at"]) : $this->createdAt;
        $this->updatedAt    = isset($row["updated_at"]) ? DateTime::createFromFormat("Y-m-d H:i:s", $row["updated_at"]) : $this->updatedAt;

        $this->monthChange = $this->value - $this->monthValue;
    }

    public static function fromDatabase($row)
    {
        $instance = new self();

        $instance->fill( $row );
        return $instance;
    }

    public static function fromRequest($row){
        $instance = new self();
        $instance->fill( $row );
        return $instance;
    }

    public static function getDefaultSet($clientId = -1){
        $games = self::getGameArray();
        $instances = [];
        foreach ($games as $game){
            $instances[] = new Score($clientId, $game);
        }
        return $instances;
    }

    /**
     * @param string $gameName
     * @param Score[] $scoreArray
     * @return Score|null
     */
    public static function getScoreByGame($gameName, $scoreArray){
        $result = null;

        if ($scoreArray == null) return $result;
        foreach ($scoreArray as $item){

            if ($item->gameName != $gameName) continue;
            $result = $item;
            break;
        }
        return $result;
    }

    public function getAsArray(){
        $result = [
            "id" => $this->id,
            "gamer_id" => $this->clientId,
            "game_name" => $this->gameName,
            "total_value" => $this->value,
            "month_value" => $this->monthValue,
            "change_total" => $this->valueChange,
            "change_month" => $this->monthChange,
        ];
        return $result;
    }

    /**
     * @param $clientId - ID клиента
     * @param $mysql MysqlHelper
     * @param bool $clientTable - Если true (по дефолту), то работа идет с базой клиентских очков, иначе - командные очки
     * @return Score[]
     */
    public static function getSetFromDatabase($clientId, $mysql, $clientTable = true){

        $fieldName = $clientTable == true ? "gamer_id" : "team_id";
        $tableName = $clientTable == true ? TABLE_SCORES : TABLE_TEAM_SCORES;

        $query = "SELECT * FROM $tableName WHERE $fieldName=$clientId";

        $query_result = $mysql->selectData($query);
        $rows = $query_result["data"];
        $gameArray = self::getGameArray();
        $gameCount = count($gameArray);

        if ($query_result["result"] == true && count($rows) >= $gameCount) {
            $instances = [];

            for ($i = 0; $i < $gameCount; $i++) {

                $value = $query_result["data"][$i];
                $instance = self::fromDatabase($value);
                $instances[] =  $instance;
            }
            $query_result = $instances;
        } else {
            $query_result = self::getDefaultSet($clientId);
        }

        return $query_result;
    }

    /**
     * @param $mysql MysqlHelper
     * @param bool $clientTable - Если true (по дефолту), то работа идет с базой клиентских очков, иначе - командные очки
     * @return array
     */
    public function insertToDatabase($mysql, $clientTable = true){

        $fieldName = $clientTable == true ? "gamer_id" : "team_id";
        $tableName = $clientTable == true ? TABLE_SCORES : TABLE_TEAM_SCORES;

        $query = "INSERT INTO $tableName ($fieldName, game_name) VALUES (".$this->clientId.", '".$this->gameName."')";
        $query_result = $mysql->executeQuery($query);
        if ($query_result["result"] != true) return $query_result;

        $this->id = $mysql->getLastInsertedId();
        $query_result["data"] = $this->id;
        return $query_result;
    }

    /**
     * @param $mysql MysqlHelper
     * @param bool $clientTable - Если true (по дефолту), то работа идет с базой клиентских очков, иначе - командные очки
     * @return array
     */
    public function updateInDatabase($mysql, $clientTable = true){

        $fieldName = $clientTable == true ? "gamer_id" : "team_id";
        $tableName = $clientTable == true ? TABLE_SCORES : TABLE_TEAM_SCORES;

        $query = "UPDATE $tableName SET ".
            "$fieldName=".$this->clientId.", ".
            "game_name='".$this->gameName."', ".
            "total_value=".$this->value.", ".
            "month_value=".$this->monthValue.", ".
            "change_total='".$this->valueChange."', ".
            "change_month='".$this->monthChange."', ".
            "updated_at=NOW() ".
            "WHERE id=".$this->id;
        $query_result = $mysql->executeQuery($query);
        return $query_result;
    }

    /**
     * Удаляет конкретную запись из БД
     * @param $mysql MysqlHelper
     * @param bool $clientTable
     * @return mixed
     */
    public function deleteFromDatabase($mysql, $clientTable = true){
        $fieldName = $clientTable == true ? "gamer_id" : "team_id";
        $tableName = $clientTable == true ? TABLE_SCORES : TABLE_TEAM_SCORES;

        $query = "DELETE FROM $tableName WHERE id=".$this->id." ";
        return $mysql->executeQuery($query);
    }

    /**
     * Удаляет все записи, связанные с клиентом
     * @param $clientId - ID клиента
     * @param $mysql MysqlHelper
     * @param bool $clientTable
     * @return mixed
     */
    public static function deleteSetDatabase($clientId, $mysql, $clientTable = true){

        $fieldName = $clientTable == true ? "gamer_id" : "team_id";
        $tableName = $clientTable == true ? TABLE_SCORES : TABLE_TEAM_SCORES;

        $query = "delete from $tableName where $fieldName=$clientId ";
        return $mysql->executeQuery($query);
    }


    public static function getGameArray(){
        $array = explode(",", self::GAME_ARRAY);
        return $array;
    }

    public static function getInstanceFromDatabase($searchable, $mysql, $searchField)
    {
        // TODO: Implement getInstanceFromDatabase() method.
    }

    public static function filterInstancesFromDatabase($mysql, array $filterConditions, $condition, $withSort, $sortBy, $sortType)
    {
        // TODO: Implement filterInstancesFromDatabase() method.
        return self::getInstancesFromDatabase($mysql);
    }

    public static function getInstancesFromDatabase($mysql)
    {
        // TODO: Implement getInstancesFromDatabase() method.
    }
}