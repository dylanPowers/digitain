<?php 
/*
 * @creation	11/16/11
 * @author		Dylan Powers
 * @description A library that holds the Display object class.
 */

/*
 * @description	An object class that primarily holds variables necessary
 *		to provide dynamic content to the end user.
 */
class Display{
   
	private $vars = array();
	
//    function __construct($user_perms){
//        $this->user_perms = $user_perms;
//    }
	
	public function __set($name, $value) {
		$this->vars[$name] = $value;
	}
	
	public function __get($name) {
		if (isset($this->vars[$name])){
			return $this->vars[$name];
		}		
	}
	
//    function set_el($element_name, $perms){
//        
//    }
//    function is_el_avail($element_name){
//        if ($user_perms >= $this->$element_name){
//            
//        }
//    }
//    function create_error_block($num_of_blocks){
//        
//    }
//    function set_error(){
//        
//    }
//    function display_errors($block_num){
//        
//    }
//    function calc_perms($perm, $x = 0){
//        if (pow(2, $x) <= $perm){
//            ++$x;
//            $this->calc_perms($perm, $x);
//        }
//        else{
//            --$x;
//            $perm - $x;
//        }
//    }
}

?>
