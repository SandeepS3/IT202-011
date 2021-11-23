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

    //Variables
    var box1 = false;
    var box2 = false;
    var box3 = false;
    var box4 = false;
    var box5 = false;
    var box6 = false;
    var box7 = false;
    var box8 = false;
    var box9 = false;
    var score = 0


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

        //Temporary
        var randNum = Math.floor(Math.random() * (10)) + 1;
        console.log(randNum)
        if (randNum % 2 == 0) {
            score++
            console.log('Score is ' + score)
        }
    }

    function getMousePos(canvas, evt) {
        var rect = canvas.getBoundingClientRect();
        return {
            x: evt.clientX - rect.left,
            y: evt.clientY - rect.top
        };
    }

    canvas.addEventListener('click', function(evt) {
        var mousePos = getMousePos(canvas, evt);
        console.log('Mouse position: ' + mousePos.x + ',' + mousePos.y)


        //Box1
        if (mousePos.x >= 0 && mousePos.x <= 200) {
            if (mousePos.y >= 0 && mousePos.y <= 133) {
                box1 = true;
                console.log('This is box 1')
            } else {
                box1 = false;
            }
        }

        //Box2
        if (mousePos.x >= 200 && mousePos.x <= 400) {
            if (mousePos.y >= 0 && mousePos.y <= 133) {
                box2 = true;
                console.log('This is box 2')
            } else {
                box2 = false;
            }
        }

        //Box3
        if (mousePos.x >= 400 && mousePos.x <= 600) {
            if (mousePos.y >= 0 && mousePos.y <= 133) {
                box3 = true;
                console.log('This is box 3')
            } else {
                box3 = false;
            }
        }


        //Box4
        if (mousePos.x >= 0 && mousePos.x <= 200) {
            if (mousePos.y >= 134 && mousePos.y <= 266) {
                box4 = true;
                console.log('This is box 4')
            } else {
                box4 = false;
            }
        }

        //Box5
        if (mousePos.x >= 200 && mousePos.x <= 400) {
            if (mousePos.y >= 134 && mousePos.y <= 266) {
                box5 = true;
                console.log('This is box 5')
            } else {
                box5 = false;
            }
        }

        //Box6
        if (mousePos.x >= 400 && mousePos.x <= 600) {
            if (mousePos.y >= 134 && mousePos.y <= 266) {
                box6 = true;
                console.log('This is box 6')
            } else {
                box6 = false;
            }
        }

        //Box7
        if (mousePos.x >= 0 && mousePos.x <= 200) {
            if (mousePos.y >= 266 && mousePos.y <= 400) {
                box7 = true;
                console.log('This is box 7')
            } else {
                box7 = false;
            }
        }

        //Box8
        if (mousePos.x >= 200 && mousePos.x <= 400) {
            if (mousePos.y >= 266 && mousePos.y <= 400) {
                box8 = true;
                console.log('This is box 8')
            } else {
                box8 = false;
            }
        }

        //Box9
        if (mousePos.x >= 400 && mousePos.x <= 600) {
            if (mousePos.y >= 266 && mousePos.y <= 400) {
                box9 = true;
                console.log('This is box 9')
            } else {
                box9 = false;
            }
        }
    }, false);
</script>

</body>

</html>