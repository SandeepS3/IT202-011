<?php
require(__DIR__ . "/../../../lib/db.php");
require(__DIR__ . "/../../../lib/functions.php");
?>
<?php
try {
    $db = getDB();
    session_start();
    $id = get_user_id();

    $getPoints = $db->prepare("SELECT Points FROM Users WHERE id = :id");
    $getPoints->execute([":id" => $id]);
    $theFetch = $getPoints->fetch();
    $a = $theFetch["Points"];
    echo $a;
} catch (Exception $e) {
    echo var_export($e, true);
    echo "\nDatabase not updated";
}
?>