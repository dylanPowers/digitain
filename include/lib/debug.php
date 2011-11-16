<?php 
class Debug{
    private $debug_output;
    
    function __construct(){
        $this->debug_output = '<!--';
    }
    function __set($var_name, $value){
        if ($var_name == 'debug_output'){
            $this->debug_output .= $value;
        }
    }
    
    /* Get's the contents of a variable.
     * Input: A variable name.
     * Output: None.
     */
    function var_analyze($var, $line){
        ob_start();     
        var_dump($var);
        $string = ob_get_contents();
        ob_end_clean();
        $this->debug_output .= $string . ' @ Line ' . $line;
    }
    
    /*
     * Description: Takes in a function name and its arguments and gives
     *      its output. If only there was a better way of debugging PHP
     *      programs :/
     * Input: $function_name is simply the name of the function to analyze.
     *        $arguments will consist of a keyless array.
     *        
     */
    function function_analyze($function_name, $arguments){
        for ($i = 0, $argument_string = '', $array_length = count($arguments); $i < $array_length; ++$i){
            if ($i == 0){
                $argument_string .= $arguments[$i];
            }
            else{
                $argument_string .= ', ' . $arguments[$i];
            }
        }
        $function_call = $function_name . '(' . $argument_string . ');';
        $this->debug_output .= $function_call;
        $this->debug_output .= '\nReturn Value: ' . eval($function_call);
        
    }
}

?>
