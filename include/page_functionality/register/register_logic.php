<?php
//First define our variables
@ $username = trim($_POST['reg_username']);
@ $password = $_POST['reg_password'];
@ $password_confirm = $_POST['reg_password_confirm'];

//Define the class display to store the page display variables.
class display{
    function __construct(){
        $this->root = false;
        $this->missing_input = false;
        $this->passwords_no_match = false;
        $this->user_exists = false;
        $this->successful = false;
        $this->insert_error = false;
    }
}

//Initialize the display class.
$display = new display();

//Check if the defined variables even exist
if (!$username && !$password && !$password_confirm){
    //Then display the root page.
    $display->root = true;
}

//Check that a field isn't missing
elseif (!$username || !$password || !$password_confirm){
    //Then display the missing input page.
    $display->missing_input = true;
}

//Check that the passwords match and if not...
elseif ($password != $password_confirm){
    //Then display the unmatching passwords page.
    $display->passwords_no_match = true;
}

//Else everything is in order and we can begin processing.
else{
    
    //First we want to check that the username doesn't already exist in our system.
    $result = $db_connection->query_select('digitain.users', array('username'=>$username));
    //IF there is a result...
    if ($result->num_rows > 0){
        //then display the username already exists page.
        $display->user_exists = true;
    }
    
    //Else we can continue processing.
    else{
        $display->username = $username;
        
        //Now we want to insert the user into the database.
        $result = $db_connection->query_insert('digitain.users', array('username'=>$username, 'password'=>sha1($password)));
        
        //If the result was successful...
        if ($result){
            //then tell the user.
            $display->successful = true;
        }
        
        //Else if it failed then alert the user.
        else{
            $display->insert_error = true;
        }
    }     
}
?>
