<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 08.02.2017
 * Time: 15:51
 */
class HtmlHelper
{
    /**
     * @param string $tdContent
     * @param int $tdCount
     * @param int $contentPos
     * @return string
     */
    public static function constructRow($tdContent, $tdCount = 4, $contentPos = 2){

        $content = "<tr class='bg-custom'>";
        for ($i = 1; $i <= $tdCount; $i++){
            $content.= $i == $contentPos ? "<td><b>$tdContent</b></td>" : "<td></td>";
        }
        return $content;
    }

    /**
     * @param $scoreChange mixed
     * @return string
     */
    public static function WrapScoreValueChange($scoreChange){
        $scoreChangeValue = intval($scoreChange);
        $class = $scoreChangeValue >= 0 ? "text-success" : "text-danger";
        $textChanged = $scoreChangeValue >= 0 ? "+".$scoreChange : $scoreChange;
        return "<span class='$class'>$textChanged</span>";
    }

    /**
     * @param $instance
     * @param $position
     * @return string
     */
    public static function constructRowForPersonalInstancePublic($instance, $position){

        $content = "";
        $content .= "<tr>";
        $content .= "<td>".($position)."</td>";
        $content .= "<td>ID".$instance["id"]." <b>".$instance["name"]."</b></a></td>";

        $changeWrap= HtmlHelper::WrapScoreValueChange($instance["change"]);
        $content .= "<td>".$instance["value"]." (".$changeWrap.")</td>";

        $text = HtmlHelper::WrapScoreValueChange($instance["monthChange"]);
        $content .= "<td>".$text."</td>\n";

        $content .= "</tr>";
        return $content;
    }

    /**
     * @param $instance
     * @param $position
     * @return string
     */
    public static function constructRowForTeamInstancePublic($instance, $position){

        $team = $instance["team"];
        $players = $instance["players"];

        $changeWrap= HtmlHelper::WrapScoreValueChange($team["change"]);

        $content = "";
        $content .= "<tr>";
        $content .= "<td>".($position)."</td>";
        $content .= "<td>ID".$team["id"]."<b> ".$team["name"]."</b></td>";
        $content .= "<td>".$team["value"]." (".$changeWrap.")</td>";

        $text = HtmlHelper::WrapScoreValueChange($team["monthChange"]);
        $content .= "<td>".$text."</td>\n";

        $content .= "<td><b>".$players[0]["name"]."</b><br>ID".$players[0]["id"].". Рейтинг: ".$players[0]["value"]."</td>";

        for ($n = 1; $n < count($players); $n++){
            if (!is_null($players[$n]["id"])) {
                $content .= "<td><b>".$players[$n]["name"]."</b><br>ID".$players[$n]["id"].". Рейтинг: ".$players[$n]["value"]."</td>";
            }
            else {
                $content .= "<td>Отсутствует</td>";
            }
        }

        $content .= "</tr>";
        return $content;
    }


    /**
     * @param $currentGame string
     * @param $link string
     * @return string
     */
    public static function getRatingGameButtons($currentGame, $link){
        $content = "<div class='float-sm-right'>\n";
        $content .= "<div class='btn-group' role='group' aria-label='navigation'>\n";

        $games = Score::getGameArray();
        foreach ($games as $game){
            $class = $game == $currentGame ? "primary" : "secondary";
            $disableState = ($game == Score::SCORE_CSGO) ? "" : "disabled";
            $content .= "<a href='$link?game=$game'  class='btn btn-$class $disableState'>".$game."</a>\n";
        }
        $content .= "</div>\n";
        $content .= "</div>\n";
        return $content;
    }


    /**
     * @param $currentGame string
     * @return string
     */
    public static function getGameTitle($currentGame){
        switch ($currentGame) {

            case Score::SCORE_DOTA:
                $gameTitle = "DOTA2";
                break;

            case Score::SCORE_CSGO:
                $gameTitle = "CS:GO";
                break;

            case Score::SCORE_HEARTHSTONE:
                $gameTitle = "Hearthstone";
                break;

            default:
                $gameTitle = "";
                break;
        }
        return $gameTitle;
    }

