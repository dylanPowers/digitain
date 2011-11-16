<?php 
class Display{
   
    function __construct($user_perms){
        $this->user_perms = $user_perms;
    }
    function set_el($element_name, $perms){
        
    }
    function is_el_avail($element_name){
        if ($user_perms >= $this->$element_name){
            
        }
    }
    function create_error_block($num_of_blocks){
        
    }
    function set_error(){
        
    }
    function display_errors($block_num){
        
    }
    function calc_perms($perm, $x = 0){
        if (pow(2, $x) <= $perm){
            ++$x;
            $this->calc_perms($perm, $x);
        }
        else{
            --$x;
            $perm - $x;
        }
    }
}

?>
