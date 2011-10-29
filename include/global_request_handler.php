<?php 

define('SERVER_NAME_MY_', 'my.digi-tain.com');
define('SERVER_NAME_LOCALHOST', '127.0.0.1');
define('SERVER_NAME_BLOG_', 'blog.digi-tain.com');
define('SERVER_NAME_HOME', 'digi-tain.com');
define('SERVER_NAME_HOME_ALT', 'www.digi-tain.com');

if ($_SERVER['SERVER_NAME'] == SERVER_NAME_LOCALHOST){
    define('DEBUG', true);
}

if (!defined('DEBUG')){
    define('DEBUG', false);
}

if (DEBUG){
    $debug_data = '<!--';
    function debug($var){
        global $debug_data;
        ob_start();     
        var_dump($var);
        $string = ob_get_contents();
        ob_end_clean();
        $debug_data .= $string;
    }
}






?>
