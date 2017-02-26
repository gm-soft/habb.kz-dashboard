<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 26.02.2017
 * Time: 10:17
 */
abstract class TournamentFactory
{
    const Gamer = "gamer";
    const Team = "team";

    /**
     * В случае отсутствия сущности или несовпадения типа сущности вернется null
     *
     * @param $id - ID сущности в базе данных
     * @param $type - тип/класс сущности
     * @param MySQLHelper|null $mysql
     * @return ITournamentParticipant|null
     */
    public static function getParticipantByType($id, $type, $mysql = null){
        $type = strtolower($type);
        $mysql = is_null($mysql) ? MysqlHelper::getInstance() : $mysql;

        switch ($type) {
            case self::Gamer:
                $instance = Gamer::getInstanceFromDatabase($id, $mysql);
                break;
            case self::Team:
                $instance = Team::getInstanceFromDatabase($id, $mysql);
                break;
            default:
                $instance = null;
                break;

        }
        return $instance;
    }
}