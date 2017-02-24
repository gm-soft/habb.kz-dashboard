<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 22.02.2017
 * Time: 17:23
 */
abstract class TournamentTypes {
    const Gamers = "gamers";
    const Teams = "teams";

    /**
     * Возвращает строковую переменную типа турнира
     *
     * @param mixed $type
     * @return null|string
     */
    public static function getTournamentType($type){
        switch ($type){
            case self::Gamers:
                return self::Gamers;
            case self::Teams:
                return self::Teams;
        }
        return null;
    }
}