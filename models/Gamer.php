<?php

/** Gamer
 * Модель представляет аккаунт игрока в системе HABB. Может участовать в командах, турнирах
*/
class Gamer extends BaseInstance implements ISelectableOption, ITournamentParticipant
{

    /** @var string Имя клиента */
    public $name;

    /** @var string Фамилия клиента */
    public $last_name;

    /** @var DateTime День рождения (SQL) */
    public $birthday;

    /** @var string Номер телефона. Считается уникальным полем */
    public $phone;

    /** @var string Email. Считается уникальным полем */
    public $email;

    /** @var string Город проживания */
    public $city;

    /** @var string Ссылка на профиль в VK.COM */
    public $vk;

    /** @var string Статус клиента: студент, школьник, работает */
    public $status;

    /** @var string Институт, где занят клиент: школа, университет, место работы */
    public $institution;

    /** @var string Активно играет в игру */
    public $primary_game;

    /** @var string[] Массив дополнительных игр, перечисленных через запятую */
    public $secondary_games;

    /** @var string ID лида в битриксе */
    public $lead_id = '0';

    /** @var Score[] */
    public $scoreArray;


    function __construct($id = -1)
    {
        $this->id = $id;
        $this->name = "";
        $this->last_name = "";

        $this->phone = "";

        $this->email = null;
        $this->city = null;

        $this->vk = "";

        $this->status = "";
        $this->institution = "";

        $this->primary_game = "";
        $this->secondary_games = [];

        $this->lead_id = "";
        $this->scoreArray = Score::getDefaultSet($this->id);
    }

    public static function getEmptyClient($phone, $email){
        $instance = new self();
        $instance->phone = $phone;
        $instance->email = $email;
        $instance->scoreArray = Score::getDefaultSet($instance->id);
        return $instance;
    }

    public static function fromRequest(array $request)
    {
        $instance = new self();
        $instance->fill( $request );

        if (isset( $request["score_id"])){

            $scoreArray = [];
            $scoreId = $request["score_id"];
            $gameName = $request["game_name"];
            $totalValue = $request["total_value"];
            $monthValue = $request["month_value"];
            $totalChange = $request["change_total"];
            $monthChange = $request["change_month"];


            for ($i = 0; $i < count($scoreId); $i++){
                $item = new Score($instance->id, $gameName[$i]);
                $item->id = $scoreId[$i];
                $item->value = $totalValue[$i];
                $item->valueChange = $totalChange[$i];
                $item->monthValue = $monthValue[$i];
                $item->monthChange = $monthChange[$i];

                $scoreArray[] = $item;
            }
            $instance->scoreArray = $scoreArray;
            //$defaultScore = Score::getScoreByGame(SCORE_DEFAULT, $instance->scoreArray);

        } else {
            ApplicationHelper::debug("score_id is default");
            $instance->scoreArray = Score::getDefaultSet($instance->id);
            //$defaultScore = Score::getScoreByGame(SCORE_DEFAULT, $instance->scoreArray);

        }


        return $instance;
    }


    public function fill( array $row )
    {
        $this->id = isset($row["id"]) ? $row["id"] : $this->id ;
        $this->name = $row["name"];

        $this->last_name = $row["last_name"];
        $this->birthday = DateTime::createFromFormat("Y-m-d H:i:s", $row["birthday"]);
        if ($this->birthday === false) {
            $this->birthday = DateTime::createFromFormat("Y-m-d", $row["birthday"]);
        }

        $this->phone = $row["phone"];
        $this->email = $row["email"];
        $this->city = $row["city"];

        $this->vk = $row["vk"];

        $this->status = $row["status"];
        $this->institution = $row["institution"];

        $this->primary_game = $row["primary_game"];
        $this->secondary_games = explode(", ", $row["secondary_games"]);

        $this->lead_id = isset($row["lead_id"]) ? $row["lead_id"] : $this->lead_id;

        $this->createdAt = isset($row["created_at"]) ? DateTime::createFromFormat("Y-m-d H:i:s", $row["created_at"]) : $this->createdAt;
        $this->lead_id = isset($row["lead_id"]) ? $row["lead_id"] : $this->lead_id;
        $this->comment = isset($row["comment"]) ? $row["comment"] : $this->comment;
    }

    public static function fromDatabase(array $row)
    {
        $instance = new self();
        $instance->fill( $row );
        return $instance;
    }

    public function getAsFormArray(){

        $birthday = date("Y-m-d", $this->birthday->getTimestamp());

        $result = [
            "id" => $this->id,
            "name" => $this->name,
            "last_name" => $this->last_name,
            "birthday" => $birthday,
            "phone" => $this->phone,
            "email" => $this->email,
            "city" => $this->city,
            "institution" => $this->institution,
            "vk" => $this->vk,
            "status" => $this->status,
            "primary_game" => $this->primary_game,
            "secondary_games" => $this->secondary_games,
            "lead_id" => $this->lead_id,
            "created_at" => $this->createdAt,
            "comment" => $this->comment ,
            "score_array" => $this->scoreArray
        ];
        return $result;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        $full = $this->name." ".$this->last_name;
        return $full;
    }

