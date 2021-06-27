<?php
$gesloten = true;
if ( $_SERVER["REMOTE_ADDR"] === '2001:1c03:20:c400:a8b0:9884:dd80:8c03' )
{
	$gesloten = false;
}
if ($gesloten)
{
	echo "<h1>Sorry, we're CLOSED!</h1>";
	//header("Location: http://" . $_SERVER["REMOTE_ADDR"]); /* Redirect browser */
	exit;
}
?>