<?php
require(__DIR__ . "/../../../lib/db.php");
require(__DIR__ . "/../../../lib/functions.php");
?>
<?php
try {
    $db = getDB();
    session_start();
    $userId = get_user_id();

    $getScores = $db->prepare("SELECT * FROM ScoresHistory WHERE user_id = :userId ORDER BY created DESC LIMIT 10");
    $getScores->execute([":userId" => $userId]);
    $theFetch = $getScores->fetchAll();
    $json = json_encode($theFetch);
    echo $json;
    // echo get_top10_weekly();
} catch (Exception $e) {
    echo var_export($e, true);
    echo "\nDatabase not updated";
}
?>