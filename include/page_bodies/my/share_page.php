<div class="standard_layout">
    <h1>Share</h1>
    <?php
    if ($display->unauthorized){
        ?>
    <p>To make use of this part of the site you must unlock the bonus content first.</p>
        <?php 
    }
    elseif ($display->success){
        ?>
    <p>
        Yay! You successfully uploaded <?php echo $display->file_upload_name; ?> to share with everyone else.
        Depending on if you have http://tracker.digi-tain.com:80 as one of your trackers, my server will automatically begin the p2p
        transfer soon. 
    </p>
    <p>
        Click <a href="/share.php">Here</a> to upload another.
    </p>
        <?php
    }
    elseif ($display->upload){
        ?>
    <h2>Upload Your Files Here</h2>
    <form action="/share.php" method="post" enctype="multipart/form-data">
        <div>
            <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
            <label for="userfile">Upload your .torrent file:</label>
            <input id="userfile" type="file" name="userfile" />
            <input type="submit" value="Upload" />
        </div>
    </form>
        <?php        
    }
    else{
        ?>
    <p>
        An error occurred =O. <?php echo $display->error_message; ?>
    </p>
    <h2>Upload Your Files Here</h2>
    <form action="/share.php" method="post" enctype="multipart/form-data">
        <div>
            <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
            <label for="userfile">Upload your .torrent file:</label>
            <input id="userfile" type="file" name="userfile" />
            <input type="submit" value="Upload" />
        </div>
    </form>
        <?php    
    }
    ?>   
</div>
