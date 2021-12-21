<?php
require_once(__DIR__ . "/../../partials/nav.php");
is_logged_in(true);
if (!isset($_GET["page"])) {
    $page = 1;
} else {
    $page = $_GET["page"];
}
$db = getDB();
//handle join
if (isset($_POST["join"])) {
    $user_id = get_user_id();
    $comp_id = se($_POST, "comp_id", 0, false);
    $cost = se($_POST, "join_fee", 0, false);
    updatePoints($cost * -1, "Joined Competition");
    add_to_competition($comp_id);
    flash("Successfully added to the Competition!!!", "success");
}
//$per_page = 5;
$per_page = 10;
$startRow = ($page - 1) * $per_page;

$stmt = $db->prepare("SELECT * FROM Competitions WHERE expires>CURRENT_TIMESTAMP ORDER BY expires ASC LIMIT " . $startRow . "," . $per_page);
$results = [];
try {
    $stmt->execute();
    $r = $stmt->fetchAll();
    if ($r) {
        $results = $r;
    }
} catch (PDOException $e) {
    flash("There was a problem fetching competitions, please try again later", "danger");
    error_log("List competitions error: " . var_export($e, true));
}

$numOfRows = $db->prepare("SELECT * FROM Competitions WHERE expires>CURRENT_TIMESTAMP ORDER BY expires");
$numOfRows->execute();
$numOfRows = $numOfRows->rowCount();

$numOfPages = ceil($numOfRows / $per_page);
?>
<div class="container-fluid">
    <h1>List Competitions</h1>
    <table class="table">
        <thead>
            <th>Title</th>
            <th>Participants</th>
            <th>Reward</th>
            <th>Min Score</th>
            <th>Expires</th>
            <thead>Actions</th>
            </thead>
        <tbody>
            <?php if (count($results) > 0) : ?>
                <?php foreach ($results as $row) : ?>
                    <tr>
                        <td><?php se($row, "comp_name"); ?></td>
                        <td><?php se($row, "current_participants"); ?>/<?php se($row, "min_participants"); ?></td>
                        <td><?php se($row, "current_reward"); ?><br>Payout: <?php echo (se($row, "paid_out", "-", false)) === "1" ? 'true' : 'false'; ?></td>
                        <td><?php se($row, "min_score"); ?></td>
                        <td><?php se($row, "expires", "-"); ?></td>
                        <td>
                            <!-- <?php //if (se($row, "joined", 0, false)) : 
                                    ?> -->
                            <?php if (inComp($row)) : ?>
                                <button class="btn btn-primary disabled" onclick="event.preventDefault()" disabled>Already Joined</button>
                            <?php elseif (se($row, "min_score", 0, false) > getUserScore()) : ?>
                                <button class="btn btn-primary disabled" onclick="event.preventDefault()" disabled>Score too low to Join</button>
                            <?php else : ?>
                                <form method="POST">
                                    <input type="hidden" name="comp_id" value="<?php se($row, 'id'); ?>" />
                                    <input type="hidden" name="cost" value="<?php se($row, 'join_fee', 0); ?>" />
                                    <input type="submit" name="join" class="btn btn-primary" value="Join (Cost: <?php se($row, "join_fee", 0) ?>)" />
                                </form>
                            <?php endif; ?>
                            <!-- <a class="btn btn-secondary" href="view_competition.php?id=<?php //se($row, 'id'); 
                                                                                            ?>">View</a> -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="100%">No active competitions</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
for ($i = 1; $i <= $numOfPages; $i++) {
    $j = "<a type='button' class='btn btn-primary' href='list_competitions.php?page=" . $i . "'>Page " . $i . "</a> ";
    echo $j;
}
require(__DIR__ . "/../../partials/flash.php");
?>