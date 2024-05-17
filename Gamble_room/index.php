<?php
session_start();
?>
<html>
<head>
    <meta charset="UTF-8">
    <title class="trs" trtxt="$title$">Gamble room</title>
    <link rel="stylesheet" href="css/main.css" type="text/css" media="all">
    <link rel="stylesheet" href="css/range.css" type="text/css" media="all">
    <link rel="icon" type="image/x-icon" href="img/icon.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        playing=false;
    </script>
    <script src="js/lib.js"></script>
    <script src="js/main.js"></script>
    <script src="js/translate.js"></script>
</head>
<body>
    <div id="title" >
        <h1 class="trs" trtxt="$title$">Gamble room</h1>
        <form method="POST" action="?action=reset" id = "resetf">
            <button id="reset" name="reset" >
                Reset
            </button>
        </form>
    </div>
    <div id="main">
        <form method="POST" >
            <?php
                if(isset($_POST['reset'])){
                    session_destroy();
                    header('Location: index.php');
                }
                if(isset($_POST["play"])){
                    $_SESSION['p1']=(object)[
                            'dice'=> array(),
                            'win'=>false,
                            'lose'=>false,
                            'gmb'=>false, 
                            'id'=>1,
                            'name'=>isset($_POST["name1"])&&!empty($_POST["name1"])?$_POST["name1"]:'--',
                        ];
                    $_SESSION['p2']=(object)[
                            'dice'=> array(),
                            'win'=>false,
                            'lose'=>false,
                            'gmb'=>false, 
                            'id'=>2,
                            'name'=>isset($_POST["name2"])&&!empty($_POST["name2"])?$_POST["name2"]:'--',
                        ];
                    $_SESSION['p3']=(object)[
                            'dice'=> array(),
                            'win'=>false,
                            'lose'=>false,
                            'gmb'=>false, 
                            'id'=>3,
                            'name'=>isset($_POST["name3"])&&!empty($_POST["name3"])?$_POST["name3"]:'--',
                        ];
                    $_SESSION['activep']=1;
                    session_write_close();
                    header('Location: play2.php');
                    exit;
                }
            ?>
            <div id="inp">
                <div id="p1">
                    <div id="core">
                        <div id="right">
                            <ul>
                                <li>Player 1</li>
                                <li><input id="name" name="name1" placeholder="User name"></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="p2">
                    <div id="core">
                        <div id="right">
                            <ul>
                                <li>Player 2</li>
                                <li><input id="name" name="name2" placeholder="User name"></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="p3">
                    <div id="core">
                        <div id="right">
                            <ul>
                                <li>Player 3</li>
                                <li><input id="name" name="name3" placeholder="User name"></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id="plbtn">
                <button id="play" name="play" >
                Play
                </button>
            </div>
        </form>
    </div>
    <?php
    session_write_close();
    ?>
</body>
</html>