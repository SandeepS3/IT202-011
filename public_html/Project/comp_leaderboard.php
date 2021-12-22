<?php
require_once(__DIR__ . "/../../partials/nav.php");
?>

<?php
if (!isset($_GET["comp"])) {
    echo "no competion";
    return;
} else {
    $comp = $_GET["comp"];
}

$db = getDB();
$info = $db->prepare(
    "SELECT username,(Select IFNULL(SUM(win),0) from ScoresHistory 
    WHERE ScoresHistory.user_id=Users.id AND ScoresHistory.created >= Competitions.created AND ScoresHistory.created<= Competitions.expires) AS sum  
    FROM Competitions INNER JOIN CompetitionParticipants ON Competitions.id=CompetitionParticipants.comp_id 
    INNER JOIN Users ON CompetitionParticipants.user_id=Users.id WHERE Competitions.comp_name=:cname ORDER BY sum desc LIMIT 10"
);
$info->execute([":cname" => $comp]);
$results = $info->fetchAll();

?>
<div class="container-fluid">
    <h1>Score Board for Competition: '<?php echo $comp ?>'</h1>
    <table class="table">
        <thead>
            <th>Username</th>
            <th>Score</th>
        </thead>
        <tbody>
            <?php if (count($results) > 0) : ?>
                <?php foreach ($results as $row) : ?>
                    <tr>
                        <td>
                            <a href="viewothers.php?player=<?php se($row, "username"); ?>">
                                <?php se($row, "username"); ?>
                            </a>
                        </td>
                        <td><?php se($row, "sum"); ?></td>
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