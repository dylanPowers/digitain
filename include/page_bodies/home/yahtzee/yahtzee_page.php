<div class="standard_layout">
<?php 
	
    @ $gameid=$_GET['gameid'];    
    if (!isset($gameid) && !isset($_POST['gameid']) && !isset($_SESSION['yahtzee_gameid'])){        
        echo'
    <h2>Missing gameid</h2>
    <a href="new-yahtzee-game.php">Click to start a New Game</a>';
    }    
    else{
        if (!$gameid){
            if (isset($_SESSION['gameid'])){
				$gameid = $_SESSION['yahtzee_gameid'];
			}
			else{
				$gameid = $_POST['gameid'];
				$_SESSION['yahtzee_gameid'] = $gameid;
			}
        }
        require('../../include/yahtzee_score.php');
        new yahtzee_game($db_connection, $gameid, $_POST);		
    }
?>
</div>