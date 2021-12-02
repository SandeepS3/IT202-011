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
    let canvas = document.getElementById('canvas');
    // Get the canvas drawing context
    let context = canvas.getContext('2d');

    //Welcome
    context.font = "30px Arial";
    context.fillText("Welcome to Tic-Tac-Toe", 150, 200);
    context.fillText("This is a Single-Player Game", 120, 300)
    context.fillText("Click Start to Play!", 180, 400)



    function startGame() {
        //Variables
        let box1 = false;
        let box2 = false;
        let box3 = false;
        let box4 = false;
        let box5 = false;
        let box6 = false;
        let box7 = false;
        let box8 = false;
        let box9 = false;
        const spaces = [null, null, null, null, null, null, null, null, null]
        let score = 0
        let winner = false;
        let counter = 0
        let index = 0

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
        let randNum = Math.floor(Math.random() * (10)) + 1;
        console.log(randNum)
        if (randNum % 2 == 0) {
            score++
            winner = true
            console.log('Score is ' + score)
        } else {
            winner = false
        }

        function getMousePos(canvas, evt) {
            let rect = canvas.getBoundingClientRect();
            return {
                x: evt.clientX - rect.left,
                y: evt.clientY - rect.top
            };
        }

        canvas.addEventListener('click', function(evt) {
            let mousePos = getMousePos(canvas, evt);
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


            while (true) {
                console.log(counter, "counter")
                if (xWon() == true || oWon() == true) {
                    break
                }
                if (counter % 2 == 0) {
                    if (box1 == true) {
                        spaces[0] = 'x'
                        console.log(spaces)
                        counter = counter + 1
                        console.log(counter)


                        context.beginPath();
                        context.moveTo(0, 0);
                        context.lineTo(200, 200);
                        context.stroke();
                        context.beginPath();
                        context.moveTo(200, 0);
                        context.lineTo(0, 200);
                        context.stroke();
                    }
                    if (box2 == true) {
                        spaces[1] = 'x'
                        console.log(spaces)
                        counter = counter + 1
                        console.log(counter)


                        context.beginPath();
                        context.moveTo(200, 0);
                        context.lineTo(400, 200);
                        context.stroke();
                        context.beginPath();
                        context.moveTo(400, 0);
                        context.lineTo(200, 200);
                        context.stroke();
                    }
                    if (box3 == true) {
                        spaces[2] = 'x'
                        console.log(spaces)
                        counter = counter + 1
                        console.log(counter)


                        context.beginPath();
                        context.moveTo(400, 0);
                        context.lineTo(600, 200);
                        context.stroke();
                        context.beginPath();
                        context.moveTo(600, 0);
                        context.lineTo(400, 200);
                        context.stroke();
                    }
                    if (box4 == true) {
                        spaces[3] = 'x'
                        console.log(spaces)
                        counter = counter + 1
                        console.log(counter)


                        context.beginPath();
                        context.moveTo(0, 200);
                        context.lineTo(200, 400);
                        context.stroke();
                        context.beginPath();
                        context.moveTo(200, 200);
                        context.lineTo(0, 400);
                        context.stroke();
                    }
                    if (box5 == true) {
                        spaces[4] = 'x'
                        console.log(spaces)
                        counter = counter + 1
                        console.log(counter)


                        context.beginPath();
                        context.moveTo(200, 200);
                        context.lineTo(400, 400);
                        context.stroke();
                        context.beginPath();
                        context.moveTo(400, 200);
                        context.lineTo(200, 400);
                        context.stroke();
                    }
                    if (box6 == true) {
                        spaces[5] = 'x'
                        console.log(spaces)
                        counter = counter + 1
                        console.log(counter)


                        context.beginPath();
                        context.moveTo(400, 200);
                        context.lineTo(600, 400);
                        context.stroke();
                        context.beginPath();
                        context.moveTo(600, 200);
                        context.lineTo(400, 400);
                        context.stroke();
                    }
                    if (box7 == true) {
                        spaces[6] = 'x'
                        console.log(spaces)
                        counter = counter + 1
                        console.log(counter)


                        context.beginPath();
                        context.moveTo(0, 400);
                        context.lineTo(200, 600);
                        context.stroke();
                        context.beginPath();
                        context.moveTo(200, 400);
                        context.lineTo(0, 600);
                        context.stroke();
                    }
                    if (box8 == true) {
                        spaces[7] = 'x'
                        console.log(spaces)
                        counter = counter + 1
                        console.log(counter)


                        context.beginPath();
                        context.moveTo(200, 400);
                        context.lineTo(400, 600);
                        context.stroke();
                        context.beginPath();
                        context.moveTo(400, 400);
                        context.lineTo(200, 600);
                        context.stroke();
                    }
                    if (box9 == true) {
                        spaces[8] = 'x'
                        console.log(spaces)
                        counter = counter + 1
                        console.log(counter)


                        context.beginPath();
                        context.moveTo(400, 400);
                        context.lineTo(600, 600);
                        context.stroke();
                        context.beginPath();
                        context.moveTo(600, 400);
                        context.lineTo(400, 600);
                        context.stroke();
                    }

                } else {
                    let randO = Math.floor(Math.random() * (9));
                    while (spaces[randO] !== null) {
                        randO = Math.floor(Math.random() * (9));
                    }
                    spaces[randO] = 'o'
                    if (spaces[0] === 'o') {
                        context.beginPath();
                        context.arc(100, 100, 40, 0, 2 * Math.PI);
                        context.stroke();
                    }
                    if (spaces[1] === 'o') {
                        context.beginPath();
                        context.arc(300, 100, 40, 0, 2 * Math.PI);
                        context.stroke();
                    }
                    if (spaces[2] === 'o') {
                        context.beginPath();
                        context.arc(500, 100, 40, 0, 2 * Math.PI);
                        context.stroke();
                    }
                    if (spaces[3] === 'o') {
                        context.beginPath();
                        context.arc(100, 300, 40, 0, 2 * Math.PI);
                        context.stroke();
                    }
                    if (spaces[4] === 'o') {
                        context.beginPath();
                        context.arc(300, 300, 40, 0, 2 * Math.PI);
                        context.stroke();
                    }
                    if (spaces[5] === 'o') {
                        context.beginPath();
                        context.arc(500, 300, 40, 0, 2 * Math.PI);
                        context.stroke();
                    }
                    if (spaces[6] === 'o') {
                        context.beginPath();
                        context.arc(100, 500, 40, 0, 2 * Math.PI);
                        context.stroke();
                    }
                    if (spaces[7] === 'o') {
                        context.beginPath();
                        context.arc(300, 500, 40, 0, 2 * Math.PI);
                        context.stroke();
                    }
                    if (spaces[8] === 'o') {
                        context.beginPath();
                        context.arc(500, 500, 40, 0, 2 * Math.PI);
                        context.stroke();
                    }
                    counter = counter + 1
                }
            }
        }, false);

    }
</script>

</body>

</html>