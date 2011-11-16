<?php
/*Legacy Page Handler
 * 
 */

include_once('global_request_handler.php');

//
//define('SERVER_NAME_MY_', 'my.digi-tain.com');
//define('SERVER_NAME_LOCALHOST', '127.0.0.1');
//define('SERVER_NAME_BLOG_', 'blog.digi-tain.com');
//define('SERVER_NAME_HOME', 'digi-tain.com');
//define('SERVER_NAME_HOME_ALT', 'www.digi-tain.com');
//
//include('function_library.php');
//include('session_initialization.php');
//
//if ($_SERVER['SERVER_NAME'] == SERVER_NAME_LOCALHOST){
//    define('DEBUG', true);
//}
//
//if (!defined('DEBUG')){
//    define('DEBUG', false);
//}
//
//if (DEBUG){
//    $debug_data = '<!--';
//    function debug($var){
//        global $debug_data;
//        ob_start();     
//        var_dump($var);
//        $string = ob_get_contents();
//        ob_end_clean();
//        $debug_data .= $string;
//    }
//}
//
////If the user has just tried to log on.
//if (isset($_POST['username']) && isset($_POST['password'])){
//    include('global_login.php');
//    $login_error = login();		
//}
//
///*
// * The purpose of the $logic_path is for a web page to be able to initialize any
// * program logic that needs to be processed before anything is displayed
// * to the user.
//*/
//if (isset($logic_path)){
//    include('page_functionality/'.$logic_path);
//}
//
////We want to be sure to include the site-wide digitain header.
//if ($_SERVER['SERVER_NAME'] == 'my.digi-tain.com' or $_SERVER['SERVER_PORT'] == '81'){
//    include('my_header.php');
//}
//else{
//    include('global_header.php');
//}
////Content
//if (isset($page_path)){
//    include('pages/'.$page_path);
//}
////We also want to be sure to include the site-wide footer.
//include('global_footer.php');

?>
