<?php
//Yahtzee header
if (isset($_POST['gameid'])){		
	$title = 'Please wait for your game to be initialized =D';
    $meta = 'http-equiv="REFRESH" content="2;url=yahtzee.php?gameid='.$_POST['gameid'].'"';
}
?>
