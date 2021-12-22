<?php
require_once(__DIR__ . "/../../partials/nav.php");
is_logged_in(true);

if (!isset($_GET["page"])) {
    $page = 1;
} else {
    $page = $_GET["page"];
}

$rowsPerPage = 10;
$row = ($page - 1) * $rowsPerPage;

$userComps = get_user_comp_history($row, $rowsPerPage);
$numOfRows = $userComps[0];
$numOfPages = ceil($numOfRows / $rowsPerPage);

$allComps = $userComps[1];

$results = $allComps;
?>
<div class="container-fluid">
    <h1>Competitions History</h1>
    <table class="table">
        <thead>
            <th>Competition Name</th>
            <th>Duration of Competition</th>
            <th>Expires/Expired</th>
            <th>Total Participants</th>
            <th>Paid Out</th>
            <!-- <thead>Actions</th> -->
        </thead>
        <tbody>
            <?php if (count($results) > 0) : ?>
                <?php foreach ($results as $row) : ?>
                    <tr>
                        <td>
                            <a href="comp_leaderboard.php?comp=<?php se($row, "comp_name"); ?>">
                                <?php se($row, "comp_name"); ?>
                            </a>
                        </td>
                        <td><?php se($row, "duration"); ?></td>
                        <td><?php se($row, "expires"); ?></td>
                        <td><?php se($row, "current_participants"); ?></td>
                        <td><?php echo se($row, "paid_out", "-", false) == "1" ? "True" : "False"; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="100%">No Competition History</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php


for ($i = 1; $i <= $numOfPages; $i++) {
    $j = "<a type='button' class='btn btn-primary' href='comp_history.php?page=" . $i . "'>Page " . $i . "</a> ";
    echo $j;
}
require(__DIR__ . "/../../partials/flash.php");
?>