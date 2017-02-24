<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 20.01.2017
 * Time: 21:00
 */
class Team extends BaseInstance implements ISelectableOption, ITournamentParticipant
{

    /** @var string Название команды */
    public $name;

    /** @var string Город */
    public $city;

    /** @var string Запись о последнем изменении очков .+/-25, к примеру */
    public $score_change;

    /** @var int Капитан команды ID */
    public $captain_id;

    /** @var int Игрок 2 */
    public $player_2_id;

    /** @var int Игрок 3 */
    public $player_3_id;

    /** @var int Игрок 4 */
    public $player_4_id;

    /** @var int Игрок 5 */
    public $player_5_id;

    /** @var Score[] */
    public $scoreArray;

    function __construct($id = -1)
    {
        $this->id = $id;
        $this->name = null;
        $this->city = null;
        $this->score_change = null;

        $this->captain_id = 0;
        $this->player_2_id = 0;
        $this->player_3_id = 0;
        $this->player_4_id = 0;
        $this->player_5_id = 0;
        $this->scoreArray = Score::getDefaultSet($this->id);
    }

    public static function fromDatabase(array $row){
        $instance = new self();
        $instance->fill($row);

        return $instance;
    }

    /**
     * Возвращает экземпляр, созданный из массива данных формы
     * @param array $request
     * @return Team
     */
    public static function fromRequest(array $request){
        $instance = new self();
        $instance->id = $request["id"];
        $instance->name = $request["name"];
        $instance->city = $request["city"];
        $instance->comment = $request["comment"];


        $instance->captain_id = $request["captain_id"];
        $instance->player_2_id = $request["player_2_id"];
        $instance->player_3_id = $request["player_3_id"];
        $instance->player_4_id = $request["player_4_id"];
        $instance->player_5_id = $request["player_5_id"];

        if ( isset($request["score_id"]) ){
            $scoreArray = [];
            $scoreId = $request["score_id"];
            $gameName = $request["game_name"];
            $totalValue = $request["total_value"];
            $monthValue = $request["month_value"];
            $totalChange = $request["change_total"];
            $monthChange = $request["change_month"];


            for ($i = 0; $i < count($scoreId); $i++){
                $item = new Score($instance->id, $gameName[$i]);
                $item->id = intval($scoreId[$i]);
                $item->value = intval($totalValue[$i]);
                $item->valueChange = intval($totalChange[$i]);
                $item->monthValue = intval($monthValue[$i]);
                $item->monthChange = intval($monthChange[$i]);

                $scoreArray[] = $item;
            }
            $instance->scoreArray = $scoreArray;
            //$defaultScore = Score::getScoreByGame(SCORE_DEFAULT, $instance->scoreArray);

        } else {
            $instance->scoreArray = Score::getDefaultSet($instance->id);
            //$defaultScore = Score::getScoreByGame(SCORE_DEFAULT, $instance->scoreArray);

        }

        return $instance;
    }

    public function fill(array $row) {
        $this->id = $row["id"];
        $this->name = $row["team_name"];
        $this->city = $row["city"];
        $this->comment = $row["comment"];
        $this->lastOperation = $row["last_operation"];

        $this->captain_id = $row["captain_id"];
        $this->player_2_id = $row["player_2_id"];
        $this->player_3_id = $row["player_3_id"];
        $this->player_4_id = $row["player_4_id"];
        $this->player_5_id = $row["player_5_id"];

        $this->createdAt = DateTime::createFromFormat("Y-m-d H:i:s", $row["created_at"]);
        $this->updatedAt = DateTime::createFromFormat("Y-m-d H:i:s", $row["updated_at"]);
    }

    /**
     * @return array
     */
    public function getPlayersIdAsArray(){
        $result = [
            $this->captain_id,
            $this->player_2_id,
            $this->player_3_id,
            $this->player_4_id,
            $this->player_5_id
        ];
        return $result;
    }

    public function getAsFormArray(){


        $result = [
            "id" => $this->id,
            "name" => $this->name,
            "city" => $this->city,
            "comment" => $this->comment,
            "captain_id" => $this->captain_id,
            "player_2_id" => $this->player_2_id,
            "player_3_id" => $this->player_3_id,
            "player_4_id" => $this->player_4_id,
            "player_5_id" => $this->player_5_id,
            "score_array" => $this->scoreArray
        ];
        return $result;
    }

    //-------------
    //-------------
    //-------------

    /**
     * Добавляет новую команду
     * @param $mysql MysqlHelper
     * @return array
     */
    public function insertToDatabase($mysql) {

        $query = "insert into `".TABLE_TEAMS."` (`team_name`, `city`, `comment`, `captain_id`, `player_2_id`, `player_3_id`, `player_4_id`, `player_5_id`, `last_operation`) values (".
            "'".$this->name."', "
            ."'".$this->city."', "
            ."'".$this->comment."', "
            .$this->captain_id.", "
            .$this->player_2_id.", "
            .$this->player_3_id.", "
            .$this->player_4_id.", "
            .$this->player_5_id.", "
            ."'".$this->lastOperation."' )";

        $query_result = $mysql->executeQuery($query);
        if ($query_result["result"] != true) {
            return $query_result;
        }
        $query_result["data"] = $mysql->getLastInsertedId();
        $this->id = $query_result["data"];
        //----------------------------------------
        $addRes = true;
        foreach ($this->scoreArray as $item){
            $item->clientId = $this->id;
            $addResSub = $item->insertToDatabase($mysql, false);
            $addRes = $addRes && $addResSub["result"];
        }
        $query_result["sub"] = $addRes;
        return $query_result;
    }

