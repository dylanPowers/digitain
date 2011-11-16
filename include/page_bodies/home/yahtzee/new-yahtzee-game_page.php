<?php $install = array('title'=>'New Game =D', 'URI'=>'/yahtzee/new-yahtzee-game.php', 'logic_path'=>'/yahtzee/new-yahtzee-game/new-yahtzee-game_logic.php', 'security_key'=>'ade53d4f906bc038fc04041c1d89b9f78f192f69');
//function install_page(){
//    $title='New Game =D';
//    $URI = '/yahtzee/new-yahtzee-game';
//    $add_logic = true;
//    return array($title, $URI, $add_logic);
//}
?>
<div class="standard_layout">
    <h1>New Game</h1>
    <h3>Let's have some fun! =D</h3>

<?php 
	//Set variables
	if (isset($_SESSION['valid_user'])){
		$userid = $_SESSION['valid_user'];
	}
	else {		
		@ $userid = $_POST['userid'];
	}
	@ $num_players = $_POST['num_players'];
	
	if (isset($_POST['gameid'])){
		$gameid = $_POST['gameid'];
		$_SESSION['yahtzee_gameid'] = $gameid;
		foreach ($_POST as $key => $value){
			if ($key != 'gameid'){
				$playername = trim($value);
				$db_connection->query_insert('digitain.yahtzee_scores', array('gameid'=>$gameid, 'playername'=>$playername));
			}
		}	
		echo 
'	<h3>Your game is now being initialized</h3>';			
	}	
	//Get number of players if none
	elseif (!$num_players){
		if (!$userid){
			echo 
'	<p><em><b>You '."haven't".' logged in yet! '."It's".' highly reccomended you log in.
	<br />When the game starts be sure to make a note of your game id in the URL ;)</b></em></p>';
			$userid = '0';
		}
		echo 
'	<table>
		<form action="new-yahtzee-game.php" method="post">
			<input type="hidden" name="userid" value="'.$userid.'" />
			<tr>
				<td size="100">
					Number of Players
				</td>
				<td size="15">
					<select name="num_players">';
			for ($i=1; $i < 100; $i++){
				echo 
	'					<option value="'.$i.'">'.$i.'</option>';
			}
			echo 
	'			</td>
			</tr>
			<tr>
				<td align="center" colspan="2">
					<input type="submit" value="Submit" />
				</td>
			</tr>
		</form>
	</table>';
	}
		
	//Else get player names instead.
	else{
		$db_connection->query_insert('digitain.yahtzee_games', array('gameid'=>0, 'userid'=>$userid));		
		$gameid = $db_connection->insert_id;
		echo '
	<h3>Enter your player names</h3>
	<form action="new-yahtzee-game.php" method="post">	
		<input type="hidden" name="gameid" value="'.$gameid.'" />
		';		
		for ($i=1; $i <= $num_players; $i++){
			echo '
		<input type="text" name="player'.$i.'" size="15" maxlength="50" />
		<br />	
			';
		}
		echo 
'		<input type="submit" value="Submit" />
	</form>';	
	}
?>
    </div>