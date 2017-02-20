<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 18.02.2017
 * Time: 17:30
 */
class Challonge
{
    /** @var  ChallongeAPI */
    public static $challonge = null;

    /** @var array */
    public static $errors;

    public static function Init($apiKey){
        self::$challonge = new ChallongeAPI($apiKey);
        self::$errors = array();
    }

    public static function getTournaments($params=array()) {

        return self::$challonge->makeCall('tournaments', $params, 'get');
    }

    public static function getTournament($tournament_id, $params=array()) {
        return self::$challonge->makeCall("tournaments/$tournament_id", $params, "get");
    }

    public static function createTournament($params=array()) {
        if (sizeof($params) == 0) {
            self::$errors = array('$params empty');
            return false;
        }
        return self::$challonge->makeCall("tournaments", $params, "post");
    }

    public static function updateTournament($tournament_id, $params=array()) {
        return self::$challonge->makeCall("tournaments/$tournament_id", $params, "put");
    }

    public static function deleteTournament($tournament_id) {
        return self::$challonge->makeCall("tournaments/$tournament_id", array(), "delete");
    }

    public static function publishTournament($tournament_id, $params=array()) {
        return self::$challonge->makeCall("tournaments/publish/$tournament_id", $params, "post");
    }

    public static function startTournament($tournament_id, $params=array()) {
        return self::$challonge->makeCall("tournaments/start/$tournament_id", $params, "post");
    }

    public static function resetTournament($tournament_id, $params=array()) {
        return self::$challonge->makeCall("tournaments/reset/$tournament_id", $params, "post");
    }


    public static function getParticipants($tournament_id) {
        return self::$challonge->makeCall("tournaments/$tournament_id/participants");
    }

    public static function getParticipant($tournament_id, $participant_id, $params=array()) {
        return self::$challonge->makeCall("tournaments/$tournament_id/participants/$participant_id", $params);
    }

    public static function createParticipant($tournament_id, $params=array()) {
        if (sizeof($params) == 0) {
            self::$errors = array('$params empty');
            return false;
        }
        return self::$challonge->makeCall("tournaments/$tournament_id/participants", $params, "post");
    }

    public static function updateParticipant($tournament_id, $participant_id, $params=array()) {
        return self::$challonge->makeCall("tournaments/$tournament_id/participants/$participant_id", $params, "put");
    }

    public static function deleteParticipant($tournament_id, $participant_id) {
        return self::$challonge->makeCall("tournaments/$tournament_id/participants/$participant_id", array(), "delete");
    }

    public static function randomizeParticipants($tournament_id) {
        return self::$challonge->makeCall("tournaments/$tournament_id/participants/randomize", array(), "post");
    }


    public static function getMatches($tournament_id, $params=array()) {
        return self::$challonge->makeCall("tournaments/$tournament_id/matches", $params);
    }

    public static function getMatch($tournament_id, $match_id) {
        return self::$challonge->makeCall("tournaments/$tournament_id/matches/$match_id");
    }

    public static function updateMatch($tournament_id, $match_id, $params=array()) {
        if (sizeof($params) == 0) {
            self::$errors = array('$params empty');
            return false;
        }
        return self::$challonge->makeCall("tournaments/$tournament_id/matches/$match_id", $params, "put");
    }
}