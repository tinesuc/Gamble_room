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
            if(!isset($_SESSION['game_start'])||$_SESSION['game_start']=false){
                $_SESSION['target'] = rand(35, 70);
                echo $_SESSION['target'];
                $_SESSION['game_start']=true;
            }else{
                echo $_SESSION['target'];
            }
            $target = $_SESSION["target"];
            if(isset($_POST['reset'])){
                session_destroy();
                header('Location: '.$_SERVER['SCRIPT_NAME']);
            }
            if(!isset($_SESSION['activep'])){
                $_SESSION['activep']=0;
            }
            $active = $_SESSION['activep'];
            
            if(!isset($_SESSION['p1'])){
                $_SESSION['p1']=(object)[
                    'dice'=> array(),
                    'win'=>false,
                    'lose'=>false,
                    'gmb'=>false, 
                    'id'=>0
                    ];
            }
            $dice1 = $_SESSION['p1'];
            if(!isset($_SESSION['p2'])){
                $_SESSION['p2']=(object)[
                    'dice'=> array(),
                    'win'=>false,
                    'lose'=>false,
                    'gmb'=>false,
                    'id'=>1
                    ];
            }
            $dice2 = $_SESSION['p2'];
            if(!isset($_SESSION['p3'])){
                $_SESSION['p3']=(object)[
                    'dice'=> array(),
                    'win'=>false,
                    'lose'=>false,
                    'gmb'=>false,
                    'id'=>2
                    ];
            }
            $dice3 = $_SESSION['p3'];
            
            function procDies($dice){
                global $target;
                global $active;
                    echo "oughn ".array_sum($dice->dice)." ".isset($_POST["gamble"])." ".$target;
                    if($dice->gmb){
                        echo "tcugbhkkjl";
                    }
                if(!$dice->lose&&!$dice->win&&$active==$dice->id){
                    if(array_sum($dice->dice)<$target&&isset($_POST["gamble"])){
                        $r = rand(1, 6);
                        echo "tebnnntenetnten ".$active." ".$dice->id;
                        array_push($dice->dice, $r);
                        $active = ($active+1)%3;
                        $_SESSION['activep']=$active;
                        header('Location: '.$_SERVER['SCRIPT_NAME']);
                    }else if(array_sum($dice->dice)>$target&&count($dice->dice)<20&&$dice->gmb){
                        $rm = array_sum($dice->dice)*(1-rand(1, 6)*0.15);
                        echo "tebnnntenetnten ".$active." ".$dice->id;
                        $diceNew = array();
                        foreach($dice->dice as $die){
                            if(array_sum($diceNew)+$die>=$rm)break;
                            array_push($diceNew, $die);
                        }
                        $dice->dice = $diceNew;
                        $active = ($active+1)%3;
                        $_SESSION['activep']=$active;
                        header('Location: '.$_SERVER['SCRIPT_NAME']);
                    }else if(array_sum($dice->dice)==$target){
                        echo "tebnnntenetnten ".$active." ".$dice->id;
                        $dice->win=true;
                        $active = -10;
                        $_SESSION['activep']=$active;
                    }else if(count($dice->dice)>=20){
                        echo "tebnnntenetnten ".$active." ".$dice->id;
                        $active = ($active+1)%3;
                        $_SESSION['activep']=$active;
                        $dice->lose=true;
                    }else{
                        echo "tebnnntenetnten ".$active." ".$dice->id." ".$dice->gmb;
                        
                    }
                    $met = end($dice->dice);
                    if(!empty($met))echo "<img src=\"img/dice{$met}.gif\">" ;
                    echo "kbijnjnl ".$active." ".$dice->id;
                }
                unset($_POST["gamble"]);
                $dice->gmb=false;
            }
            function out($dice){
                global $target;
                global $active;
                echo 'class="';
                if($active==$dice->id){
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
        <form method="POST" action="?action=reset" id = "resetf">
            <button id="reset" name="reset" >
                Reset
            </button>
        </form>
    </div>
    <div id="main">
        <div id="p1">
            <div id="top">
                <?php
                //$active=1;
                if(isset($_POST["gamble"])){
                    echo "tcugbhkkjl";
                    $dice1->gmb=true;
                }
                procDies($dice1);
                unset($_POST["gamble"]);
                $_SESSION['p1'] = $dice1;
                ?>
            </div>
            <div id="core">
                <div id="left" <?php
                        out($dice1);
                    ?>>
                <div id="right">
                    <form method="POST" action="?action=gamble">
                        <button id="gamble" name="gamble" >
                            Gamble
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div id="p2">
            <div id="top">
                <?php
                //$active=1;
                if(isset($_POST["gamble"])){
                    echo "tcugbhkkjl";
                    $dice2->gmb=true;
                }
                procDies($dice2);
                unset($_POST["gamble"]);
                $_SESSION['p2'] = $dice2;
                ?>
            </div>
            <div id="core">
                <div id="left" <?php
                        out($dice2);
                    ?>>
                <div id="right">
                    <form method="POST" action="?action=gamble">
                        <button id="gamble" name="gamble" >
                            Gamble
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div id="p3">
            <div id="top">
                <?php
                //$active=1;
                if(isset($_POST["gamble"])){
                    echo "tcugbhkkjl";
                    $dice3->gmb=true;
                }
                procDies($dice3);
                unset($_POST["gamble"]);
                $_SESSION['p3'] = $dice3;
                ?>
            </div>
            <div id="core">
                <div id="left" <?php
                        out($dice3);
                    ?>>
                <div id="right">
                    <form method="POST" action="?action=gamble">
                        <button id="gamble" name="gamble" >
                            Gamble
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
    <?php
    session_write_close();
    ?>
</body>
</html>