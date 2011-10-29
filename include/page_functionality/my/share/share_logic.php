<?php
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

include('tracker/bencoding.inc.php');
class torrent_file{
    private $db_connection;
    function __construct($file_path, $db_connection){
        $this->is_error = false;
        $this->file_path = $file_path;
        $this->db_connection = $db_connection;
        $raw_torrent_data = file_get_contents($this->file_path);
        $torrent_data = bdecode($raw_torrent_data);
        //If the file actually has valid torrent data.
        if ($torrent_data){
            $this->torrent_data = $torrent_data;
        }
        else{
            $this->error('Bad torrent data');
        }
    }
    function error($message){
        $this->is_error = true;
        $this->error_message = $message;
    }
    
    /*
     * Function to return any extra trackers that exist in the torrent data that aren't the 
     * digi-tain.com tracker.
     */
    function get_extra_trackers(){
        $orig_trackers = array();
        if (!stristr($this->torrent_data['announce'], 'digi-tain') && !stristr($this->torrent_data['announce'], 'digitain')){
            $orig_trackers[] = $this->torrent_data['announce'];
        }        
        if (isset($this->torrent_data['announce-list'])){
            foreach ($this->torrent_data['announce-list'] as $value){
                foreach ($value as $key=>$new_value){               
                    if (stristr($new_value, 'digi-tain') || stristr($new_value, 'digitain')){
                        unset($value[$key]);
                    }
                }
                $orig_trackers[] = $value;
            }             
        }
        return $orig_trackers;
    }
    
    /*
     * This function will edit torrent data to make it more secure for digi-tain.com to host.
     * Edits include: Removing all trackers except for digi-tain.com
     *                Modify the torrent creator to the uploader's digi-tain.com username
     *                Modify the torrent comment
     */
    function secure_torrent_file(){ 
        $torrent_data = $this->torrent_data;
        $torrent_data['announce'] = 'http://tracker.digi-tain.com:80/announce';
        unset($torrent_data['announce-list']);
        unset($torrent_data['info']['private']);
        $torrent_data['created by'] = $_SESSION['username'];
        $torrent_data['comment'] = 'Enjoy =D';
        $new_torrent_data = bencode($torrent_data);
        return $new_torrent_data;
    }
    
    /*
     * This not only transfers the data to a mysql database, but it also transfers a copy of the data
     * to a file in a directory that can be read by the digi-tain.com torrent client.
     */
    function torrent_to_db($file_name){
        $type = 'application/x-bittorrent';
        $data = $this->secure_torrent_file();
        $upfile = '/var/deluge/'.$file_name;
        $bytes = file_put_contents($upfile, $data);
        if (!$bytes){
            $this->error('Unable to transfer file');
            return false;
        }
        //Alternative to finding the file size: $size = mb_strlen($data, '8bit');
        $size = filesize($upfile);
        $name = $this->torrent_data['info']['name'];
        if (strlen($name) > 100){
            $this->error('File name too long. Max file name length is 100 chars.');
            return false;
        }
        $name_exists = $this->db_connection->query_select('digitain.torrents', array('name'=>$name), 'torrent_id', false, true);
        if ($name_exists && @count($name_exists) >= 1){
            $this->error('That file name already exists.');
            return false;
        }
        //The original trackers need to be serialized from an array.
        $extra_trackers = serialize($this->get_extra_trackers());
        return $this->db_connection->query_insert('digitain.torrents', array('name'=>$name, 
                                                                             'uploader'=>$_SESSION['username'], 
                                                                             'opt_trackers'=>$extra_trackers,                                                                              
                                                                             'file_name'=>$name.'.torrent', 
                                                                             'file_size'=>$size, 
                                                                             'file_type'=>$type, 
                                                                             'data'=>$data
                                                                            )
                                                );
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
            $torrent_file = new torrent_file($_FILES['userfile']['tmp_name'], $db_connection);
            if ($torrent_file->is_error){
                $display->error_message = $torrent_file->error_message;
            }
            else{
                //Send the file to the database.
                if ($torrent_file->torrent_to_db($_FILES['userfile']['name'])){
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