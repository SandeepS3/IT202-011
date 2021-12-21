<?php
require_once(__DIR__ . "/../../partials/nav.php");
is_logged_in(true);
?>
<?php
$db = getDB();
$getScores = $db->prepare("SELECT * FROM ScoresHistory WHERE user_id = :userId");
$getScores->execute([":userId" => get_user_id()]);
$totalRows = $getScores->rowCount();
$resultsPerPage = 10;
$numOfPages = ceil($totalRows / $resultsPerPage);


if (!isset($_GET["page"])) {
    die(header("Location: profile.php?page=1"));
} else if ($_GET["page"] < 1) {
    die(header("Location: profile.php?page=1"));
} else if ($_GET["page"] > $numOfPages) {
    die(header("Location: profile.php?page=" . $numOfPages));
} else {
    $page = $_GET["page"];
}
$row = ($page - 1) * $resultsPerPage;
if (isset($_POST["save"])) {
    $email = se($_POST, "email", null, false);
    $username = se($_POST, "username", null, false);
    $visibility = se($_POST, "visibility", null, false) == "" ? "public" : "private";
    $hasError = false;
    $email = sanitize_email($email);
    if (!is_valid_email($email)) {
        flash("Invalid email address", "danger");
        $hasError = true;
    }
    if (!preg_match('/^[a-z0-9_-]{3,16}$/i', $username)) {
        flash("Username must only be alphanumeric and can only contain - or _", "danger");
        $hasError = true;
    }
    if (!$hasError) {
        $params = [":email" => $email, ":username" => $username, ":visibility" => $visibility, ":id" => get_user_id()];
        $db = getDB();
        $stmt = $db->prepare("UPDATE Users set email = :email, username = :username, visibility = :visibility where id = :id");
        try {
            $stmt->execute($params);
            $_SESSION["user"]["visibility"] = $visibility;
            flash("Details Updated!", "success");
        } catch (Exception $e) {
            users_check_duplicate($e->errorInfo);
        }
    }
    //select fresh data from table
    $stmt = $db->prepare("SELECT id, email, IFNULL(username, email) as `username` from Users where id = :id LIMIT 1");
    try {
        $stmt->execute([":id" => get_user_id()]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            //$_SESSION["user"] = $user;
            $_SESSION["user"]["email"] = $user["email"];
            $_SESSION["user"]["username"] = $user["username"];
        } else {
            flash("User doesn't exist", "danger");
        }
    } catch (Exception $e) {
        flash("An unexpected error occurred, please try again", "danger");
        //echo "<pre>" . var_export($e->errorInfo, true) . "</pre>";
    }


    //check/update password
    $current_password = se($_POST, "currentPassword", null, false);
    $new_password = se($_POST, "newPassword", null, false);
    $confirm_password = se($_POST, "confirmPassword", null, false);
    if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
        if ($new_password === $confirm_password) {
            //TODO validate current
            $stmt = $db->prepare("SELECT password from Users where id = :id");
            try {
                $stmt->execute([":id" => get_user_id()]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (isset($result["password"])) {
                    if (password_verify($current_password, $result["password"])) {
                        $query = "UPDATE Users set password = :password where id = :id";
                        $stmt = $db->prepare($query);
                        $stmt->execute([
                            ":id" => get_user_id(),
                            ":password" => password_hash($new_password, PASSWORD_BCRYPT)
                        ]);

                        flash("Password reset", "success");
                    } else {
                        flash("Current password is invalid", "warning");
                    }
                }
            } catch (Exception $e) {
                echo "<pre>" . var_export($e->errorInfo, true) . "</pre>";
            }
        } else {
            flash("New passwords don't match", "warning");
        }
    }
}
?>

<?php
$visibility = get_visibility();
$email = get_user_email();
$username = get_username();
?>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<style>
    .pagination {
        display: inline-block;
    }

    .pagination a {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
        transition: background-color .3s;
        border: 1px solid #ddd;
    }

    .pagination a.active {
        background-color: #4CAF50;
        color: white;
        border: 1px solid #4CAF50;
    }

    .pagination a:hover:not(.active) {
        background-color: #ddd;
    }
