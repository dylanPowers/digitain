    <div class="standard_layout">
        <h1>Let's Play Yahtzee!</h1>
<?php 
if (isset($_SESSION['valid_user'])){
	$games = $db_connection->query_select('digitain.yahtzee_games', array('userid'=>$_SESSION['valid_user']));
	$num_games = $games->num_rows;			
	if ($num_games == 0){
		echo'
	<button type="button" onclick="window.location.href='."'new_yahtzee_game.php'".'">New Game</button>
	<a href="http://www.hasbro.com/common/instruct/Yahtzee.pdf" target="_blank">Go here for 
                the official Yahtzee rules</a>';
	} 	
	else{
		$gameid = 0;
		$gameid_row = $games->fetch_assoc();
		for ($i=0; $i < $num_games; $i++){
			if ($gameid_row['gameid'] > $gameid){
				$gameid = $gameid_row['gameid'];				
			}
		}
		$_SESSION['yahtzee_gameid'] = $gameid;
		echo '
	<form action="yahtzee.php" method="get"> 	
		<input type="submit" value="Continue Last Game" />
		<input type="hidden" name="gameid" value="'.$gameid.'" />				
	</form>
	<button type="button" onclick="window.location.href='."'new-yahtzee-game.php'".'">New Game</button>';		
	}
}
else{
?>   
            	
        <form action="/yahtzee/" method="post">	
            <div id="in_page_login" class="login">
                <div class="login_bar"></div>
                <ul class="login_descripts">
                    <li>Username</li>
                    <li>Password</li>
                </ul>
                <div class="login_boxes">
                    <input type="text" name="username" size="10" maxlength="50" title="Yer ID?" />
                    <input type="password" name="password" size="10" maxlength="50" title="Wuz da password?" />
                </div>                      
                <input class="submit_button" type="submit" value="Log In" title="Click here =D" />
            </div>
        </form>	
        <div class="login_content">
            <h4><a href="new-yahtzee-game.php">
                    (Click here if you wish to progress without logging in)</a>
            </h4>
            <h5><em>
                Warning: Not logging in may create a frustrating game experience
                with it being easy to lose game data (such as navigating
                off of the web page). If you are able to remember your game id you
                can come back to your game by going to digi-tain.com/yahtzee/yahtzee.php?gameid=*your game id*
            </em></h5>
            <p>
                Caution: Beware the refresh button....
            </p>
            <button type="button" onclick="window.location.href='/register.php'">REGISTER HERE</button>
            <a href="http://www.hasbro.com/common/instruct/Yahtzee.pdf" target="_blank">Go here for 
                the official Yahtzee rules</a>
            <p>
                NOTE: You may log in the middle of a game, but your game will not be affiliated with your login name.
            </p>
        </div>       
<?php 
}?>
	</div>