<?php
//Define the class display to store the page display variables.
class display{
    function __construct(){
        $this->not_logged_in = false;
        $this->show_content = false;
        $this->files = array();
        $this->no_content = false;
    }
}
$display = new display();

//If isset $_GET['var'] then $var = .... else $var = default
(isset($_GET['file']) ? $file = $_GET['file'] : $file = false);
(isset($_GET['tracker_file']) ? $tracker_file = $_GET['tracker_file'] : $tracker_file = false);
(isset($_GET['display_order']) ? $display_order = $_GET['display_order'] : $display_order = 'date_desc');

if (isset($_SESSION['valid_user'])){
    if ($_SESSION['access_privileges'] == 'bonus'){
        if ($file){
            $content = $db_connection->query_select('digitain.torrents', array('name'=>$file), 'file_name, file_size, file_type, data', false, true);
            if ($content){
                header("Content-length: ".$content[0]['file_size']);
                header("Content-type: ".$content[0]['file_type']);
                header("Content-Disposition: attachment; filename=".$content[0]['file_name']);
                echo $content[0]['data'];
                exit;
            }
            
        }
        elseif ($tracker_file){
            $content = $db_connection->query_select('digitain.torrents', array('name'=>$tracker_file), 'opt_trackers', false, true);
            if ($content){
                $orig_trackers = unserialize($content[0]['opt_trackers']);
                $tracker_str = '';
                foreach($orig_trackers as $value){
                    if (is_array($value)){
                        foreach($value as $new_value){
                            $tracker_str .= $new_value."\r\n";
                        }
                    }
                    else{
                        $tracker_str .= $value."\r\n";
                    }
                    $tracker_str .= "\r\n";
                }
                header("Content-length: ".mb_strlen($tracker_str, '8bit'));
                header("Content-type: text/plain");
                //header("Content-Disposition: attachment; filename=".$tracker_file."-optional-trackers.txt");
                header("Content-Disposition: inline; filename=".$tracker_file."-optional-trackers.txt");
                echo $tracker_str;
                exit;
            }
        }
        $display->file_ord = 'name_asc';
        $display->date_ord = 'date_desc';
        $display->show_content = true;
        $select = 'name, category, uploader, upload_datetime, opt_trackers';
        switch ($display_order){
            case 'date_desc':
                $display->files = $db_connection->query_select('digitain.torrents', false, $select, 'torrent_id desc', true);
                $display->date_ord = 'date_asc';
                break;
            case 'date_asc':
                $display->files = $db_connection->query_select('digitain.torrents', false, $select, 'torrent_id asc', true);
                break;
            case 'name_asc':
                $display->files = $db_connection->query_select('digitain.torrents', false, $select, 'name asc', true);
                $display->file_ord = 'name_desc';
                break;
            case 'name_desc':
                $display->files = $db_connection->query_select('digitain.torrents', false, $select, 'name desc', true);
                break;
            default:
                $display->files = $db_connection->query_select('digitain.torrents', false, $select, 'torrent_id desc', true);
                $display->date_ord = 'date_asc';
                break;
        }
        
        //Some last value manipulation and cleanup before output to the browser is done.
        foreach ($display->files as $key=>$value){
            $upload_date = date_parse($value['upload_datetime']);
            $display->files[$key]['upload_datetime'] = $upload_date['year'].'-'.$upload_date['month'].'-'.$upload_date['day'];
            $display->files[$key]['opt_trackers'] = unserialize($display->files[$key]['opt_trackers']);            
        }
    }
    else{
        $display->no_content = true;
    }    
}
else{
    $display->not_logged_in = true;
}
?>