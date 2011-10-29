<?php
include('funct_lib/torrent_file_lib.php');
class display{
    function __construct(){
        $this->error = false;
        $this->error_message = "";
        $this->file_upload_name = '';
        $this->success = false;
        $this->upload = false;
        $this->unauthorized = false;
    }    
}

$display = new display();
if (isset($_SESSION['valid_user']) && @$_SESSION['access_privileges'] == 'bonus'){
    if (isset($_FILES['userfile'])){
        $display->file_upload_name = $_FILES['userfile']['name'];
        if ($_FILES['userfile']['error'] > 0){
            $display->error = true;
            $display->error_message = 'Problem: ';
            switch ($_FILES['userfile']['error']){
                case 1: $display->error_message.= 'File exceeded upload_max_filesize';
                    break;
                case 2: $display->error_message.= 'File exceeded max_file_size';
                    break;
                case 3: $display->error_message.= 'File only partially uploaded';
                    break;
                case 4: $display->error_message.= 'No file uploaded';
                    break;
                case 6: $display->error_message.= 'Cannot upload file: No temp directory specified';
                    break;
                case 7: $display->error_message.= 'Upload failed: Cannot write to disk';
                    break;                            
            }
        }
        elseif (substr($_FILES['userfile']['name'], -8) != '.torrent' || $_FILES['userfile']['type'] != 'application/x-bittorrent'){
            $display->error_message = 'Problem: File is not a torrent file.';
        }
        else {
            $torrent_file = new TorrentFile($_FILES['userfile']['tmp_name'], $db_connection);
            if ($torrent_file->is_error){
                $display->error_message = $torrent_file->error_message;
            }
            else{
                //Send the file to the database.
                if ($torrent_file->torrent_to_db($_SESSION['username'])){
                    $display->success = true;
                }
                else{
                    if ($torrent_file->is_error){
                        $display->error_message = $torrent_file->error_message;
                    }
                    else{
                        $display->error_message = 'Problem: Could not move the file to the database';
                    }
                }
            }            
        }
    }
    else{
        $display->upload = true;
    }
}
else{
    $display->unauthorized = true;
}
?>