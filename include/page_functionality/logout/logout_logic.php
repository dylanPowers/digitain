<?php
unset($_SESSION['valid_user']);
unset($_SESSION['username']);
unset($_SESSION['access_privileges']);
if (isset($_SESSION['valid_user']) || isset($_SESSION['username']) || isset($_SESSION['access_privileges'])){
	$success = False;
}
else{
	$success = True;
}

?>
