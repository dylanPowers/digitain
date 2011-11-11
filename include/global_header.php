<?php
/*
 * This will create a unified header for the whole website. The default title
 * is set to 'Digitain =D' if undefined. 
 */
if (!isset($title)){
    $title='Digitain =D';
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>    
    <title><?php echo $title; ?></title>
    <link rel="shortcut icon" href="http://digi-tain.com/favicon.ico" />

    <?php
if (DEBUG){
    ?>
    <link rel="stylesheet" type="text/css" href="http://127.0.0.1/style_sheets/reset.css" />
    <link rel="stylesheet" type="text/css" href="http://127.0.0.1/style_sheets/global.css" />
    <link rel="stylesheet" type="text/css" href="http://127.0.0.1/style_sheets/home.css" />    
    <?php
}
else{
    ?>
    <link rel="stylesheet" type="text/css" href="http://digi-tain.com/style_sheets/reset.css" />    
    <link rel="stylesheet" type="text/css" href="http://digi-tain.com/style_sheets/global.css" />
    <link rel="stylesheet" type="text/css" href="http://digi-tain.com/style_sheets/home.css" />    
    <?php
}
if (isset($link)){
    echo '<link '.$link.' />';
}
    ?>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <?php   
if (isset($meta)){
    echo '<meta '.$meta.' />';
}
    ?>
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
	<header id="global_header">
    	<nav>
        	<ul>
            	<li class="left"><a href="http://blog.digi-tain.com">Blog.</a></li>
                <a href="http://digi-tain.com"><li class="anchor">Home</li></a>
                <li class="right"><a href="http://my.digi-tain.com">My.</a></li>
            </ul>
        </nav>
        <?php $logo_path = get_logo(); ?>
    	<a href="/" title="Homepage"><img class="global_logo" draggable="true" src="/imgs/digitain_logo.png" alt="Digitain Logo" /></a>
        
<?php

    if (isset($_SESSION['valid_user'])){
        echo '	
				<p>Logged in as '.$_SESSION['username'].'</p>
				<a class="logout" href="/logout.php">Logout</a>';
    }
    else{
        if (isset($login_error)){
            echo $login_error;
        }
        ?>
        <div class="login">
            <form action="<?php $_SERVER["REQUEST_URI"] ?>" method="post">
                <ul class="login_descripts">
                    <li>Username</li>
                    <li>Password</li>
                </ul>
                <div class="login_boxes">
                    <input type="text" name="username" size="10" maxlength="50" title="Yer ID?" />
                    <input type="password" name="password" size="10" maxlength="50" title="Wuz da password?" />
                </div>
                <a href="/register.php">Register Here</a>
                <input class="submit_button" type="submit" value="Log In" />
            </form>
        </div>
        
        <?php
    }
?>
	</header>    
	<div id="global_page_elements">
    	<nav class="nav_left">
            <?php //Someday.... generate_nav();?>
        	<ul>
            	<li><a href="/yahtzee/">Yahtzee</a></li>
                <li><a href="/about.php">About</a></li>
                <li><a href="/site-map.php">Site Map</a></li>
            </ul>
        </nav>
    	<div id="global_page_content_spacer">
        	<div id="global_page_content">
