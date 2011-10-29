
	<div class="standard_layout">
<?php
if ($success){
	echo'
		<h3>Logout Successful</h3>
		<br />
		<a href="/">Homepage</a>';
}
else{
	echo'
		<h3 class="fail">'."I'm".' sorry an error occurred. Logout Unsuccessful</h3>
		<br />
		<a href="/">Homepage</a>';
}
?>
	</div>

