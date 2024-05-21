<?php
session_start();
//var_dump($_SESSION);
if(isset($_POST['reset'])){
    session_destroy();
    header('Location: index.php');
    exit;
}

// Initialize or retrieve session variables
if(!isset($_SESSION['game_start']) || $_SESSION['game_start'] == false) {
    $_SESSION['target'] = rand(35, 70);
    $_SESSION['game_start'] = true;
}
$target = $_SESSION['target'];
$active = isset($_SESSION['activep']) ? $_SESSION['activep'] : 0;
$dice1 = isset($_SESSION['p1']) ? $_SESSION['p1'] : (object)['id' => 1, 'dice' => [], 'lose' => false, 'win' => false];
$dice2 = isset($_SESSION['p2']) ? $_SESSION['p2'] : (object)['id' => 2, 'dice' => [], 'lose' => false, 'win' => false];
$dice3 = isset($_SESSION['p3']) ? $_SESSION['p3'] : (object)['id' => 3, 'dice' => [], 'lose' => false, 'win' => false];
$lim=20;

function alllose(){
    global $dice1, $dice2, $dice3, $active, $lim;
    if(count($dice1->dice)>=$lim&&count($dice2->dice)>=$lim&&count($dice3->dice)>=$lim){
        $_SESSION['activep'] = -10;
        $active = -10;
        echo "<script>setTimeout(function()    {   "
                . "form = document.createElement('form');"
                . "form.method='POST';"
                . "form.action='index.php';"
                . "inp = document.createElement('button');"
                . "inp.name = 'reset';"
                . "form.appendChild(inp);"
                . "document.body.appendChild(form);"
                . "form.submit();"
        . "    }, 5000);</script>";
    }
}
alllose();
// Function to increment active player
function incActive() {
    global $active;
    $active = ($active % 3) + 1;
    echo $active;
    $_SESSION['activep'] = $active;
}
function wins($dice){
    global $target, $active;
    $dice->win=true;
    $_SESSION['win']=$dice->id;
    $active = -10;
    $_SESSION['activep']=$active;
    echo "<script>"
    . "$('body').fireworks();"
    . "setTimeout(function()    {   "
            . "form = document.createElement('form');"
            . "form.method='POST';"
            . "form.action='index.php';"
            . "inp = document.createElement('button');"
            . "inp.name = 'reset';"
            . "form.appendChild(inp);"
            . "document.body.appendChild(form);"
            . "form.submit();"
    . "    }, 5000);</script>";
}
// Function to process dice rolls
function procDies($dice) {
    global $target, $active, $lim;
    if (!$dice->lose && !isset($_SESSION['win']) && $active == $dice->id) {
        if (isset($_POST["gamble" . $dice->id])&&array_sum($dice->dice)<$target&&count($dice->dice)<20) {
            $r = rand(1, 6);
            array_push($dice->dice, $r);
            echo "<img src=\"img/dice{$r}.png\">" ;
            
            if (array_sum($dice->dice) < $target) {
                incActive();
            }
            if(array_sum($dice->dice)==$target){
                wins($dice);
                return;
            }
            if(count($dice->dice)>=$lim){
                $dice->lose=true;
                //incActive();
            }
            return;
        }
        if(isset($_POST["gamble" . $dice->id])&&array_sum($dice->dice)>$target&&count($dice->dice)<20){
            $r = rand(1, 6);
            $rm = array_sum($dice->dice)*(1-$r*0.15);
            echo "<img src=\"img/dice{$r}.png\">" ;
            $diceNew = array();
            foreach($dice->dice as $die){
                if (array_sum($diceNew) + $die >= $rm) {
                    break;
                }
                array_push($diceNew, $die);
            }
            $dice->dice = $diceNew;
            incActive();
            return;
        }
    }
//    $_SESSION['p'.$active] = $dice;
//    while((!$active<0)&&$_SESSION['p'.$active]->lose){
//        echo $_SESSION['p'.$active];
//        incActive;
//        alllose();
//    }
    if ($dice->lose && $active == $dice->id) {
        incActive();
    }
    alllose();
    if(isset($_SESSION['win'])||$active==-10){
         echo "<script>setTimeout(function()    {   "
            . "form = document.createElement('form');"
            . "form.method='POST';"
            . "form.action='index.php';"
            . "inp = document.createElement('button');"
            . "inp.name = 'reset';"
            . "form.appendChild(inp);"
            . "document.body.appendChild(form);"
            . "form.submit();"
    . "    }, 5000);</script>";
    }
}
function playId(){
    global $target, $active;
    alllose();
    if($active<0)return;
    while((!$active<0)&&$_SESSION['p'.$active]->lose){
        incActive;
        alllose();
    }
    if($active<0)return;
    echo "playing".$active." ";
}

// Function to output dice results
function out($dice) {
    global $target, $active;
    echo $active." ";
    echo 'class="';
    if($active==$dice->id){
    }
    if($dice->lose){
        echo "overcube ";
    }else if(array_sum($dice->dice)>$target){
        echo "overscore ";
    }else if($dice->win){
        echo "zmaga ";
    }
    echo '"';
    echo ">";
    echo '<div id="zmaga">';
    echo '    You won!';
    echo '</div>';
    echo '<div id="overscore">';
    echo '    You have too many point, <br>throw to see how many you lose (15%/point)';
    echo '</div>';
    echo '<div id="overcube">';
    echo '    You threw too many dice and lost!';
    echo '</div>';
    foreach($dice->dice as $die){
        echo "<img src=\"img/dice".$die.".png\">";
    }
    echo '</div';
}

// Process form submissions
function proc($dice, $id){
    global $dice1,$dice2,$dice3;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        procDies($dice);

    }
}
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
    <script src="js/jquery.fireworks.js"></script>
    <?php
        echo "<script>$(document).on('keypress',function(e) {"
                . "if(e.which == 119) {   "
                    . "form = document.createElement('form');"
                    . "form.method='POST';"
                    . "form.action='index.php?action=reset';"
                    . "inp = document.createElement('button');"
                    . "inp.name = 'reset';"
                    . "form.appendChild(inp);"
                    . "document.body.appendChild(form);"
                    . "form.submit();"
                . "}"
            . "});</script>";
    ?>
</head>
<body>
    <div id="title" >
        <h1 class="trs" trtxt="$title$">Gamble room</h1>
        <h1 id="target">Get exactly <?php
            echo $target;
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
                    proc($dice1, "p1");
                    ?>
                </div>
                <div id="core">
                    <div id="left" <?php
                            out($dice1);
                        ?>>
                    <div id="text"><?php
                        echo $dice1->name;
                    ?></div>
                    <div id="right">
                            <button id="gamble" name="gamble1" >
                                Gamble
                            </button>
                    </div>
                </div>
            </div>
            <div id="p2">
                <div id="top">
                    <?php
                    //$active=1;
                    proc($dice2, "p2");
                    ?>
                </div>
                <div id="core">
                    <div id="left" <?php
                            out($dice2);
                        ?>>
                    <div id="text"><?php
                        echo $dice2->name;
                    ?></div>
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
                    proc($dice3, "p3");
                    ?>
                </div>
                <div id="core">
                    <div id="left" <?php
                            out($dice3);
                        ?>>
                    <div id="text"><?php
                        echo $dice3->name;
                    ?></div>
                    <div id="right">
                            <button id="gamble" name="gamble3" >
                                Gamble
                            </button>
                    </div>
                </div>
            </div>
                <div style="display:none" class="<?php
                    playId();
                ?>"></div>
        </div>
    </form>
</body>
</html>

<?php
alllose();
session_write_close();
?>
