<?php
// H.J.A.M. Mol 04-04-2009 23:03:28
// PHP-AJAX to support the XQL tool
$file = $_REQUEST['p_data'];
$lines = file_get_contents($file);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
header("Cache-Control: no-cache, must-revalidate" ); 
header("Pragma: no-cache" );
header("Content-Type: text/xml; charset=utf-8");
echo get_magic_quotes_gpc() ? stripslashes($lines) : $lines;
?>
