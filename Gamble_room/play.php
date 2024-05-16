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
        <h1 id="target">Get exactly <?php
            var_dump($_POST);
            echo "<br>";
            var_dump($_SESSION);
            if(isset($_POST['reset'])){
                session_destroy();
                header('Location: index.php');
            }
            if(!isset($_SESSION['game_start'])||$_SESSION['game_start']=false){
                $_SESSION['target'] = rand(35, 70);
                echo $_SESSION['target'];
                $_SESSION['game_start']=true;
            }else{
                echo $_SESSION['target'];
            }
            $target = $_SESSION["target"];
            if(!isset($_SESSION['activep'])){
                $_SESSION['activep']=0;
            }
            $active = $_SESSION['activep'];
            
            $dice1 = $_SESSION['p1'];
            $dice2 = $_SESSION['p2'];
            $dice3 = $_SESSION['p3'];
            function incActive(){
                global $active;
                $active = ($active+1)%3;
                $_SESSION['activep']=$active;
            }
            function isgamble(){
                return isset($_POST["gamble"])
                    || isset($_POST["gamble2"])
                    || isset($_POST["gamble3"])
                ;
                
            }
            function procDies($dice){
                global $target;
                global $active;
            var_dump($dice);
                if(!$dice->lose&&!isset($_SESSION['win'])&&$active==$dice->id){
                    if(array_sum($dice->dice)<$target&&isgamble()){
                        $r = rand(1, 6);
                        array_push($dice->dice, $r);
                        incActive();
                        header('Location: '.$_SERVER['SCRIPT_NAME']);
                    }else if(array_sum($dice->dice)>$target&&count($dice->dice)<20&&isgamble()){
                        $rm = array_sum($dice->dice)*(1-rand(1, 6)*0.15);
                        $diceNew = array();
                        foreach($dice->dice as $die){
                            if(array_sum($diceNew)+$die>=$rm)break;
                            array_push($diceNew, $die);
                        }
                        $dice->dice = $diceNew;
                        incActive();
                        header('Location: '.$_SERVER['SCRIPT_NAME']);
                    }else if(array_sum($dice->dice)==$target){
                        $dice->win=true;
                        $_SESSION['win']=true;
                        $active = -10;
                        $_SESSION['activep']=$active;
                        echo "<script>setTimeout(function()    {   $('#some_id').load('index.php');    }, 5000);</script>";
                    }else if(count($dice->dice)>=20){
                        incActive();
                        $dice->lose=true;
                    }else{
                        echo "tebnnntenetnten ".$active." ".$dice->id." ".$dice->gmb;
                    }
                    $met = end($dice->dice);
                    if(!empty($met))echo "<img src=\"img/dice{$met}.gif\">" ;
                    echo "<img src=\"img/dice{$met}.gif\">" ;
                    echo "kbijnjnl ".$active." ".$dice->id;
                }
                unset($_POST["gamble"]);
                unset($_POST["gamble2"]);
                unset($_POST["gamble3"]);
            }
            function out($dice){
                global $target;
                global $active;
                echo 'class="';
                if($_SESSION['activep']==$dice->id){
                    echo "playing ";
                }
                if(array_sum($dice->dice)>$target){
                    echo "overscore ";
                }
                if($dice->win){
                    echo "zmaga ";
                }
                if($dice->lose){
                    echo "overcube ";
                }
                echo '"';
                echo ">";
                echo '<div id="zmaga">';
                echo '    Zmagal si!';
                echo '</div>';
                echo '<div id="overscore">';
                echo '    Imaš preveč pik, <br>meči za koliko jih boš zgubil (15%/piko)';
                echo '</div>';
                echo '<div id="overcube">';
                echo '    Metal si preveč kock in si zgubil';
                echo '</div>';
                foreach($dice->dice as $die){
                    echo "<img src=\"img/dice".$die.".gif\">";
                }
                echo "tebnnntenetnten ".$active." ".$dice->id;
                echo '</div';
            }
            
            
        ?> points</h1>
        <form method="POST" action="index.php?action=reset" id = "resetf">
            <button id="reset" name="reset" >
                Reset
            </button>
        </form>
    </div>
    <form method="POST">
        <div id="main">
            <div id="p1">
                <div id="top">
                    <?php
                    //$active=1;
                    procDies($dice1);
                    $_SESSION['p1'] = $dice1;
                    ?>
                </div>
                <div id="core">
                    <div id="left" <?php
                            out($dice1);
                        ?>>
                    <div id="right">
                            <button id="gamble" name="gamble" >
                                Gamble
                            </button>
                    </div>
                </div>
            </div>
            <div id="p2">
                <div id="top">
                    <?php
                    //$active=1;
                    procDies($dice2);
                    $_SESSION['p2'] = $dice2;
                    ?>
                </div>
                <div id="core">
                    <div id="left" <?php
                            out($dice2);
                        ?>>
                    <div id="right">
                            <button id="gamble" name="gamble2" >
                                Gamble
                            </button>
                    </div>
                </div>
            </div>
            <div id="p3">
                <div id="top">
                    <?php
                    //$active=1;
                    procDies($dice3);
                    $_SESSION['p3'] = $dice3;
                    ?>
                </div>
                <div id="core">
                    <div id="left" <?php
                            out($dice3);
                        ?>>
                    <div id="right">
                            <button id="gamble" name="gamble3" >
                                Gamble
                            </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php
    session_write_close();
    ?>
</body>
</html>