    /**
     * Возвращает возраст клиента
     * @return int
     */
    public function getAge(){
        $result = intval((time() - $this->birthday->getTimestamp()) / (365*60*60*24));
        return $result;
    }

    public function getSecondaryGamesString(){
        return join(", ", $this->secondary_games);
    }


    /**
     * Вставляет запись в БД
     * @param $mysql MysqlHelper
     * @return array
     */
    public function insertToDatabase($mysql){

        $sec_games = $this->getSecondaryGamesString();
        $query = "insert into `".TABLE_CLIENTS."` (".
            "`name`, ".
            "`last_name`, ".
            "`phone`, ".
            "`birthday`, ".
            "`email`, ".
            "`city`, ".
            "`vk`, ".
            "`status`, ".
            "`institution`, ".
            "`primary_game`, ".
            "`secondary_games` ".
            ") values (".
            "'".$this->name."',".
            "'".$this->last_name."',".
            "'".$this->phone."',".
            "'".date("Y-m-d", $this->birthday->getTimestamp())."',".
            "'".$this->email."',".
            "'".$this->city."',".
            "'".$this->vk."',".
            "'".$this->status."',".
            "'".$this->institution."',".
            "'".$this->primary_game."',".
            "'".$sec_games."'".
            ")";
        $query_result = $mysql->executeQuery($query);
        if ($query_result["result"] != true) return $query_result;

        $this->id = $mysql->getLastInsertedId();
        $query_result["data"] = $this->id;

        $query = "INSERT INTO ".TABLE_SCORES." (client_id, game_name) VALUES ";
        $count = count($this->scoreArray);
        //--------------------
        for ($i = 0; $i < $count; $i++){
            $item = $this->scoreArray[$i];
            $query .= "(".$this->id.", '".$item->gameName."')";
            if ($i != $count - 1) $query .= ", ";
        }

        $query .= " ON DUPLICATE KEY UPDATE id=id";
        $subResult = $mysql->executeQuery($query);
        $query_result["sub"] = $subResult;

        return $query_result;
    }


    /**
     * Удаляет пользователя из системы
     *
     * @param $mysql MysqlHelper
     * @return array|null
     */
    public function deleteFromDatabase($mysql){

        $instanceDelete = $mysql->deleteInstance($this->id, "id", TABLE_CLIENTS);
        $scoreDelete = Score::deleteSetDatabase($this->id, $mysql);
        return $instanceDelete;
    }

    /**
     * @param $mysql MysqlHelper
     * @return array
     */
    public function updateInDatabase($mysql){

        $sec_games = $this->getSecondaryGamesString();
        $query = "UPDATE `".TABLE_CLIENTS."` SET ".
            "`name`='"      .$this->name."', ".
            "`last_name`='" .$this->last_name."',".
            "`phone`='"     .$this->phone."',".
            "`birthday`=STR_TO_DATE('".date("Y-m-d", $this->birthday->getTimestamp())."','%Y-%m-%d'),".
            "`email`='".$this->email."',".
            "`city`='".$this->city."',".
            "`vk`='".$this->vk."',".
            "`status`='".$this->status."',".
            "`institution`='".$this->institution."',".
            "`primary_game`='".$this->primary_game."',".
            "`secondary_games`='".$sec_games."', ".
            "`comment`='".$this->comment."', ".
            "`lead_id`='".$this->lead_id."' ".
            " WHERE id=".$this->id;
        $query_result = $mysql->executeQuery($query);

        $count = count($this->scoreArray);
        for ($i = 0; $i < $count; $i++){
            $item = $this->scoreArray[$i];

            if ($item->id > 0){
                $query = "UPDATE ".TABLE_SCORES." SET ".
                    "total_value=".$item->value.", ".
                    "month_value=".$item->monthValue.", ".
                    "change_total=".$item->valueChange.", ".
                    "change_month=".$item->monthValue.", ".
                    "updated_at=NOW() where client_id=".$this->id." AND game_name='".$item->gameName."'; ";
                $mysql->executeQuery($query);
            } else {
                $query = "INSERT INTO ".TABLE_SCORES." (client_id, game_name, total_value, month_value, change_total, change_month) VALUES ".
                    "(".$this->id.", '".$item->gameName."', ".$item->value.", ".$item->monthValue.", ".$item->valueChange.", ".$item->monthChange." ); \n";

                $mysql->executeQuery($query);
                $item->id = $mysql->getLastInsertedId();
            }

        }

        return $query_result;
    }

    /**
     * @param $searchable - ID клиента или иное искомое значение
     * @param $mysql MysqlHelper
     * @param string $field - искомое поле. ID по умолчанию
     * @return Gamer|null
     */
    public static function getInstanceFromDatabase($searchable, $mysql, $field = "id"){
        $query = "SELECT * FROM ".TABLE_CLIENTS." WHERE $field='$searchable'";

        $query_result = $mysql->executeQuery($query);
        if ($query_result["result"] == true && !is_null($query_result["data"])) {

            $instance = self::fromDatabase($query_result["data"]);
            $instance->scoreArray = Score::getSetFromDatabase($instance->id, $mysql);

            return $instance;
        }
        return null;
    }


