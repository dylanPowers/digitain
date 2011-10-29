<?php 
include_once('tracker/bencoding.inc.php');
class TorrentFile{
    private $db_connection;
    function __construct($file_path, $db_connection, $existing_data = False){
        $this->is_error = false;
        $this->existing_data = $existing_data;
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
    function secure_torrent_file($username){ 
        $torrent_data = $this->torrent_data;
        $torrent_data['announce'] = 'http://tracker.digi-tain.com:80/announce';
        unset($torrent_data['announce-list']);
        unset($torrent_data['info']['private']);
        $torrent_data['created by'] = $username;
        $torrent_data['comment'] = 'Enjoy =D';
        $new_torrent_data = bencode($torrent_data);
        return $new_torrent_data;
    }
    
    /*
     * This not only transfers the data to a mysql database, but it also transfers a copy of the data
     * to the transmission bittorrent client that exists on the server.
     */
    function torrent_to_db($uploader){
        $type = 'application/x-bittorrent';
        $data = $this->secure_torrent_file($uploader);
        if (!torrent_to_transbt($data)){
            return false;
        }
        $size = mb_strlen($data, '8bit');
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
                                                                             'uploader'=>$uploader, 
                                                                             'opt_trackers'=>$extra_trackers,                                                                              
                                                                             'file_name'=>$name.'.torrent', 
                                                                             'file_size'=>$size, 
                                                                             'file_type'=>$type, 
                                                                             'data'=>$data
                                                                            )
                                                );
    }
    
    /*
     * This function was written for use on a unix system, primarily Ubuntu. The purpose is to
     * add a torrent file into the transmission bittorrent client. Transmission has a very
     * handy command line utility that makes it easy for manipulating from a PHP script.
     */
    private function torrent_to_transbt($data){
        $tmp_path = '/tmp/temp_transmission_torrent';
        $bytes = file_put_contents($tmp_path, $data);
        if (!$bytes){
            $this->error('Unable to transfer file to temporary directory.');
            return false;
        }        
        if ($this->existing_data){
            /*
             * If a info hash needed to be defined, this would be it.
                $info = $this->torrent_data['info'];
                $encoded_info = bencode($info);
                $info_hash = sha1($encoded_info);
             */
            $result = `transmission-remote --auth transmission:transmission -a $tmp_path --find $existing_data_dir -v`;
        }
        else{
            $result = `transmission-remote --auth transmission:transmission -a $tmp_path`;
        }
        unlink($tmp_path);
        if (stristr($result, '"success"')){
            return true;
        }
        else{
            error($result);
            return false;
        }
    }
}
?>
