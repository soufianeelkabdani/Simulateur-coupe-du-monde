<?php

function OpositeTeam($array, $firstteam)
{
    $arrayData = $array;
    unset($arrayData[$firstteam]);
    unset($arrayData["joue"]);
    foreach ($arrayData as $key => $value) {
        return $key;
    }
}
// result array here will be the matches (the array containing all the matches)
function createTeams($resultsArray)
{
    $Teams = [];
    // this codes used to get the available teams from the resultsArray ;
    $TheTeamsKeys = array();
    // key will be the teamvsteam2
    // val will be the array containing the goals scored by each team and if the match played
    foreach ($resultsArray as $key => $val) {
        $contries = array();
        $values = array();
        // valKey will be the the names of the two teams playing against each other and also the played
        // miniVAL will be the the score of each team and also the value of the played
        foreach ($val as $valkey => $miniVAL) {
            // countries will take the names of the both teams and also the played
            array_push($contries, $valkey);
            // values will take the score of each team and also the played value
            array_push($values, $miniVAL);
            // theTeamsKeys array will take the value of the two teams playing against each other and also the played but after we'll the delete the played         
            array_push($TheTeamsKeys, $valkey);
        }
    }
    // here we're deleting the played from the theTeamsKeys array
    foreach ($TheTeamsKeys as $key => $value) {
        if ($value == "joue") {
            unset($TheTeamsKeys[$key]);
        }
    }
    // here we're deleting the duplicated teams from theTeamsKeys array and we're indexing the array numerically
    // array_values here is useless cause the array elements already indexed
    $TheTeamsKeys = array_values(array_unique($TheTeamsKeys));
    foreach ($TheTeamsKeys as $value) {
        $Teams += [$value => array("POINTS" => 0, "GAMES_PLAYED" => 0, "GAMES_WON" => 0, "GAMES_EQUAL" => 0, "GAME_LOSTS" => 0, "GOALS_SCORED" => 0, "GOALS_RECEIVED" => 0, "DIFF" => 0)];
    }
    // teams is an array containing info about each team 
    // key is the name of the country   
    foreach ($Teams as $key => $value) {
        $GAMES_PLAYED = 0;
        $GAMES_WON = 0;
        $GAMES_EQUAL = 0;
        $GAME_LOSTS = 0;
        $POINTS = ($GAMES_WON * 3) + ($GAMES_EQUAL * 1);
        $GOALS_SCORED = 0;
        $GOALS_RECEIVED = 0;
        $DIFF = $GOALS_SCORED - $GOALS_RECEIVED;
        // dataValue here is storing the info about the scores of both teams and if the game played or not         
        foreach ($resultsArray as $DataKey => $DataValue) {
            // condition : if score of team is not 0             
            if (isset($DataValue[$key])) {
                $GOALS_SCORED += $DataValue[$key];
                $GOALS_RECEIVED += $DataValue[OpositeTeam($DataValue, $key)];
                $DIFF = $GOALS_SCORED - $GOALS_RECEIVED;
                if ($DataValue["joue"] == true) {
                    $GAMES_PLAYED += 1;
                }
                if ($DataValue[$key] > $DataValue[OpositeTeam($DataValue, $key)]) {
                    $GAMES_WON += 1;
                } elseif ($DataValue[$key] < $DataValue[OpositeTeam($DataValue, $key)]) {
                    $GAME_LOSTS += 1;
                } elseif ($DataValue[$key] == $DataValue[OpositeTeam($DataValue, $key)]) {
                    $GAMES_EQUAL += 1;
                }
            }
        }
        $Teams[$key]["GOALS_SCORED"] = $GOALS_SCORED;
        $Teams[$key]["GOALS_RECEIVED"] = $GOALS_RECEIVED;
        $Teams[$key]["DIFF"] = $DIFF;
        $Teams[$key]["GAMES_PLAYED"] = $GAMES_PLAYED;
        $Teams[$key]["GAMES_WON"] = $GAMES_WON;
        $Teams[$key]["GAME_LOSTS"] = $GAME_LOSTS;
        $Teams[$key]["GAMES_EQUAL"] = $GAMES_EQUAL;
        $Teams[$key]["POINTS"] = ($GAMES_WON * 3) + ($GAMES_EQUAL * 1);
    }
    return changeFromData($Teams);
};

function changeFromData($data)
{
    foreach ($data as $key => $value) {
        foreach ($value as $xkey => $xvalue) {
            $data[$key]["Team"] = $key;
        }
    }
    $bestArrayForm = [];
    foreach ($data as $key => $value) {
        array_push($bestArrayForm, $value);
    }
    return $bestArrayForm;
}

function trierTable($data)
{
    global $matches;
    usort($data, function ($x, $y) {
        global $matches;
        if ($x["POINTS"] === $y["POINTS"]) {
            if ($x["DIFF"] === $y["DIFF"]) {
                if ($x["GOALS_SCORED"] === $y["GOALS_SCORED"]) {
                    foreach ($matches as $matcheKey => $matcheValue) {
                        if (isset($matcheValue[$x["Team"]])  && isset($matcheValue[$y["Team"]])) {
                            if ($matcheValue[$x["Team"]] === $matcheValue[$y["Team"]]) {
                                return 0;
                            } else if ($matcheValue[$x["Team"]] < $matcheValue[$y["Team"]]) {
                                return 1;
                            } else if ($matcheValue[$x["Team"]] > $matcheValue[$y["Team"]]) {
                                return -1;
                            }
                        }
                    }
                } else if ($x["GOALS_SCORED"] < $y["GOALS_SCORED"]) {
                    return 1;
                } else if ($x["GOALS_SCORED"] > $y["GOALS_SCORED"]) {
                    return -1;
                }
            } else if ($x["DIFF"] < $y["DIFF"]) {
                return 1;
            } else if ($x["DIFF"] > $y["DIFF"]) {
                return -1;
            }
        } else if ($x["POINTS"] < $y["POINTS"]) {
            return 1;
        } else if ($x["POINTS"] > $y["POINTS"]) {
            return -1;
        }
    });
    return $data;
}

