<?php
class display{
    function __construct(){
        $this->error_message = '';
        
    }
}

function dir_scan_for_vids($scan_path, $depth = 1){
    global $db_connection;
    $dirs = scandir($scan_path);
    foreach ($dirs as $value){
        if (is_dir($scan_path.$value) && $depth > 0){
            dir_scan_for_vids($scan_path.$value, $depth-1);
        }
        $ext = pathinfo($scan_path.$value, 'PATHINFO_EXTENSION');
        if ($ext == 'avi' || $ext == 'mkv' || $ext == 'mp4' || $ext == 'm4v'){
            $tmp_path = '/tmp/temp_torrent.torrent';
            $result = `-o $tmp_path $scan_path.$value`;
            if (stristr($result, '"success"')){
                $torrent = new TorrentFile($tmp_path, $db_connection, $scan_path.$value);
                $torrent->torrent_to_db('=D');
                unset($torrent);
            }
            else{
                $this->error_message .= $result;
            }
            unlink($tmp_path);
        }
    }
}
function regen_tor_client(){
    global $db_connection;
    $dot_torrent_files = $db_connection->query_select('digitain.torrents', $match = false, '*', false, true);
    foreach ($dot_torrent_files as $dot_torrent_file){
        $tmp_filename = '/tmp/temp_tor.torrent';
        $dot_torrent_file['data_locat'];
        file_put_contents('/tmp/temp_tor.torrent', $dot_torrent_file['data']);
        $torrent = new TorrentFile($tmp_filename, $db_connection, $dot_torrent_file['data_locat']);
        
        
    }
}
if ($_SESSION['username'] == 'Pawessum16'){
    include('funct_lib/torrent_file_lib.php');
    @ $scan_path = $_POST['scan_path'];
    @ $imp_db_to_transbt = $_POST['imp'];
    if ($scan_path){
        dir_scan_for_vids($scan_path);
    }
    if ($imp_db_to_transbt){
        
    }
}

//If they're not an admin, then lets simply reroute them to the homepage.
else{
    header('Location: http://digi-tain.com');
}
?>
