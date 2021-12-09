<?php
require(__DIR__ . "/../../../lib/db.php");
require(__DIR__ . "/../../../lib/functions.php");
?>
<?php
$db = getDB();
session_start();
$userId = get_user_id();
try {

    $gmWon = $_POST['GameWon'];
    $gmWon = $gmWon === "true" ? 1 : 0;
    if ($gmWon === 1) {
        //+1 to users score
        $getScore = $db->prepare("SELECT score from Scores where user_id = :userId");
        $getScore->execute([":userId" => $userId]);
        $theFetch = $getScore->fetch();

        if ($theFetch === false) {
            $putScore = $db->prepare("INSERT INTO Scores (score,user_id) VALUES (:newScore,:userId)");
        } else {
            $putScore = $db->prepare("UPDATE Scores SET score=:newScore where user_id = :userId");
        }
        $theScore = $theFetch === false ? "0" : $theFetch["score"];
        $putScore->execute([":newScore" => $theScore + 1, ":userId" => $userId]);
        echo "Winner\n";
    }
    $addAttempt = $db->prepare("INSERT INTO ScoresHistory (user_id,win) VALUES (:userId,:winner)");
    $addAttempt->execute([":userId" => $userId, ":winner" => $gmWon]);
    echo "Databases Updated!!\n";
} catch (Exception $e) {
    echo var_export($e, true);
    echo "\nDatabase not updated";
}
?>