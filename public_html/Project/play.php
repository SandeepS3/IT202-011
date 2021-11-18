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
</head>

<body>
    <div style="text-align:center;">
        <canvas style="  width: 600px; height: 400px; border: 1px solid black;" id="canvas" width="600" height="600" tabindex="1"></canvas>
        <button onclick="startGame()">Start</button>
    </div>

</body>
<script>
    // Tic-Tac-Toe

    // Get a reference to the canvas DOM element
    var canvas = document.getElementById('canvas');
    // Get the canvas drawing context
    var context = canvas.getContext('2d');

    //Welcome
    context.font = "30px Arial";
    context.fillText("Welcome to Tic-Tac-Toe", 150, 200);
    context.fillText("This is a Single-Player Game", 120, 300)
    context.fillText("Click Start to Play!", 180, 400)


    function startGame() {
        context.clearRect(0, 0, canvas.width, canvas.height);
        //Draw Board

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
</script>

</body>

</html>