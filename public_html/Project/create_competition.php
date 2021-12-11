<?php
require_once(__DIR__ . "/../../partials/nav.php");
is_logged_in(true);
$payout_options = [
    '100% to First',
    '80% to First, 20% to Second',
    '70% to First, 20% to Second, 10% to Third',
    '60% to First, 30% to Second, 10% to Third',
    '34% to First, 33% to Second, 33% to Third',
];

function putPayoutOptionInVars($opt, $opts)
{
    $p1 = 0;
    $p2 = 0;
    $p3 = 0;
    if ($opt == $opts[0]) {
        $p1 = 100;
        $p2 = 0;
        $p3 = 0;
    } else if ($opt == $opts[1]) {
        $p1 = 80;
        $p2 = 20;
        $p3 = 0;
    } else if ($opt == $opts[2]) {
        $p1 = 70;
        $p2 = 20;
        $p3 = 10;
    } else if ($opt == $opts[3]) {
        $p1 = 60;
        $p2 = 30;
        $p3 = 10;
    } else if ($opt == $opts[4]) {
        $p1 = 34;
        $p2 = 33;
        $p3 = 33;
    }
    return array($p1, $p2, $p3);
}

$creator_id = get_user_id();
$db = getDB();
$stmt = $db->prepare("SELECT points from Users WHERE id=:uid");
$stmt->execute([":uid" => $creator_id]);
$avaliablePoints = $stmt->fetch()["points"];

if (isset($_POST["comp_name"]) && !empty($_POST["comp_name"])) {
    $comp_name = $_POST['comp_name'];
    $starting_reward = $_POST['starting_reward'];
    $min_score = $_POST['min_score'];
    $min_participants = $_POST['min_participants'];
    $join_fee = $_POST['join_fee'];
    $duration = $_POST['duration'];
    $costToMakeComp = $join_fee + $starting_reward + 1;


    $payout_option = $_POST['payout_option'];
    $places = putPayoutOptionInVars($payout_option, $payout_options);
    $newArr = array(
        'comp_name' => $_POST['comp_name'],
        'starting_reward' => $_POST['starting_reward'],
        'min_score' => $_POST['min_score'],
        'min_participants' => $_POST['min_participants'],
        'join_fee' => $_POST['join_fee'],
        'duration' => $_POST['duration'],
        'first_place' => $places[0],
        'second_place' => $places[1],
        'third_place' => $places[2],
        'creator_id' => $creator_id
    );

    if ($avaliablePoints < $costToMakeComp) {
        flash("Not enough points to make the Competition", "warning");
    } else {
        $comp_id = save_data("Competitions", $newArr);
        if ($comp_id > 0) {
            if (add_to_competition($comp_id)) {
                updatePoints($costToMakeComp * -1, "Made Competition");
                flash("Successfully created competition", "success");
            } else {
                flash("Error in creating the Competition", "warning");
            }
        } else {
            flash("Error in creating the Competition", "warning");
        }
    }
}
?>

<div class="container-fluid">
    <h1>Create Competition</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input id="title" name="comp_name" class="form-control" />
        </div>
        <div class="mb-3">
            <label for="reward" class="form-label">Starting Reward</label>
            <input id="reward" type="number" name="starting_reward" class="form-control" onchange="updateCost()" placeholder=">= 1" min="1" />
        </div>
        <div class="mb-3">
            <label for="ms" class="form-label">Min. Score</label>
            <input id="ms" name="min_score" type="number" class="form-control" placeholder=">= 1" min="1" />
        </div>
        <div class="mb-3">
            <label for="mp" class="form-label">Min. Participants</label>
            <input id="mp" name="min_participants" type="number" class="form-control" placeholder=">= 3" min="3" />
        </div>
        <div class="mb-3">
            <label for="jc" class="form-label">Join Cost</label>
            <input id="jc" name="join_fee" type="number" class="form-control" onchange="updateCost()" placeholder=">= 0" min="0" />
        </div>
        <div class="mb-3">
            <label for="duration" class="form-label">Duration (in Days)</label>
            <input id="duration" name="duration" type="number" class="form-control" placeholder=">= 3" min="3" />
        </div>
        <div class="mb-3">
            <label for="po" class="form-label">Payout Option</label>
            <select id="po" name="payout_option" class="form-control">
                <?php foreach ($payout_options as $po) : ?>
                    <option value="<?php se($po, 'id'); ?>"><?php se($po, 'place'); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <input type="submit" value="Create Competition (Cost: 2)" class="btn btn-primary" />
        </div>
    </form>
    <script>
        function updateCost() {
            let starting = parseInt(document.getElementById("reward").value || 0) + 1;
            let join = parseInt(document.getElementById("jc").value || 0);
            if (join < 0) {
                join = 1;
            }
            let cost = starting + join;
            document.querySelector("[type=submit]").value = `Create Competition (Cost: ${cost})`;
            return cost;
        }
    </script>
</div>
<?php
require(__DIR__ . "/../../partials/flash.php");
?>