    /**
     * Получение списка всех записей с возможностью пагинации
     *
     * @param $mysql MysqlHelper
     * @return Gamer[]|null
     */
    public static function getInstancesFromDatabase($mysql){

        $query = "SELECT * FROM ".TABLE_CLIENTS. "  WHERE is_active=1";

        $query_result = $mysql->selectData($query);
        if ($query_result["result"] == true) {
            $instances = [];
            foreach ($query_result["data"] as $key => $value) {


                $instance = self::fromDatabase($value);
                $instance->scoreArray = Score::getSetFromDatabase($instance->id, $mysql);
                $instances[] =  $instance;
            }
            $query_result = $instances;
        } else {
            $query_result = null;
        }

        return $query_result;
    }

    /**
     * Возвращает массив всех стим-аккаунтов с фильтрацией
     *
     * @param $mysql MysqlHelper
     * @param array $filterConditions - массив условий вида "id = 1"
     * @param string $condition - соединитель условий: AND/OR
     * @param bool $withSort
     * @param string $sortBy
     * @param string $sortType
     * @return Gamer[]|null
     */
    public static function filterInstancesFromDatabase($mysql, array $filterConditions, $condition = "AND", $withSort = false, $sortBy = "id", $sortType = "DESC"){
        $query = "SELECT * FROM ".TABLE_CLIENTS." ";

        $query .= " WHERE ";
        $filterConditions[] = "is_active=1";
        $filterConditionCount = count($filterConditions);
        for ($i = 0; $i < $filterConditionCount; $i++){
            $field = $filterConditions[$i];

            $query .= $field;
            if ($i != $filterConditionCount - 1) {
                $query .= " ".$condition. " ";
            }
        }

        if ($withSort == true){
            $query .= " ORDER BY $sortBy $sortType";
        }

        $query_result = $mysql->selectData($query);

        if ($query_result["result"] == true) {
            $instances = [];
            foreach ($query_result["data"] as $key => $value) {


                $instance = self::fromDatabase($value);
                $instance->scoreArray = Score::getSetFromDatabase($instance->id, $mysql);
                $instances[] =  $instance;
            }
            $query_result = $instances;
        } else {
            $query_result = null;
        }

        return $query_result;
    }

    /**
     * Возвращает массив команд, где участвует игрок
     * Массив вида [id, name, value, change]
     *
     * @param $mysql MysqlHelper
     * @param $gameName
     * @return array|null
     */
    public function getTeamsForClient($mysql, $gameName){
        $query = "SELECT ".TABLE_TEAMS.".id, ".TABLE_TEAMS.".team_name, ".TABLE_TEAM_SCORES.".total_value, ".TABLE_TEAM_SCORES.".change_total FROM ".TABLE_TEAMS."  
        LEFT JOIN 
           ".TABLE_TEAM_SCORES." ON ".TABLE_TEAMS.".id=".TABLE_TEAM_SCORES.".team_id AND ".TABLE_TEAM_SCORES.".game_name='$gameName' 
        WHERE ".TABLE_TEAMS.".captain_id=".$this->id." OR ".
            TABLE_TEAMS.".player_2_id=".$this->id." OR ".
            TABLE_TEAMS.".player_3_id=".$this->id." OR ".
            TABLE_TEAMS.".player_4_id=".$this->id." OR ".
            TABLE_TEAMS.".player_5_id=".$this->id." ";
        $query_result = $mysql->selectData($query);

        if ($query_result["result"] == true) {
            $instances = [];
            foreach ($query_result["data"] as $key => $value) {


                $instance = [
                    "id" => $value["id"],
                    "name" => $value["team_name"],
                    "value" => $value["total_value"],
                    "change" => $value["change_total"]
                ];
                $instances[] =  $instance;
            }
            $query_result = $instances;
        } else {
            $query_result = null;
        }

        return $query_result;
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

    public function getKey()
    {
        return $this->id;
    }

    public function getValue()
    {
        $result = "[ID ".$this->id."] ".$this->getFullName(). " ($this->phone)";
        return $result;
    }

    /**
     *
     */
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->getFullName();
    }

    /**
     * Возвращает очки по игре. Если не найдены, то null
     * @param string $gameName
     * @return null|Score
     */
    public function getScore($gameName)
    {
        $result = null;
        foreach ($this->scoreArray as $item) {
            if ($item->gameName != $gameName) continue;
            return $item;
        }
        return $result;
    }

    /**
     * Возвращает линк на себя для встраивания в ссылки
     * @return string
     */
    public function getLink()
    {
        return "/gamers/view.php?id=".$this->id;
    }

    /**
     * Возвращает строковое отображение названия класса
     * @return string
     */
    public function getClass()
    {
        return get_class($this);
    }
}