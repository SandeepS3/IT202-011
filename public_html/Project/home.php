<?php
require(__DIR__ . "/../../partials/nav.php");
?>
<h1>Home</h1>
<?php

// if (is_logged_in(true)) {
//     echo "Welcome home, " . get_username();
//     //comment this out if you don't want to see the session variables
//     echo "<pre>" . var_export($_SESSION, true) . "</pre>";
// }
if (!is_logged_in(true)) {
    die(header("Location: login.php"));
}
$weekly = get_top10_weekly();
$monthly = get_top10_monthly();
$lifetime = get_top10_lifetime();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
</head>

<body>
    <div class="container-fluid">
        <h3>This Weeks Top Players</h3>
        <table class="table">
            <thead>
                <th>Name</th>
                <th>Score</th>
            </thead>
            <tbody>
                <?php if (count($weekly) > 0) : ?>
                    <?php foreach ($weekly as $row) : ?>
                        <tr>
                            <td><?php se($row, "username"); ?></td>
                            <td><?php se($row, "score"); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="100%">No Competition History</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>This Months Top Players</h3>
        <table class="table">
            <thead>
                <th>Name</th>
                <th>Score</th>
            </thead>
            <tbody>
                <?php if (count($monthly) > 0) : ?>
                    <?php foreach ($monthly as $row) : ?>
                        <tr>
                            <td><?php se($row, "username"); ?></td>
                            <td><?php se($row, "score"); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="100%">No Competition History</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>Top Players Lifetime</h3>
        <table class="table">
            <thead>
                <th>Name</th>
                <th>Score</th>
            </thead>
            <tbody>
                <?php if (count($lifetime) > 0) : ?>
                    <?php foreach ($lifetime as $row) : ?>
                        <tr>
                            <td><?php se($row, "username"); ?></td>
                            <td><?php se($row, "score"); ?></td>
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
    <form action="play.php">
        <button type="submit" class="btn btn-primary">Play the Game</button>
    </form>
</body>

</html>
<?php
require(__DIR__ . "/../../partials/flash.php");
?>