    /**
     * @param $instances array
     * @param string $type
     * @return array
     */
    public static function sortInstancesByValue($instances, $type = "clients"){
        $normal = [];
        $bellow = [];

        for ($i = 0; $i < count($instances); $i++) {

            $instance = $instances[$i];
            if ($type == "teams") {
                $value = intval($instance["team"]["value"]);
            } else {
                $value = intval($instance["value"]);
            }

            if ($value < 5) {
                $bellow[] = $instance;
            } else {
                $normal[] = $instance;
            }
        }
        return ["normal" => $normal, "bellow" => $bellow];
    }

    /**
     * Формирует Select-2 список игроков
     *
     * @param $clients Client[]
     * @param $fieldName string
     * @param $fieldId string|null
     * @param array|null $formData
     * @param bool $isRequired
     * @return string
     */
    public static function constructClientSelectField($clients, $fieldName, $fieldId = null, array $formData = null, $isRequired = false){

        $fieldId = !is_null($fieldId) ? $fieldId : $fieldName;
        $requiredState = $isRequired == true ? "required" : "";


        $content = "<select class='form-control select2-single' id='$fieldId' name='$fieldName' $requiredState>\n";
        $content .= $isRequired == false ? "<option value='null'>Без игрока</option>\n" : "<option value=''>Выберите аккаунт</option>\n";

        foreach ($clients as $client) {
            $selected = !is_null($formData) && $client->id == $formData[$fieldName] ? "selected" : "";

            $optionText = "[ID ".$client->id."] ".$client->getFullName() ." (".$client->phone.")";
            $optionText = "<option value='".$client->id."' $selected>$optionText</option>\n";
            $content .= $optionText;
        }
        $content .= "</select>";
        return $content;

    }

    /**
     * @param string $fieldName
     * @param string|null $fieldId
     * @param array|null $userGames
     * @param bool $isRequired
     * @param bool $isMultiple
     * @return string
     */
    public static function constructGameSelectField($fieldName, $fieldId = null, $userGames = null, $isRequired = false, $isMultiple = false){
        $fieldId = !is_null($fieldId) ? $fieldId : $fieldName;
        $fieldName = $isMultiple == true ? $fieldName."[]" : $fieldName;
        $userGames = is_array($userGames) ? $userGames : [$userGames];

        $multipleAttr = $isMultiple == true ? "multiple='multiple'" : "";
        $classAttr = $isMultiple == true ? "multiple" : "single";

        $requiredState = $isRequired == true ? "required" : "";

        $content = "<select class='form-control select2-$classAttr' id='$fieldId' name='$fieldName' $requiredState $multipleAttr>\n";

        $content .= "<option value='' disabled>Выберите игру</option>|";

        $gameIds = explode(",", "dota,cs:go,lol,hearthstone,wot,overwatch,cod");
        $gameTitles = explode(",", "Dota2,CS:GO,League of Legends,Hearthstone,World of Tanks,Overwatch,Call of Duty (серия игр)");

        for ($i = 0; $i < count($gameIds);$i++) {
            $value = $gameIds[$i];
            $title = $gameTitles[$i];

            $selected = in_array($value, $userGames)  ? "selected" : "";
            $content .= "<option value='$value' $selected>$title</option>\n";
        }
        $content .= "</select>";

        return $content;
    }

    public static function constructCitiesSelect($selectedCity = null) {
        $cities = ApplicationHelper::getCities();
        $content = "<select class='form-control' name='city' required>\n";
        $content .= "<option value='' disabled>Город</option>\n";

        for ($i = 0; $i<count($cities); $i++) {

            $selectedState = $selectedCity == $cities[$i] ? "selected" : "";
            $content .= "<option value='$cities[$i]' $selectedState>$cities[$i]</option>";
        }
        $content .= "</select>";
        return $content;
    }
}




















