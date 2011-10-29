<?php

if (!isset($title)){
    $title='My.Digitain =D';
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
    <link rel="stylesheet" type="text/css" href="http://127.0.0.1/style_sheets/my.css" />    
    <?php
}
else{
    ?>
    <link rel="stylesheet" type="text/css" href="http://digi-tain.com/style_sheets/reset.css" />    
    <link rel="stylesheet" type="text/css" href="http://digi-tain.com/style_sheets/global.css" />
    <link rel="stylesheet" type="text/css" href="http://digi-tain.com/style_sheets/my.css" />
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
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
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
    	<a href="/" title="Homepage"><img class="global_logo" draggable="true" src="http://digi-tain.com/imgs/my.digitain_logo.png" alt="Digitain Logo" /></a>
    <?php

    if (isset($_SESSION['valid_user'])){
        echo '	
				<p>Logged in as '.$_SESSION['username'].'</p>
				<a class="logout" href="http://digi-tain.com/logout.php">Logout</a>';
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
                <a href="http://digi-tain.com/register.php">Register Here</a>
                <input class="submit_button" type="submit" value="Log In" />
            </form>
        </div>
        <?php
    }
?>
	</header> 
    
	<div id="global_page_elements">
    	<nav class="nav_left">
        	<ul>
            <?php
			if (@$_SESSION['access_privileges'] == 'bonus'){
				?>
                <li><a href="/share.php">Share</a></li>
                <li><a href="/help.php">Help</a></li>
                <li><a href="/in-the-works.php">Updates</a></li>
                <?php
			}
			else{
				?>           	
            	<li><a href="/authorize.php">Unlock Content</a></li>
                <?php
			}
			?>
            </ul>
        </nav>
    	<div id="global_page_content_spacer">
        	<div id="global_page_content">
