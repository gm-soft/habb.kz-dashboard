<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 22.02.2017
 * Time: 15:29
 */
interface IDatabaseObject
{
    public static function getInstanceFromDatabase($searchable, $mysql, $searchField);
    public static function filterInstancesFromDatabase($mysql, array $filterConditions, $condition, $withSort, $sortBy, $sortType);
    public static function getInstancesFromDatabase($mysql);

    public function insertToDatabase($mysql);
    public function deleteFromDatabase($mysql);
    public function updateInDatabase($mysql);

    function fill(array $row);
}