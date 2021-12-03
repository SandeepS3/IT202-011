<?php
require(__DIR__ . "/../../partials/nav.php");
is_logged_in(true);
?>
<h1>Tic-Tac-Toe</h1>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

<body>
    <div style="text-align:center;">
        <canvas style="border: 1px solid black;" id="canvas" width="600" height="600" tabindex="1"></canvas>
        <button onclick="menu()">Start</button>
    </div>

</body>
<script>
    // Tic-Tac-Toe
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');

    let spaces = [null, null, null, null, null, null, null, null, null]
    //Welcome

    function endGame() {
        erase();
        context.fillStyle = '#000000';
        context.font = '24px Arial';
        context.textAlign = 'center';
        context.fillText('Game Over. Final Score: ' + 1, canvas.width / 2, canvas.height / 2);
    }

    function drawBoard() {

        //Left Vertical
        context.beginPath();
        context.moveTo(200, 0);
        context.lineTo(200, 600);
        context.stroke();

        //Right Vertical
        context.beginPath();
        context.moveTo(400, 0);
        context.lineTo(400, 600);
        context.stroke();

        //Top Horizontal
        context.beginPath();
        context.moveTo(0, 200);
        context.lineTo(600, 200);
        context.stroke();

        //Bottom Hoizontal
        context.beginPath();
        context.moveTo(0, 400);
        context.lineTo(600, 400);
        context.stroke();
    }

    function erase() {
        context.fillStyle = '#FFFFFF';
        context.fillRect(0, 0, 600, 600);
    }

    function getMousePos(canvas, evt) {
        let rect = canvas.getBoundingClientRect();
        return {
            x: evt.clientX - rect.left,
            y: evt.clientY - rect.top
        };
    }

    function getBox(x, y) {
        //Box1
        if (x >= 0 && x <= 200) {
            if (y >= 0 && y <= 200) {
                return 0
            }
        }

        //Box2
        if (x >= 200 && x <= 400) {
            if (y >= 0 && y <= 200) {
                return 1
            }
        }

        //Box3
        if (x >= 400 && x <= 600) {
            if (y >= 0 && y <= 200) {
                return 2
            }
        }


        //Box4
        if (x >= 0 && x <= 200) {
            if (y >= 200 && y <= 400) {
                return 3
            }
        }

        //Box5
        if (x >= 200 && x <= 400) {
            if (y >= 200 && y <= 400) {
                return 4
            }
        }

        //Box6
        if (x >= 400 && x <= 600) {
            if (y >= 200 && y <= 400) {
                return 5;
            }
        }

        //Box7
        if (x >= 0 && x <= 200) {
            if (y >= 400 && y <= 600) {
                return 6
            }
        }

        //Box8
        if (x >= 200 && x <= 400) {
            if (y >= 400 && y <= 600) {
                return 7
            }
        }

        //Box9
        if (x >= 400 && x <= 600) {
            if (y >= 400 && y <= 600) {
                return 8
            }
        }
    }

    function drawX(ind) {
        if (spaces[ind] !== 'o') {
            spaces[ind] = 'x'
        }
        if (ind == 0 || spaces[0] === 'x') {
            if (spaces[0] !== 'o') {
                context.beginPath();
                context.moveTo(0, 0);
                context.lineTo(200, 200);
                context.stroke();
                context.beginPath();
                context.moveTo(200, 0);
                context.lineTo(0, 200);
                context.stroke();
            }
        }
        if (ind == 1 || spaces[1] === 'x') {
            if (spaces[1] !== 'o') {
                context.beginPath();
                context.moveTo(200, 0);
                context.lineTo(400, 200);
                context.stroke();
                context.beginPath();
                context.moveTo(400, 0);
                context.lineTo(200, 200);
                context.stroke();
            }
        }
        if (ind == 2 || spaces[2] === 'x') {
            if (spaces[2] !== 'o') {
                context.beginPath();
                context.moveTo(400, 0);
                context.lineTo(600, 200);
                context.stroke();
                context.beginPath();
                context.moveTo(600, 0);
                context.lineTo(400, 200);
                context.stroke();
            }
        }
        if (ind == 3 || spaces[3] === 'x') {
            if (spaces[3] !== 'o') {
                context.beginPath();
                context.moveTo(0, 200);
                context.lineTo(200, 400);
                context.stroke();
                context.beginPath();
                context.moveTo(200, 200);
                context.lineTo(0, 400);
                context.stroke();
            }
        }
        if (ind == 4 || spaces[4] === 'x') {
            if (spaces[4] !== 'o') {
                context.beginPath();
                context.moveTo(200, 200);
                context.lineTo(400, 400);
                context.stroke();
                context.beginPath();
                context.moveTo(400, 200);
                context.lineTo(200, 400);
                context.stroke();
            }
        }
        if (ind == 5 || spaces[5] === 'x') {
            if (spaces[5] !== 'o') {
                context.beginPath();
                context.moveTo(400, 200);
                context.lineTo(600, 400);
                context.stroke();
                context.beginPath();
                context.moveTo(600, 200);
                context.lineTo(400, 400);
                context.stroke();
            }
        }
        if (ind == 6 || spaces[6] === 'x') {
            if (spaces[6] !== 'o') {
                context.beginPath();
                context.moveTo(0, 400);
                context.lineTo(200, 600);
                context.stroke();
                context.beginPath();
                context.moveTo(200, 400);
                context.lineTo(0, 600);
                context.stroke();
            }
        }
        if (ind == 7 || spaces[7] === 'x') {
            if (spaces[7] !== 'o') {
                context.beginPath();
                context.moveTo(200, 400);
                context.lineTo(400, 600);
                context.stroke();
                context.beginPath();
                context.moveTo(400, 400);
                context.lineTo(200, 600);
                context.stroke();
            }
        }
        if (ind == 8 || spaces[8] === 'x') {
            if (spaces[8] !== 'o') {
                context.beginPath();
                context.moveTo(400, 400);
                context.lineTo(600, 600);
                context.stroke();
                context.beginPath();
                context.moveTo(600, 400);
                context.lineTo(400, 600);
                context.stroke();
            }
        }
    }

    function drawO(ind) {
        let randO = Math.floor(Math.random() * (9));
        while (spaces[randO] !== null) {
            randO = Math.floor(Math.random() * (9));
            console.log("new O needed")
        }

        spaces[randO] = 'o'


        if (spaces[0] === 'o' && spaces[0] !== 'x') {
            context.beginPath();
            context.arc(100, 100, 40, 0, 2 * Math.PI);
            context.stroke();
        }
        if (spaces[1] === 'o' && spaces[1] !== 'x') {
            context.beginPath();
            context.arc(300, 100, 40, 0, 2 * Math.PI);
            context.stroke();
        }
        if (spaces[2] === 'o' && spaces[2] !== 'x') {
            context.beginPath();
            context.arc(500, 100, 40, 0, 2 * Math.PI);
            context.stroke();
        }
        if (spaces[3] === 'o' && spaces[3] !== 'x') {
            context.beginPath();
            context.arc(100, 300, 40, 0, 2 * Math.PI);
            context.stroke();
        }
        if (spaces[4] === 'o' && spaces[4] !== 'x') {
            context.beginPath();
            context.arc(300, 300, 40, 0, 2 * Math.PI);
            context.stroke();
        }
        if (spaces[5] === 'o' && spaces[5] !== 'x') {
            context.beginPath();
            context.arc(500, 300, 40, 0, 2 * Math.PI);
            context.stroke();
        }
        if (spaces[6] === 'o' && spaces[6] !== 'x') {
            context.beginPath();
            context.arc(100, 500, 40, 0, 2 * Math.PI);
            context.stroke();
        }
        if (spaces[7] === 'o' && spaces[7] !== 'x') {
            context.beginPath();
            context.arc(300, 500, 40, 0, 2 * Math.PI);
            context.stroke();
        }
        if (spaces[8] === 'o' && spaces[8] !== 'x') {
            context.beginPath();
            context.arc(500, 500, 40, 0, 2 * Math.PI);
            context.stroke();
        }
    }

    function menu() {
        spaces = [null, null, null, null, null, null, null, null, null]
        erase();
        context.fillStyle = '#000000';
        context.font = "30px Arial";
        context.fillText("Welcome to Tic-Tac-Toe", 150, 200);
        context.fillText("This is a Single-Player Game", 120, 300)
        context.fillText("Click Start to Play!", 180, 400)
        canvas.addEventListener('click', main)
    }


    function X_WON() {
        function xWon() {
            if (spaces[0] === 'x' && spaces[1] === 'x' && spaces[2] === 'x') {
                return true
            }
            if (spaces[3] === 'x' && spaces[4] === 'x' && spaces[5] === 'x') {
                return true
            }
            if (spaces[6] === 'x' && spaces[7] === 'x' && spaces[8] === 'x') {
                return true
            }
            if (spaces[0] === 'x' && spaces[3] === 'x' && spaces[6] === 'x') {
                return true
            }
            if (spaces[1] === 'x' && spaces[4] === 'x' && spaces[7] === 'x') {
                return true
            }
            if (spaces[2] === 'x' && spaces[5] === 'x' && spaces[8] === 'x') {
                return true
            }
            if (spaces[0] === 'x' && spaces[4] === 'x' && spaces[8] === 'x') {
                return true
            }
            if (spaces[2] === 'x' && spaces[4] === 'x' && spaces[6] === 'x') {
                return true
            }
            return false
        }

        function oWon() {
            if (spaces[0] === 'o' && spaces[1] === 'o' && spaces[2] === 'o') {
                return true
            }
            if (spaces[3] === 'o' && spaces[4] === 'o' && spaces[5] === 'o') {
                return true
            }
            if (spaces[6] === 'o' && spaces[7] === 'o' && spaces[8] === 'o') {
                return true
            }
            if (spaces[0] === 'o' && spaces[3] === 'o' && spaces[6] === 'o') {
                return true
            }
            if (spaces[1] === 'o' && spaces[4] === 'o' && spaces[7] === 'o') {
                return true
            }
            if (spaces[2] === 'o' && spaces[5] === 'o' && spaces[8] === 'o') {
                return true
            }
            if (spaces[0] === 'o' && spaces[4] === 'o' && spaces[8] === 'o') {
                return true
            }
            if (spaces[2] === 'o' && spaces[4] === 'o' && spaces[6] === 'o') {
                return true
            }
            return false
        }


        if (xWon()) {
            flash("X WON!!!")
            return true;
        }
        if (oWon()) {
            flash("O WON!!!")
            return false

        }

        return null;
    }

    function sendData(gameover) {
        $.ajax({
            url: "api/save_score.php",
            type: "post",
            data: {
                "GameWon": gameover
            },
            success: (resp, status, xhr) => {
                console.log(resp)
            },
            error: (xhr, status, error) => {
                console.log(xhr, status, error);
            }
        });
    }

    function main(evt) {
        console.log(spaces)
        erase()
        drawBoard()
        let mousePos = getMousePos(canvas, evt);
        let boxIndexNum = getBox(mousePos.x, mousePos.y)
        if (spaces[boxIndexNum] !== null) {
            console.log(spaces[boxIndexNum])
            flash("Space taked", "warning")
        }
        drawX(boxIndexNum)
        if (X_WON() !== null) {
            console.log("Game Ended")
            canvas.removeEventListener("click", main)
            sendData(X_WON())
            return;
        }
        console.log(spaces.includes(null), spaces)
        if (!spaces.includes(null)) {
            flash(" Draw!!!")
            console.log("Draw")
            canvas.removeEventListener("click", main)
            sendData(X_WON())
            // endGame();
            console.log(spaces)
        }
        drawO(boxIndexNum)
        if (X_WON() !== null) {
            console.log("Game Ended")
            canvas.removeEventListener("click", main)
            sendData(X_WON())
            return;
        }
        if (!spaces.includes(null)) {
            flash(" Draw!!!")
            console.log("Draw")
            canvas.removeEventListener("click", main)
            sendData(X_WON())
            // endGame();
            console.log(spaces)
            return;
        }

        console.log(spaces)
    }

    menu()
</script>
<?php
require(__DIR__ . "/../../partials/flash.php");
?>