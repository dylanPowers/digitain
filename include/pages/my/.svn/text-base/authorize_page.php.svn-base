<div class="standard_layout">
<?php
if ($display->lost){
    ?>
    <h1>You've already unlocked content =)</h1>
    <p>
        To get started simply visit the my.digi-tain home page, and all the available
        files are there for download.
    </p>
    <?php
}
if ($display->success){
    $title = 'Success =D';
    ?>
    <h1>Success =D</h1>
    <p>
        Now you may view your newly unlocked content.
    </p>    
    <?php
}
if (!$display->lost && !$display->success){
    ?>
    <h1>Unlock Extra Content Here</h1>
    <?php
    if ($display->not_logged_in){
        ?>
    <p>
        You aren't logged in=( The first step is to log in or register from the 
        box in the upper right corner of the page.
    </p>
        <?php
    }
    elseif ($display->incorrect_code){
        ?>
    <p>
        <span>I'm sorry, but that code was not correct.</span>
        Please try again.
    </p>
    <form action="/authorize.php" method="post">
        Authorization Code: <input type="text" name="authorization" maxlength="10" size="10" />
        <input type="submit" value="Enter">
    </form>
        <?php
    }
    elseif ($display->internal_error){
        ?>
    <p>
        I'm sorry, but we are experiencing technical difficulties. Please come back later.
    </p>
        <?php
    }
    else{
        ?>
    <form action="/authorize.php" method="post">
        Authorization Code: <input type="text" name="authorization" maxlength="10" size="10" />
        <input type="submit" value="Enter">
    </form>
        <?php
    }
}






?>    
    
    
    
    
</div>