</style>
<div class="container-fluid">
    <h1>Profile</h1>
    <form method="POST" onsubmit="return validate(this);">
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input class="form-control" type="email" name="email" id="email" value="<?php se($email); ?>" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <input class="form-control" type="text" name="username" id="username" value="<?php se($username); ?>" />
        </div>
        <!-- DO NOT PRELOAD PASSWORD -->
        <div class="mb-3">Password Reset</div>
        <div class="mb-3">
            <label class="form-label" for="cp">Current Password</label>
            <input class="form-control" type="password" name="currentPassword" id="cp" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="np">New Password</label>
            <input class="form-control" type="password" name="newPassword" id="np" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="conp">Confirm Password</label>
            <input class="form-control" type="password" name="confirmPassword" id="conp" />
        </div>

        <div class="mb-3" style="flex-direction: column;">
            <label style="flex:1" class="form-label" id="switchLabel" for="switchVisibility">Profile Visibility : Private</label>
            <label style="flex:1" class="switch">
                <input type="checkbox" onclick="buttonfuc()" id="switchVisibility" name="visibility">
                <span class="slider round"></span>
            </label>
        </div>

        <input type="submit" class="mt-3 btn btn-primary" value="Update Profile" name="save" />

    </form>
</div>
<p>Points: </p>
<p class="theScoreOrPoints" id="points"></p>
<div class="container-fluid">
    <!-- <button id="showScoresBtn" onclick='getScores()' class="mt-3 btn btn-primary">Show last 10 Scores</button> -->
    <ol id="last10Scores">
    </ol>
</div>
<script>
    function validate(form) {
        let pw = form.newPassword.value;
        let con = form.confirmPassword.value;
        let isValid = true;
        //TODO add other client side validation....
        if (pw !== con) {
            flash("Password and Confirm password must match", "warning");
            isValid = false;
        }
        return isValid;
    }

    function getScores(start = 0, numOfres = 5) {
        $.ajax({
            url: "api/get_10scores.php",
            type: "post",
            data: {
                "start": start,
                "resultnum": numOfres,
            },
            success: (resp, status, xhr) => {
                theScores = JSON.parse(resp);
                showScores(theScores)
            },
            error: (xhr, status, error) => {
                console.log(xhr, status, error);
            }
        });
    }

    function showScores(scrs) {
        const theUl = document.getElementById("last10Scores")
        theUl.innerHTML = ""
        if (scrs.length === 0) {
            theUl.innerHTML = "<div>No recorded attempts.</div>"
        }
        scrs.forEach(score => {
            const li = document.createElement("li")
            const div = document.createElement("div")

            msg = score.win === "0" ? "You did not win :(" : "You won!!!"
            const date = new Date(score.created)
            const options = {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                time: 'numeric'
            };
            div.innerHTML = `<h5>${msg}</h5><p>Attempted - ${date.toLocaleDateString("en-US",options)} at ${date.toLocaleTimeString('en-US')}</p>`
            li.appendChild(div);
            theUl.appendChild(li);
        })
    }


    $("#points").load("api/get_points.php");

    function buttonfuc() {
        const thebutton = document.getElementById("switchVisibility")
        const label = document.getElementById("switchLabel")
        if (thebutton.checked) {
            label.innerText = "Profile Visibility : Private"
            thebutton.value = "private";
        } else {
            label.innerText = "Profile Visibility : Public"
            thebutton.value = "public";
        }
    }

    function firstbuttonrun() {
        const thebutton = document.getElementById("switchVisibility")
        checkVal = "<?php echo se($visibility, null, "", false) == "public" ? "false" : "true"; ?>" == "false" ? false : true
        thebutton.checked = checkVal
        buttonfuc()
    }

    getScores(<?php echo $row ?>, <?php echo $resultsPerPage ?>)
    firstbuttonrun()
</script>

<?php
// echo var_export($row);
// echo var_export($resultsPerPage);
if ($page - 1 == 0) $page = 2;
if ($page + 1 == $numOfPages + 1) $page = $numOfPages - 1;
$j = '<div class="pagination">
        <a href="profile.php?page=' . ($page - 1) . '">❮</a>
        <a href="profile.php?page=' . ($page + 1) . '">❯</a>
    </div>';
echo $j;
require_once(__DIR__ . "/../../partials/flash.php");
?>