<?php
require(__DIR__ . "/../../../lib/db.php");
require(__DIR__ . "/../../../lib/functions.php");
?>
<?php
try {
    $db = getDB();
    session_start();
    $userId = get_user_id();


    $reason = $_POST['reason'];
    $thePoints = $_POST['points'];

    echo $reason;
    $putPoints = $db->prepare("INSERT INTO PointsHistory (user_id,point_change,reason) VALUES (:userId,:thePoints,:reason)");
    $putPoints->execute([":userId" => $userId, ":thePoints" => $thePoints, ":reason" => $reason]);

    $updatePoints = $db->prepare("UPDATE Users SET points = (SELECT (ifnull(sum(point_change),0)) from PointsHistory where user_id = :userId) where id = :userId");
    $updatePoints->execute([":userId" => $userId]);
    echo "Points Updated!!";
} catch (Exception $e) {
    echo var_export($e, true);
    echo "\nDatabase not updated";
}
