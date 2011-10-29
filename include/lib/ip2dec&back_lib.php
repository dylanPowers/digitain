<?php
/*
 * The purpose of ip2bin() and bin2ip() is to convert an ipv4 and ipv6 address to and from decimal numeric format for database storage. 
 * These functions original author had written these functions for ip to binary conversion. Unfortunately, binary is a pain to 
 * store in a MySQL database so it was rewritten to convert to an integer. It's also important to note that
 * PHP's builtin function ip2long() does not work with ipv6 addresses (hopefully that's something in the works). For now, this is the best solution. 
 * In the whole scheme of things, ipv6 support is not a must have, but I like to future proof things.
 */
function ip2dec($ip){ 
    if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) 
        return ip2long($ip); 
    if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) 
        return false; 
    if(($ip_n = inet_pton($ip)) === false) return false; 
    $bits = 15; // 16 x 8 bit = 128bit (ipv6) 
    while ($bits >= 0) 
    { 
        $bin = sprintf("%08b",(ord($ip_n[$bits]))); 
        $ipbin = $bin.$ipbin; 
        $bits--; 
    } 
    $ipdec = bindec($ipbin);
    return $ipdec; 
}
function dec2ip($dec){ 
   $bin = decbin($dec);
   if(strlen($bin) <= 32) // 32bits (ipv4) 
       return long2ip(base_convert($bin,2,10)); 
   if(strlen($bin) != 128) 
       return false; 
   $pad = 128 - strlen($bin); 
   for ($i = 1; $i <= $pad; $i++) 
   { 
       $bin = "0".$bin; 
   } 
   $bits = 0; 
   while ($bits <= 7) 
   { 
       $bin_part = substr($bin,($bits*16),16); 
       $ipv6 .= dechex(bindec($bin_part)).":"; 
       $bits++; 
   } 
   return inet_ntop(inet_pton(substr($ipv6,0,-1))); 
} ?>
