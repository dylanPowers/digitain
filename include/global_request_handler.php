<?php 

define('SERVER_NAME_MY_', 'my.digi-tain.com');
define('SERVER_NAME_LOCALHOST', '127.0.0.1');
define('SERVER_NAME_BLOG_', 'blog.digi-tain.com');
define('SERVER_NAME_HOME', 'digi-tain.com');
define('SERVER_NAME_HOME_ALT', 'www.digi-tain.com');
define('SERVER_NAME_TRACKER_', 'tracker.digi-tain.com');

include_once('lib/debug.php');

if ($_SERVER['SERVER_NAME'] == SERVER_NAME_LOCALHOST){
    define('DEBUG', true);
}

if (!defined('DEBUG')){
    define('DEBUG', false);
}

if (DEBUG){
    $debug_data = new Debug();
}


//Does some pre processing before the web page is sent to the user.
include('logic_handler.php');

//Puts together the web page visual components and sends it to the user.
include('page_out.php');



?>
