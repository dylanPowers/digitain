<div class="standard_layout">
    <h1 class="registration">Digitain Registration</h1>
<?php 
if ($display->missing_input){
    ?>
    <h2>Your missing a required input!</h2>
    <?php    
}

if ($display->passwords_no_match){
    ?>
    <h2>Your passwords don't match!</h2>
    <?php    
}

if ($display->user_exists){
    ?>
    <p class="error">I'm sorry =( that username already exists in our systems.</p>
    <?php
}

if ($display->insert_error){
    ?>
    <p>I'm sorry for the inconvenience, but we are experiencing an internal error.</p>
    <?php   
}

if (!$display->successful){
    ?>    
    <div id="registration">
        <form action="/register.php" method="post">
            <ul class="form_descripts">
                <li>Username</li>
                <li>Password</li>
                <li>Verify Password</li>
            </ul>
            <div class="seperator">
            </div>
            <div class="form_boxes">
                <input type="text" name="reg_username" maxlength="49" size="15" />
                <input type="password" name="reg_password" maxlength="49" size="15"/>
                <input type="password" name="reg_password_confirm" maxlength="49" size="15"/>
            </div>
            <input class="submit" type="submit" value="Register" />           
        </form>
    </div>    
    <?php
}
else{
    ?>
    <h2>Welcome <?php $display->username ?> to Digitain as our newest member!!!=D</h2>
    <p>You can now feel free to log in under your newly created account.</p>
    <?php
}
?>

