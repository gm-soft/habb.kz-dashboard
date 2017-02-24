<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 24.02.2017
 * Time: 13:48
 */
interface ITournamentParticipant
{
    public function getId();
    public function getName();
    public function getScore($gameName);
    public function getLink();
    public function getClass();
}