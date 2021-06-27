<?php
// H.J.A.M. Mol 04-04-2009 23:03:33
// PHP-AJAX to support the XQL tool
$file = $_REQUEST['filename'] . '.sql';
$lines = urldecode($_REQUEST['lines']);
//non PHP4: $rc = file_put_contents($file, $lines);
$fp = fopen($file, 'w');
fwrite($fp, $lines);
fclose($fp);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
header("Cache-Control: no-cache, must-revalidate" ); 
header("Pragma: no-cache" );
header("Content-Type: text/xml; charset=utf-8");
echo 'Saved: ' , $file;
?>
