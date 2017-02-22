<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 11.02.2017
 * Time: 21:46
 */
abstract class CollectionHelper
{

    /**
     * ФОмрирует статистику и записывает ее в базу
     *
     * @param $mysql MysqlHelper
     * @param string $type
     * @return bool
     */
    public static function makeAStatistic($mysql, $type = Statistic::CLIENT_TYPE){
        $gameArray = Score::getGameArray();
        $result = true;
        foreach ($gameArray as $game) {

            switch ($type){
                case Statistic::CLIENT_TYPE:
                    $instances= $mysql->getClientRating($game, 0);
                    break;

                case Statistic::TEAM_TYPE:
                    $instances= $mysql->getTeamsForStatistic($game);
                    break;

                default:
                    $instances = null;
            }
            if (is_null($instances) || count($instances) == 0){
                ApplicationHelper::processError("Массив рейтинга оказался пустым. Функция возврата статистики $type game: $game");
                continue;
            }

            $statistic = Statistic::createInstance($type, $game);
            ApplicationHelper::debug(var_export($instances, true));
            $res = $statistic->addContent($instances);

            if ($res == true) {
                $insertResult = $statistic->insertToDatabase($mysql);
                $result = $result && $insertResult["result"];
            } else {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * Функция фильтрует переданный массив, возвращая только те значения, которые
     * @param array $array
     * @param $key
     * @param $value
     * @param $equalTo
     */
    public static function filterArray(array $array, $key, $value, $equalTo){

    }

    /**
     * @param $instances Statistic[]
     * @param $type
     * @return string
     */
    public static function constructStatisticFor($instances, $type = Statistic::CLIENT_TYPE){

        $content = "";
        $count = 0;


        foreach ($instances as $instance){

            if ($count == 3) continue;
            if ($type != $instance->type) continue;

            $game = $instance->game;
            $array = $instance->content;
            if (count($array) == 0) {
                continue;
            }
            $createdAt = $instance->getCreatedAt();
            $content .= "<h5>$game ($createdAt)</h5>\n";


            $content .= "<table class='table table-sm'>\n";
            $content .= "<thead>\n".
                "<tr>".
                "<th>#</th>".
                "<th>Имя</th>".
                "<th>Было</th>".
                "<th>Стало</th>".
                "<th>Разница</th>".
                "</tr>".
                "</thead>\n";
            $content .= "<tbody>";


            for ($i = 0; $i < count($array); $i++)  {
                $item = $array[$i];

                //$content .= var_export($item, true);
                $diff = $item["currentValue"] - $item["previousValue"];
                $row = "<tr>".
                    "<th scope='row'>".($i+1)."</th>".
                    "<td>".$item["name"]."</td>".
                    "<td>".$item["currentValue"]."</td>".
                    "<td>".$item["previousValue"]."</td>".
                    "<td>$diff</td>".
                    "</tr>\n";
                $content .= $row;
            }
            $content .= "</tbody></table><hr>\n";
            $count++;
        }


        return $content;
    }
}