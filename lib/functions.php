<?php
require_once(__DIR__ . "/db.php");
$BASE_PATH = '/Project/'; //This is going to be a helper for redirecting to our base project path since it's nested in another folder
function se($v, $k = null, $default = "", $isEcho = true)
{
    if (is_array($v) && isset($k) && isset($v[$k])) {
        $returnValue = $v[$k];
    } else if (is_object($v) && isset($k) && isset($v->$k)) {
        $returnValue = $v->$k;
    } else {
        $returnValue = $v;
        //added 07-05-2021 to fix case where $k of $v isn't set
        //this is to kep htmlspecialchars happy
        if (is_array($returnValue) || is_object($returnValue)) {
            $returnValue = $default;
        }
    }
    if (!isset($returnValue)) {
        $returnValue = $default;
    }
    if ($isEcho) {
        //https://www.php.net/manual/en/function.htmlspecialchars.php
        echo htmlspecialchars($returnValue, ENT_QUOTES);
    } else {
        //https://www.php.net/manual/en/function.htmlspecialchars.php
        return htmlspecialchars($returnValue, ENT_QUOTES);
    }
}
//TODO 2: filter helpers
function sanitize_email($email = "")
{
    return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
}
function is_valid_email($email = "")
{
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
}
//TODO 3: User Helpers
function is_logged_in($redirect = false, $destination = "login.php")
{
    $isLoggedIn = isset($_SESSION["user"]);
    if ($redirect && !$isLoggedIn) {
        flash("You must be logged in to view this page", "warning");
        die(header("Location: $destination"));
    }
    return $isLoggedIn; //se($_SESSION, "user", false, false);
}
function has_role($role)
{
    if (is_logged_in() && isset($_SESSION["user"]["roles"])) {
        foreach ($_SESSION["user"]["roles"] as $r) {
            if ($r["name"] === $role) {
                return true;
            }
        }
    }
    return false;
}
function get_username()
{
    if (is_logged_in()) { //we need to check for login first because "user" key may not exist
        return se($_SESSION["user"], "username", "", false);
    }
    return "";
}
function get_user_email()
{
    if (is_logged_in()) { //we need to check for login first because "user" key may not exist
        return se($_SESSION["user"], "email", "", false);
    }
    return "";
}
function get_user_id()
{
    if (is_logged_in()) { //we need to check for login first because "user" key may not exist
        return se($_SESSION["user"], "id", false, false);
    }
    return false;
}
//TODO 4: Flash Message Helpers
function flash($msg = "", $color = "info")
{
    $message = ["text" => $msg, "color" => $color];
    if (isset($_SESSION['flash'])) {
        array_push($_SESSION['flash'], $message);
    } else {
        $_SESSION['flash'] = array();
        array_push($_SESSION['flash'], $message);
    }
}

