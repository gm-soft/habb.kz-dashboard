<?php

/**
 * Играемый турнир, в котором могут участовать либо команды, либо игроки.
 */
class Tournament extends BaseInstance
{
    /** @var string Название турнира. Не более 100 символов */
    public $name;

    /** @var string Публичное описание турнира. Текстовое поле с ограниченим в 300 символов. */
    public $description;

    /** @var DateTime Дата начала турнира */
    public $beginDate;

    /** @var DateTime Дата закрытия регистрации турнира */
    public $registrationCloseDate;

    /** @var int Максимальное количество участников в турнире */
    public $participantMaxCount = 0;

    /** @var array Массив айдишников участников как стринговых переменных. Конвертируется в строку при записи в SQL */
    public $participantIdS = array();

    /** @var ITournamentParticipant[] Массив участников в турнире */
    public $participants = array();

    /** @var string Тип турнира. Может быть командный и личный */
    public $tournamentType = TournamentTypes::Teams;

    /** @var string|null  */
    public $challongeTournamentId = null;

    /** @var string|null  */
    public $gameName = null;

    /** @var int Кол-во участников турнира. Не участвует в базе данных */
    public $participantCount = 0;

    function __construct()
    {
        $this->updatedAt = new DateTime();
        $this->createdAt = new DateTime();
    }

    /**
     * @param $searchable - ID клиента или иное искомое значение
     * @param $mysql MysqlHelper
     * @param string $field - искомое поле. ID по умолчанию
     * @return Tournament|null
     */
    public static function getInstanceFromDatabase($searchable, $mysql, $field = "id")
    {
        $query = "SELECT * FROM ".TABLE_TOURNAMENTS." WHERE $field='$searchable'";

        $query_result = $mysql->executeQuery($query);
        if ($query_result["result"] == true && !is_null($query_result["data"])) {

            $instance = self::fromDatabase($query_result["data"]);

            return $instance;
        }
        return null;
    }

    public static function filterInstancesFromDatabase($mysql, array $filterConditions, $condition, $withSort, $sortBy, $sortType)
    {
        // TODO: Implement filterInstancesFromDatabase() method.
        return self::getInstancesFromDatabase($mysql);
    }

    /**
     * Возвращает массив турниров
     * @param $mysql MysqlHelper
     * @return Tournament[]|null
     */
    public static function getInstancesFromDatabase($mysql)
    {
        $query = "select * from ".TABLE_TOURNAMENTS;

        $query_result = $mysql->selectData($query);
        if ($query_result["result"] == true) {
            $instances = array();
            foreach ($query_result["data"] as $key => $value) {

                $instance = self::fromDatabase($value);
                $instances[] =  $instance;
            }
            $query_result = $instances;
        }

        return $query_result;
    }

    /**
     * @param MysqlHelper $mysql
     * @return mixed
     */
    public function insertToDatabase($mysql)
    {
        $participantIds = ApplicationHelper::joinArray($this->participantIdS);
        $query = "INSERT INTO `".TABLE_TOURNAMENTS."` (".
            "`name`, ".
            "`description`, ".
            "`begin_date`, ".
            "`reg_close_date`, ".
            "`participant_max_count`, ".
            "`participant_ids`, ".
            "`type`, ".
            "`challonge_tournament_id`, ".
            "`game_name`, ".
            "`comment`, ".
            "`last_operation` ".
            ") values (".
            "'$this->name',".
            "'$this->description',".
            "STR_TO_DATE('".date("Y-m-d H:i:s", $this->beginDate->getTimestamp())."','%Y-%m-%d %H:%i:%s'), ".
            "STR_TO_DATE('".date("Y-m-d H:i:s", $this->registrationCloseDate->getTimestamp())."','%Y-%m-%d %H:%i:%s'), ".
            " $this->participantMaxCount ".
            "'$participantIds', ".
            "'$this->tournamentType', ".
            "'$this->challongeTournamentId', ".
            "'$this->gameName', ".
            "'$this->comment', ".
            "'$this->lastOperation' ".
            ")";
        $query_result = $mysql->executeQuery($query);
        if ($query_result["result"] != true) return $query_result;

        $this->id = $mysql->getLastInsertedId();
        $query_result["data"] = $this->id;
        return $query_result;
    }

