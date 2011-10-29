<?php
/*These functions are necessary for initializing a web session.
 * 
 */
include('db_connection.php');
$db_connection = new dbConnection();
$success = $db_connection->create_connection();
include('Zebra_Session.php');
$session = new Zebra_Session($db_connection->mysqli_connection);  
?>
