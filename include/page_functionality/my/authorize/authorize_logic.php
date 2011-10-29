<?php
class display{
    function __construct(){
        $this->lost = false;
        $this->success = false;
        $this->internal_error = false;
        $this->not_logged_in = false;
        $this->incorrect_code = false;
    }    
}
$display = new display();

if (isset($_SESSION['valid_user'])){
    if (@$_SESSION['access_privileges'] == 'bonus'){
        $display->lost = true;
    }    
    elseif (isset($_POST['authorization'])){
        if ($_POST['authorization'] == '5tyj5dczR8'){
            $result = $db_connection->query_update('digitain.users', array('access_privileges'=>'bonus'), array('userid'=>$_SESSION['valid_user']));
            if ($result){
                $_SESSION['access_privileges'] = 'bonus';
                $display->success = true;
            }
            else{
                $display->internal_error = true;
            }
        }
        else{
            $display->incorrect_code = true;
        }
    }
}
else{
    $display->not_logged_in = true;
}
?>