    /**
     * @param $searchable
     * @param string $field
     * @param $mysql MysqlHelper
     * @return null|Team
     */
    public static function getInstanceFromDatabase($searchable, $mysql, $field = "id") {

        $query = "select * from ".TABLE_TEAMS." where $field='$searchable'";
        $data = $mysql->executeQuery($query);

        if ($data["result"] != true ||
            is_null($data["data"])
        ) return null;

        $row = $data["data"];
        $instance = self::fromDatabase($row);
        $instance->scoreArray = Score::getSetFromDatabase($instance->id, $mysql, false);
        return $instance;
    }

    /**
     * Возвращает массив команд
     * @param $mysql MysqlHelper
     * @return Team[]|null
     */
    public static function getInstancesFromDatabase($mysql){

        $query = "select * from ".TABLE_TEAMS;

        $query_result = $mysql->selectData($query);
        if ($query_result["result"] == true) {
            $instances = array();
            foreach ($query_result["data"] as $key => $value) {

                $instance = self::fromDatabase($value);
                $instance->scoreArray = Score::getSetFromDatabase($instance->id, $mysql, false);
                $instances[] =  $instance;
            }
            $query_result = $instances;
        }

        return $query_result;
    }

    /**
     * Обновляет команду
     * @param $mysql MysqlHelper
     * @return array
     */
    public function updateInDatabase($mysql){
        $query = "UPDATE `".TABLE_TEAMS."` SET ".
            "`team_name`='".$this->name."', ".
            "`city`='".$this->city."', ".
            "`comment`='".$this->comment."', ".
            "`captain_id`=".$this->captain_id.", ".
            "`player_2_id`=".$this->player_2_id.", ".
            "`player_3_id`=".$this->player_3_id.", ".
            "`player_4_id`=".$this->player_4_id.", ".
            "`player_5_id`=".$this->player_5_id.", ".
            "`last_operation`='".$this->lastOperation."', ".
            "updated_at=NOW() ".
            " WHERE id=".$this->id;
        $query_result = $mysql->executeQuery($query);
        //----------------------------------------
        $count = count($this->scoreArray);
        for ($i = 0; $i < $count; $i++){
            $item = $this->scoreArray[$i];

            if ($item->id > 0){
                $query = "UPDATE ".TABLE_TEAM_SCORES." SET ".
                    "total_value=".$item->value.", ".
                    "month_value=".$item->monthValue.", ".
                    "change_total=".$item->valueChange.", ".
                    "change_month=".$item->monthChange.", ".
                    "updated_at=NOW() where team_id=".$this->id." AND game_name='".$item->gameName."'; ";
                $result = $mysql->executeQuery($query);

            } else {
                $query = "INSERT INTO ".TABLE_TEAM_SCORES." (team_id, game_name, total_value, month_value, change_total, change_month) VALUES ".
                    "(".$this->id.", '".$item->gameName."', ".$item->value.", ".$item->monthValue.", ".$item->valueChange.", ".$item->monthChange." ); \n";

                $mysql->executeQuery($query);
                $item->id = $mysql->getLastInsertedId();
            }

        }
        return $query_result;
    }


    /**
     * Удаляет команду из системы
     * @param $mysql MysqlHelper
     * @return array|null
     */
    public function deleteFromDatabase($mysql){
        $scoreDelete = Score::deleteSetDatabase($this->id, $mysql, false);
        $instanceDelete = $mysql->deleteInstance($this->id, "id", TABLE_TEAMS);
        return $instanceDelete;
    }

    /**
     * Удаляет из списка видимых
     *
     * @param $mysql MysqlHelper
     * @param int $status
     * @return mixed
     */
    public function setActiveStatus($mysql, $status = 0){
        $query = "update ".TABLE_CLIENTS." set is_active=$status WHERE id=".$this->id;
        return $mysql->executeQuery($query);
    }


    static function filterInstancesFromDatabase($mysql, array $filterConditions, $condition, $withSort, $sortBy, $sortType)
    {
        // TODO: Implement filterInstancesFromDatabase() method.
        return self::getInstancesFromDatabase($mysql);
    }

    /**
     * Возвращает текстовое отображение ключеового поля объекта для заполнения в селект-списки
     * @return string
     */
    public function getKey()
    {
        return $this->id;
    }

    /**
     * Возвращает текстовое отображение объекта для заполнения в селект-списки
     * @return string
     */
    public function getValue()
    {
        $result = "[ID ".$this->id."] ".$this->name;
        return $result;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        $result = "[ID ".$this->id."] ".$this->name;
        return $result;
    }

    public function getScore($gameName)
    {
        $result = null;
        foreach ($this->scoreArray as $item) {
            if ($item->gameName != $gameName) continue;
            return $item;
        }
        return $result;
    }

    public function getLink()
    {
        return "/teams/view.php?id=".$this->id;
    }

    public function getClass()
    {
        return get_class($this);
    }

}