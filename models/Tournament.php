<?php

/**
 * Играемый турнир, в котором могут участовать либо команды, либо игроки.
 */
class Tournament extends BaseInstance
{
    /** @var string Название турнира. Не более 100 символов */
    public $title;

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

    /** @var array Массив участников в турнире */
    public $participants = array();

    /** @var string Тип турнира. Может быть командный и личный */
    public $tournamentType = TournamentTypes::Teams;

    public $challongeTournamentId = null;


    static function getInstanceFromDatabase($searchable, $mysql, $searchField)
    {
        // TODO: Implement getInstanceFromDatabase() method.
    }

    static function filterInstancesFromDatabase($mysql, array $filterConditions, $condition, $withSort, $sortBy, $sortType)
    {
        // TODO: Implement filterInstancesFromDatabase() method.
        return self::getInstancesFromDatabase($mysql);
    }

    static function getInstancesFromDatabase($mysql)
    {
        // TODO: Implement getInstancesFromDatabase() method.
    }

    function insertToDatabase($mysql)
    {
        // TODO: Implement insertToDatabase() method.
    }

    function deleteFromDatabase($mysql)
    {
        // TODO: Implement deleteFromDatabase() method.
    }

    function updateInDatabase($mysql)
    {
        // TODO: Implement updateInDatabase() method.
    }

    public function fill(array $row)
    {
        // TODO: Implement fill() method.
    }
}