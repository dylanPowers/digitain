<?php

function login(){
    global $db_connection;
	$username = trim($_POST['username']);
	$password = $_POST['password'];
	if (!$db_connection){
		$login_error = '<p class="global_error">Connection to database failed. You were not logged in</p>';
        return $login_error;
	}
	else{
		$result = $db_connection->query_select('digitain.users', array('username'=>$username, 'password'=>sha1($password)));
        
		/*
         * If they are in the database then register the userid and username.
         * 
         */
		if (@ $result->num_rows){			
			$user_array = $result->fetch_assoc();
			$_SESSION['valid_user'] = $user_array['userid'];
			$_SESSION['username'] = $user_array['username'];
			$_SESSION['access_privileges'] = $user_array['access_privileges'];
            
            /*
             * To protect the privacy of everyday visitors, we will not log their ip addresses. For visitors with bonus content access
             * we will log their ip address for purposes of security, as most torrent programs don't allow for password access to 
             * trackers (Azuraus is the only one I know). This method will allow for a psuedo log in system that should be secure enough.
             */
            if ($user_array['access_privileges'] == 'bonus'){
                $ip_int = ip2dec($_SERVER['REMOTE_ADDR']);
                $db_connection->query_update('digitain.users', array('last_ip'=>$ip_int), array('userid'=>$user_array['userid']));
            }
		}
		else {
			$login_error = '<p class="global_error">You were not logged in.</p>';
            return $login_error;
		}
	}
    
}

?>