    /**
     * Удаляет сущность из системы
     *
     * @param $mysql MysqlHelper
     * @return array|null
     */
    public function deleteFromDatabase($mysql)
    {
        $instanceDelete = $mysql->deleteInstance($this->id, "id", TABLE_TOURNAMENTS);
        return $instanceDelete;
    }

    /**
     * @param MysqlHelper $mysql
     * @return mixed
     */
    public function updateInDatabase($mysql)
    {
        $participantIds = ApplicationHelper::joinArray($this->participantIdS);
        $query = "UPDATE ".TABLE_TOURNAMENTS." SET ".
            "name='$this->name', ".
            "description='$this->description',".
            "begin_date=STR_TO_DATE('".date("Y-m-d H:i:s", $this->beginDate->getTimestamp())."','%Y-%m-%d %H:%i:%s'), ".
            "reg_close_date=STR_TO_DATE('".date("Y-m-d", $this->registrationCloseDate->getTimestamp())."','%Y-%m-%d'), ".
            "participant_max_count=$this->participantMaxCount,".
            "participant_ids='$participantIds', ".
            "type='$this->tournamentType', ".
            "challonge_tournament_id='$this->challongeTournamentId', ".
            "game_name='$this->gameName', ".
            "comment='$this->comment',".
            "last_operation='$this->lastOperation', ".
            "updated_at=now() ".
            " WHERE id=".$this->id;
        $query_result = $mysql->executeQuery($query);
        return $query_result;
    }

    public function fill(array $row)
    {
        $this->id = isset($row["id"]) ? $row["id"] : $this->id ;
        $this->name = $row["name"];
        $this->description = $row["description"];


        $this->beginDate = DateTime::createFromFormat("Y-m-d H:i:s", $row["begin_date"]);
        $this->registrationCloseDate = DateTime::createFromFormat("Y-m-d H:i:s", $row["reg_close_date"]);

        $this->participantMaxCount = intval($row["participant_max_count"]);
        $this->participantIdS = explode(",", $row["participant_ids"]);
        $this->tournamentType = TournamentTypes::getTournamentType($row["type"]);
        $this->challongeTournamentId = $row["challonge_tournament_id"];

        $this->lastOperation = $row["last_operation"];
        $this->gameName = $row["game_name"];

        $this->createdAt = isset($row["created_at"]) ? DateTime::createFromFormat("Y-m-d H:i:s", $row["created_at"]) : $this->createdAt;
        $this->updatedAt = isset($row["updated_at"]) ? DateTime::createFromFormat("Y-m-d H:i:s", $row["updated_at"]) : $this->updatedAt;
        $this->comment = isset($row["comment"]) ? $row["comment"] : $this->comment;
    }

    private function initiateParticipantArray(){
        // $count = count($this->participantIdS);
        $mysql = MysqlHelper::getInstance();

        foreach ($this->participantIdS as $participantId) {
            $participant = null;
            if ($this->tournamentType == TournamentTypes::Gamers){
                $participant = Gamer::getInstanceFromDatabase($participantId, $mysql);
            } else {
                $participant = Team::getInstanceFromDatabase($participantId, $mysql);
            }
            if (is_null($participant)) {
                ApplicationHelper::processError("Участник турнира ID$participantId ($this->tournamentType) отсутствует в базе");
                continue;
            }
            $this->participants[] = $participant;
        }
        $this->participantCount = count ($this->participants);
        return true;

    }

    /**
     * @param array $row
     * @return Tournament
     */
    public static function fromDatabase(array $row)
    {
        $instance = new self();
        $instance->fill( $row );
        $instance->initiateParticipantArray();
        return $instance;
    }

    public function getAsFormArray()
    {
        $beginDate = date("Y-m-d H:i:s", $this->beginDate->getTimestamp());
        $regCloseDate = date("Y-m-d H:i:s", $this->registrationCloseDate->getTimestamp());


        $result = [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "begin_date" => $beginDate,
            "reg_close_date" => $regCloseDate,
            "participant_max_count" => $this->participantMaxCount,
            "participant_ids" => $this->participantIdS,
            "type" => $this->tournamentType,
            "challonge_tournament_id" => $this->challongeTournamentId,
            "game_name" => $this->gameName,
            "comment" => $this->comment
        ];
        return $result;
    }


}