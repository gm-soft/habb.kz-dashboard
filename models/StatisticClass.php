<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 10.02.2017
 * Time: 10:45
 */
class Statistic extends BaseInstance
{
    /**
     * Массив в формате json
     * @var null|array() */
    public $content;

    /** @var  string */
    public $type;

    /** @var  string */
    public $game;


    const CLIENT_TYPE = "client";
    const TEAM_TYPE = "team";


    function __construct($id = -1)
    {
        $this->id = $id;
        $this->content = null;
        $this->type = self::CLIENT_TYPE;
        $this->game = null;
        $this->createdAt = new DateTime();
    }

    /**
     * Создает новый экземпляр
     *
     * @param string $type Тип: клиент или команда
     * @param string $game Игра. по дефолту - CS:GO
     * @return Statistic
     */
    public static function createInstance($type = self::CLIENT_TYPE, $game = Score::SCORE_CSGO){
        $instance = new self();
        $instance->type = $type;
        $instance->game = $game;
        return $instance;
    }

    public function getCreatedAt($format = "d.m.Y H:i:s"){
        $result = date($format, $this->createdAt->getTimestamp() + 6 * 3600);
        return $result;
    }

    /**
     * ФОрмирует контент для тела статистики
     *
     * @param array $dbRows
     * @return bool
     */
    public function addContent(array $dbRows){
        if (is_null($dbRows) || count($dbRows) == 0){
            $this->content = null;
            return false;
        }
        $jsonArray = [];

        foreach ($dbRows as $dbRow) {

            if (is_null($dbRow["id"])) continue;
            $item = [
                "id" => $dbRow["id"],
                "name" => $dbRow["name"],
                "previousValue" => $dbRow["monthValue"],
                "currentValue" => $dbRow["value"]
            ];

            $jsonArray[] = $item;
        }
        $this->content = json_encode($jsonArray);
        return true;
    }

    public function fill(array $row){
        $this->id       = isset($row["statistic_id"]) ?         intval($row["statistic_id"]) : $this->id;

        $content = isset($row["statistic_body"]) ?    $row["statistic_body"] : null;
        $content = substr($content, 1, -1);
        //$this->content  = !is_null($content) && $content != "" ? json_decode($content, true) : null;
        $this->content  = json_decode($content, true);

        $this->type     = isset($row["statistic_type"]) ?  $row["statistic_type"] : $this->type;
        $this->game     = isset($row["statistic_game"]) ?  $row["statistic_game"] : $this->game;
        $this->createdAt= isset($row["created_at"]) ? DateTime::createFromFormat("Y-m-d H:i:s", $row["created_at"]) : $this->createdAt;
    }

    public static function fromDatabase($row)
    {
        $instance = new self();

        $instance->fill( $row );
        return $instance;
    }

    public function contentToJson(){
        return json_encode($this->content);
    }

    public function getContentArray(){
        return json_decode($this->content);
    }

    /**
     * Берутся записи из базы данных
     *
     * @param $mysql MysqlHelper
     * @param array $filterArray
     * @param string $condition
     * @return Statistic[]
     */
    public static function getInstancesFromDatabase($mysql, array $filterArray = null, $condition = "AND"){

        $query = "SELECT * FROM ".TABLE_STATISTIC;

        if (!is_null($filterArray) && count($filterArray)>0) {
            $query .= " WHERE ";
            $count = count($filterArray);
            for ($i = 0; $i < $count;$i++) {
                $item = $filterArray[$i];
                $query .= $item;
                if ($i != $count-1) $query .= " $condition ";
            }
        }
        $query .= " ORDER BY statistic_id DESC";

        $query_result = $mysql->selectData($query);
        if ($query_result["result"] == true) {
            $instances = [];
            foreach ($query_result["data"] as $key => $value) {

                $instance = self::fromDatabase($value);
                $instances[] = $instance;
            }
            $query_result = $instances;
        }

        return $query_result;
    }

    /**
     * Возвращает запись по ID
     *
     * @param $searchable
     * @param $mysql MysqlHelper
     * @param string $searchField
     * @return null|Statistic
     */
    public static function getInstanceFromDatabase($searchable, $mysql, $searchField = "statistic_id") {


        $query = "SELECT * FROM ".TABLE_STATISTIC." WHERE $searchField='$searchable'";
        $data = $mysql->executeQuery($query);

        if (
            $data["result"] != true ||
            is_null($data["data"])
        ) return null;

        return self::fromDatabase($data["data"]);
    }

    /**
     * @param $mysql MysqlHelper
     * @return array(
     *      "result" => true/false,
     *      "data" => id/ошибка
     * )|null
     */
    public function insertToDatabase($mysql){

        if ($this->content == null || count($this->content) == 0) return ["result" => false];
        $content = $this->contentToJson();
        $type = $this->type;
        $game = $this->game;

        $query = "INSERT INTO ".TABLE_STATISTIC." (statistic_body, statistic_game, statistic_type) VALUES ('$content', '$game', '$type')";
        $query_result = $mysql->executeQuery($query);
        if ($query_result["result"] != true) {
            return $query_result;
        }
        $query_result["data"] = $mysql->getLastInsertedId();
        $this->id = $query_result["data"];
        return $query_result;
    }

    /**
     * Обновляет данные
     *
     * @param $mysql MysqlHelper
     * @return array|null
     */
    public function updateInDatabase($mysql){
        $content = $this->contentToJson();
        $type = $this->type;
        $game = $this->game;

        $query = "UPDATE ".TABLE_STATISTIC." SET ".
            "statistic_body='$content', ".
            "statistic_game='$game', ".
            "statistic_type='$type' ".
            " WHERE statistic_id=".$this->id;
        $query_result = $mysql->executeQuery($query);
        return $query_result;
    }

    /**
     * Удаляет запись
     *
     * @param $mysql MysqlHelper
     * @return array|null
     */
    public function deleteFromDatabase($mysql){
        $searchable = $this->id;
        $field = "statistic_id";
        $tableName = TABLE_STATISTIC;
        return $mysql->deleteInstance($searchable, $field, $tableName);
    }


    static function filterInstancesFromDatabase($mysql, array $filterConditions, $condition, $withSort, $sortBy, $sortType)
    {
        // TODO: Implement filterInstancesFromDatabase() method.
        return self::getInstancesFromDatabase($mysql);
    }
}











