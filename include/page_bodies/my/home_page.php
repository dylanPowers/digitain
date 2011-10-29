<div class="standard_layout">
<?php
if ($display->not_logged_in){
    ?>
    <h1>You've Gots No Content! :O</h1>
    <p>
        To get proper use out of my.digi-tain.com it would be helpful to log yourself in.
        If you don't log in how else are we suppose to give you personalized content ;)    
    </p>
    <div id="opts">
        <h2>Here are your options:</h2>
        <ul>
            <li><a href="http://digi-tain.com">Go Home</a></li>
            <li><a href="http://digi-tain.com/register.php">Register</a></li>
            <li>Or log in to the form below</li>
        </ul>
    </div>
    <div id="in_page_login" class="login">
        <form action="/" method="post">
            <ul class="login_descripts">
                <li>Username</li>
                <li>Password</li>
            </ul>
            <div class="login_boxes">
                <input class="login_boxes" type="text" name="username" size="10" maxlength="50" title="Yer ID?" />
                <input class="login_boxes" type="password" name="password" size="10" maxlength="50" title="Wuz da password?" />
            </div>                      
            <input class="submit_button" type="submit" value="Log In" title="Click here =D" />
        </form>
    </div>
    <?php
}
if ($display->no_content){
    ?>
    <h1>There's no Content =O</h1>
    <p>
        I'm sorry, but it appears you haven't unlocked any content yet. If you have a Digitain
        content code, then go <a href="/authorize.php">here</a> to use it.
    </p>
    <?php
}
if ($display->show_content){
    ?>
    <div class="directory">
        <h1>Here are the files available to download</h1>
        <table id="dl_files">
            <tr>
                <th><a href="?display_order=<?php echo $display->file_ord; ?>">File Name</a></th>
                <th><a href="?display_order=<?php echo $display->date_ord; ?>">Date Uploaded</a></th>
                <th>Uploader</th>
                <th>Optional Trackers</th>
            </tr>
            <?php           
    foreach ($display->files as $value){
        ?>  <tr>
                <td><a href="?file=<?php echo $value['name'];?>"><?php echo $value['name']; ?></a></td>
                <td><?php echo $value['upload_datetime']; ?></td>
                <td><?php echo $value['uploader']; ?></td>
                <td><?php ($value['opt_trackers'] ? print '<a href="?tracker_file='.$value['name'].'" target="_blank" >Optional Trackers</a>': print '');?></td>
            </tr>
        <?php
    }
        ?>            
        </table>        
    </div>
    <?php
}
?>      
</div>