function getMessages()
{
    if (isset($_SESSION['flash'])) {
        $flashes = $_SESSION['flash'];
        $_SESSION['flash'] = array();
        return $flashes;
    }
    return array();
}
//TODO generic helpers
function reset_session()
{
    session_unset();
    session_destroy();
}
function users_check_duplicate($errorInfo)
{
    if ($errorInfo[1] === 1062) {
        //https://www.php.net/manual/en/function.preg-match.php
        preg_match("/Users.(\w+)/", $errorInfo[2], $matches);
        if (isset($matches[1])) {
            flash("The chosen " . $matches[1] . " is not available.", "warning");
        } else {
            //TODO come up with a nice error message
            flash("<pre>" . var_export($errorInfo, true) . "</pre>");
        }
    } else {
        //TODO come up with a nice error message
        flash("<pre>" . var_export($errorInfo, true) . "</pre>");
    }
}
function get_url($dest)
{
    global $BASE_PATH;
    if (str_starts_with($dest, "/")) {
        //handle absolute path
        return $dest;
    }
    //handle relative path
    return $BASE_PATH . $dest;
}
function update_participants($comp_id)
{
    $db = getDB();
    $stmt = $db->prepare("UPDATE Competitions set current_participants = (SELECT IFNULL(COUNT(1),0) FROM CompetitionParticipants WHERE comp_id = :cid), 
    current_reward = IFNULL(current_reward,0)+1 WHERE id = :cid");
    try {
        $stmt->execute([":cid" => $comp_id]);
        return true;
    } catch (PDOException $e) {
        error_log("Update competition participant error: " . var_export($e, true));
        echo var_export($e);
        flash("ERROR", "danger");
    }
    return false;
}

function add_to_competition($comp_id)
{
    $user_id = get_user_id();
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO CompetitionParticipants (user_id, comp_id) VALUES (:uid, :cid)");
    try {
        $stmt->execute([":uid" => $user_id, ":cid" => $comp_id]);
        update_participants($comp_id);
        return true;
    } catch (PDOException $e) {
        error_log("Join Competition error: " . var_export($e, true));
        echo "ERROR";
        flash("ERROR", "danger");
    }
    return false;
}

function save_data($table, $data, $ignore = ["submit"])
{
    $table = se($table, null, null, false);
    $db = getDB();
    $query = "INSERT INTO $table "; //be sure you trust $table
    //https://www.php.net/manual/en/functions.anonymous.php Example#3
    $columns = array_filter(array_keys($data), function ($x) use ($ignore) {
        return !in_array($x, $ignore); // $x !== "submit";
    });
    //arrow function uses fn and doesn't have return or { }
    //https://www.php.net/manual/en/functions.arrow.php
    $placeholders = array_map(fn ($x) => ":$x", $columns);
    $query .= "(" . join(",", $columns) . ") VALUES (" . join(",", $placeholders) . ")";

    $params = [];
    foreach ($columns as $col) {
        $params[":$col"] = $data[$col];
    }
    $stmt = $db->prepare($query);
    try {
        $stmt->execute($params);
        //https://www.php.net/manual/en/pdo.lastinsertid.php
        //echo "Successfully added new record with id " . $db->lastInsertId();
        return $db->lastInsertId();
    } catch (PDOException $e) {
        //echo "<pre>" . var_export($e->errorInfo, true) . "</pre>";
        flash("<pre>" . var_export($e->errorInfo, true) . "</pre>");
        return -1;
    }
}

function updatePoints($thePoints, $reason)
{
    $db = getDB();
    $userId = get_user_id();
    $putPoints = $db->prepare("INSERT INTO PointsHistory (user_id,point_change,reason) VALUES (:userId,:thePoints,:reason)");
    $putPoints->execute([":userId" => $userId, ":thePoints" => $thePoints, ":reason" => $reason]);

    $updatePoints = $db->prepare("UPDATE Users SET points = (SELECT (ifnull(sum(point_change),0)+10) from PointsHistory where user_id = :userId) where id = :userId");
    $updatePoints->execute([":userId" => $userId]);
}

function inComp($compsRow)
{
    $uid = get_user_id();
    $db = getDB();
    if ($uid == $compsRow["creator_id"]) {
        return true;
    }
    $stmt = $db->prepare("SELECT * FROM CompetitionParticipants WHERE user_id=:uid");
    $results = []; //all rows from CompetitionParticipants where id==uid
    try {
        $stmt->execute([":uid" => $uid]);
        $r = $stmt->fetchAll();
        if ($r) {
            $results = $r;
        }
    } catch (PDOException $e) {
        flash("There was a problem fetching competitions, please try again later", "danger");
        error_log("List competitions error: " . var_export($e, true));
    }

    foreach ($results as $row) {
        if ($row["comp_id"] == $compsRow["id"]) {
            return true;
        }
    }
    return false;
}

function getUserScore()
{
    $db = getDB();
    $userId = get_user_id();
    $getScore = $db->prepare("SELECT score from Scores where user_id = :userId");
    $getScore->execute([":userId" => $userId]);
    $theFetch = $getScore->fetch();
    $theScore = $theFetch === false ? "0" : $theFetch["score"];
    return $theScore;
}

function get_top10_weekly()
{
    $db = getDB();
    $timestamp = date('Y-m-d H:i:s', time() - (7 * 86400));
    $getScores = $db->prepare("SELECT username,score FROM Scores INNER JOIN Users on Scores.user_id= Users.id WHERE Scores.modified >=:theTime ORDER BY score DESC LIMIT 10");
    $getScores->execute([":theTime" => $timestamp]);
    $theFetch = $getScores->fetchAll();
    // $json = json_encode($theFetch);
    // return $json;
    return $theFetch;
}
function get_top10_monthly()
{
    $db = getDB();
    $timestamp = date('Y-m-d H:i:s', time() - (30.5 * 86400));
    $getScores = $db->prepare("SELECT username,score FROM Scores INNER JOIN Users on Scores.user_id= Users.id WHERE Scores.modified >=:theTime ORDER BY score DESC LIMIT 10");
    $getScores->execute([":theTime" => $timestamp]);
    $theFetch = $getScores->fetchAll();
    // $json = json_encode($theFetch);
    // return $json;
    return $theFetch;
}
function get_top10_lifetime()
{
    $db = getDB();
    $getScores = $db->prepare("SELECT username,score FROM Scores INNER JOIN Users on Scores.user_id= Users.id ORDER BY Scores.score DESC LIMIT 10");
    $getScores->execute();
    $theFetch = $getScores->fetchAll();
    // $json = json_encode($theFetch);
    // return $json;
    return $theFetch;
}

function getCompWinners()
{
    $db = getDB();
    $getComps = $db->prepare("SELECT * FROM Competitions WHERE paid_out=0 AND expires<CURRENT_TIMESTAMP");
    $getComps->execute();
    $theComps = $getComps->fetchAll();

    foreach ($theComps as $aComp) {
        if ($aComp["current_participants"] < $aComp["min_participants"]) continue;

        $getPlayers = $db->prepare("SELECT * FROM CompetitionParticipants WHERE comp_id=:compId");
        $getPlayers->execute([":compId" => $aComp["id"]]);
        $thePlayers = $getPlayers->fetchAll();
        // echo "<br>".var_export(count($thePlayers))."</br>";
        $top3Payrate = [$aComp["first_place"], $aComp["second_place"], $aComp["third_place"]];

        $top3Ids = [0, 0, 0]; //ids of the top 3 players
        $top3 = [0, 0, 0]; //points of the top 3 players. Connected with top3Ids via index
        foreach ($thePlayers as $player) {
            $getScore = $db->prepare("SELECT IFNULL(sum(1),0) FROM ScoreHistory WHERE user_id=:uid AND correct=1 AND created>=:startTime AND created<= :endTime");
            $getScore->execute([":uid" => $player["user_id"], ":startTime" => $aComp["created"], "endTime" => $aComp["expires"]]);
            $theScore = $getScore->fetch()['IFNULL(sum(1),0)'];
            if ($theScore > $top3[2]) {
                if ($theScore > $top3[1]) {
                    if ($theScore > $top3[0]) {
                        $top3[2] = $top3[1];
                        $top3[1] = $top3[0];
                        $top3[0] = $theScore;

                        $top3Ids[2] = $top3Ids[1];
                        $top3Ids[1] = $top3Ids[0];
                        $top3Ids[0] = $player["user_id"];
                    } elseif ($theScore < $top3[0]) {
                        $top3[2] = $top3[1];
                        $top3[1] = $theScore;

                        $top3Ids[2] = $top3Ids[1];
                        $top3Ids[1] = $player["user_id"];
                    }
                } elseif ($theScore < $top3[1]) {
                    $top3[2] = $theScore;
                    $top3Ids[2] = $player["user_id"];
                }
            }
        }
        for ($i = 0; $i < 3; $i++) {
            $points = ceil($aComp["current_reward"] * ($top3Payrate[$i] / 100));
            $place = $i + 1;
            updatePoints($points, "Got $place place in Comp", $top3Ids[$i]);
        }

        $updatePayout = $db->prepare("UPDATE Competitions SET paid_out=1 WHERE id=:cid");
        $updatePayout->execute(["cid" => $aComp["id"]]);
    }
}

function get_visibility()
{
    if (is_logged_in()) { //we need to check for login first because "user" key may not exist
        return se($_SESSION["user"], "visibility", "", false);
    }
    return "";
}

function get_user_comp_history($start = 0, $end = 5)
{
    $db = getDB();
    $uid = get_user_id();
    $getHist = $db->prepare("SELECT comp_id FROM CompetitionParticipants  WHERE user_id= :uid");
    $getHist->execute([":uid" => $uid]);
    $allCompsIdObj = $getHist->fetchAll();

    $theQuery = "SELECT * FROM Competitions WHERE id in (";
    foreach ($allCompsIdObj as $theId) {
        $compId = $theId["comp_id"];
        $theQuery .= $compId . ",";
    }
    $theQuery = substr_replace($theQuery, ")", -1); // replace last char in str with ")"    
    $theQuery .= " LIMIT " . $start . "," . $end;
    $getComps = $db->prepare($theQuery);
    try {
        $getComps->execute();
    } catch (PDOException $e) {
        return $e;
    }
    $compLst = $getComps->fetchAll();
    return [count($allCompsIdObj), $compLst]; //total rows, the rows
}
