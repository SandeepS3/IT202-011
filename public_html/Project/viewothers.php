<?php
require_once(__DIR__ . "/../../partials/nav.php");
?>

<?php
if (!isset($_GET["player"])) {
    echo "no player";
    return;
} else {
    $player = $_GET["player"];
    $username = $player;
}

$db = getDB();
$info = $db->prepare("SELECT * FROM Users WHERE username=:unm");
$info->execute([":unm" => $player]);
$info = $info->fetch();

if ($info["visibility"] == "private") {
    echo "This Profile is Private.";
    return;
}
// echo "<pre>" . var_export($info, true) . "</pre>";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>User Profile</title>
</head>

<body>
    <div class="container-fluid">
        <h1>Profile</h1>
        <form>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input disabled class="form-control" type="text" id="username" value="<?php echo $username; ?>" />
            </div>
            <div class="mb-3">
                <label class="form-label">ID</label>
                <input disabled class="form-control" type="text" id="username" value="<?php echo $info["id"]; ?>" />
            </div>
            <div class="mb-3">
                <label class="form-label">Profile Created</label>
                <input disabled class="form-control" type="text" id="username" value="<?php echo $info["created"]; ?>" />
            </div>
            <div class="mb-3">
                <label class="form-label">Last Modified</label>
                <input disabled class="form-control" type="text" id="username" value="<?php echo $info["modified"]; ?>" />
            </div>
            <div class="mb-3">
                <label class="form-label">Points</label>
                <input disabled class="form-control" type="text" id="username" value="<?php echo $info["Points"]; ?>" />
            </div>
        </form>

    </div>
</body>

</html>