<?php
// H.J.A.M. Mol 16-08-2009 13:54:32
// PHP MySQL Web Client
// www.hermanmol.nl 16-08-2009 13:54:37
// requires files: XQL.JS, XQL.CSS, XQL_READFILE.PHP, XQL_SAVEFILE.PHP
setlocale(LC_ALL, 'nl_NL');
$dbok = FALSE;
$csv = isset($_REQUEST['cbx_exp']);
if ($csv)
{
	if (isset($_REQUEST["sqlfiles"]) and $_REQUEST["sqlfiles"]!="")
	{
		$fname = strtok($_REQUEST["sqlfiles"], ".") . "_" . date('YmdHi') . ".csv";
	}
	else
	{
		$fname = "XQL_EXPORT_" . date('YmdHi') . ".csv";
	}
	$tmpfname = dirname($_SERVER["SCRIPT_FILENAME"]) . "/$fname";
	$fs_def=';';
	$rs="\n";
	$expdata = date('d-m-Y H:i') . $rs;
	$fs='';
}
$sqllimit = (!isset($_POST['limit']) or $_POST['limit'] == '') ? 100 : $_POST['limit'];
// ====================================================================================================================
if ($_POST['DBPWD'] != '' && $_POST['DBSERVER'] != '' && $_POST['DBUSER'] != '' && $_POST['DBNAME'] != '')
{
	// connect to db
	$link = @mysql_connect($_POST['DBSERVER'], $_POST['DBUSER'], $_POST['DBPWD']);
	if (!$link) 
	{
		echo 'ERROR: unable to connect to ' , $_POST['DBSERVER'] , ' as user ' , $_POST['DBUSER'];
		echo mysql_error();
		exit;
	}
	else 
	{
		if (!mysql_select_db($_POST['DBNAME'], $link)) 
		{
	    echo 'Error selecting database ' , $_POST['DBNAME'] , ' on server ' , $_POST['DBSERVER'] , ' as user ' , $_POST['DBUSER'];
			echo mysql_error();
			exit;
		}
		else
		$dbok = TRUE;
	}
}
$wtitle = (array_key_exists('wtitle',$_POST)) ? $_POST['wtitle'] : 'XQL by H.J.A.M. Mol';
// ====================================================================================================================
// case insensitive sort of an array (with filenames)
function insenssort($a, $b)
{
   if (strtoupper($a) == strtoupper($b)) {
       return 0;
   }
   return (strtoupper($a) < strtoupper($b)) ? -1 : 1;
}
// ====================================================================================================================
// BEGIN VAN DE PAGINA
// ====================================================================================================================
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
	<title><?php echo $wtitle;?></title>
	<link rel="stylesheet" type="text/css" href="xql.css">
	<script type="text/javascript" language="JavaScript" src="xql.js"></script>
</head>
<!-- ============================================================================================================== -->
<body onload='window.f1.sql.focus();'>
<table style='background: #D4D0C8; border: 0;'>
	<tr>
	<td>
		<div style='width: 700px; height: 200px; overflow: hide;'>
			<form id="f1" name="f1" action="<?php echo $PHP_SELF;?>" method="post">
				<div style='background: #D4D0C8'>
				  <input type='hidden' id='showtable' name='showtable' value='?'>
				  <input type='hidden' id='wtitle'    name='wtitle'    value='<?php echo $wtitle;?>'>
				  <!-- ==================================================================================================== -->
				  <input type="submit" id='connect' name="connect" value="Connect" tabindex='100'>
				  &nbsp;Server:&nbsp;   <input type="text"     name="DBSERVER" id="DDBSERVER" size="10"	maxlength="80" value ="<?php echo $_POST['DBSERVER'] ?>" tabindex='101'>
				  &nbsp;User:&nbsp;     <input type="text"     name="DBUSER"   id="DBUSER"    size="10"	maxlength="80" value ="<?php echo $_POST['DBUSER']   ?>" tabindex='102'>
				  &nbsp;DB:&nbsp;       <input type="text"     name="DBNAME"   id="DBNAME"    size="10"	maxlength="80" value ="<?php echo $_POST['DBNAME']   ?>" tabindex='103'>
				  &nbsp;Password:&nbsp; <input type="password" name="DBPWD"    id="DBPWD"     size="10"	maxlength="80" value ="<?php echo $_POST['DBPWD']    ?>" tabindex='104'>
				  &nbsp;Title:&nbsp;    <input type="text"     name="wtitle"   id="wtitle"    size="15"	maxlength="80" value ="<?php echo $wtitle   ?>" tabindex='105'>
				  <br> 
				  <input type="submit" name="execute" value="Execute" tabindex='2' onclick='showit();form.submit();return false;'>
				  &nbsp;Limit: <input type="text" name="limit" id="limit" size="5" maxlength="6" value ="<?php echo $sqllimit ?>" tabindex='3' title='Default 100 rows; 0 means get all rows.'>
				  &nbsp;CSV <input type="checkbox" id='cbx_exp' name="cbx_exp" value="cbx_exp" tabindex='4' <?php ECHO ($csv ? "CHECKED" : "" ); ?>>
				  <?php
				  	if ($csv)
				  	{
				  		echo "<a href='" , dirname($_SERVER['SCRIPT_NAME']) , '/' , $fname , "' tabindex='9' title='".$fname."' target='_blank'>download</a>";
				  	}
				  ?>
				  &nbsp;
				  <select id='sqlfiles' name='sqlfiles' onchange="readfile(this.options[this.selectedIndex].value);" tabindex='10'>
				  	<option id='file0' value='' ></option>
					  <?php 
					  	//-------------------------------------------------------------------------------------------------------------
					  	// Get all *.sql files from the folder in a case insensitive sorted array
					    $dh = opendir('./');
							while (false !== ($file = readdir($dh)))
							{
								if (substr($file, -4)=='.sql')
								{
									$files[] = $file;
								}
							}
							usort($files, 'insenssort');
							// put all *.sql files in an option-list
					  	$cnt = 1;
							foreach ($files as $file)
							{
								$rc = "<option id='file" . $cnt . "' value='" . $file . "'" . ($_REQUEST["sqlfiles"]==$file?"SELECTED":""). " title='choice ". $cnt ." '>" . $file . "</option>" . CRLF;
								echo $rc;
								$cnt =$cnt + 1;
							}
					  	//-------------------------------------------------------------------------------------------------------------
					  ?>
		  		</select>
				  &nbsp;<input type="button" onclick="savefile()" value="Save" tabindex='11' />
				</div>
				<textarea cols='150' rows='20' wrap='soft'  
					id='sql' name='sql' tabindex='1' 
					title='SQL statement' accesskey='Q'
					onkeyup='if (event.keyCode == 120) {form.submit();return false;};'
					><?php echo get_magic_quotes_gpc() ? stripslashes($_POST['sql']) : $_POST['sql']; ?></textarea>
			</form>
		</div>
	</td>
	<td style='vertical-align: top;'>
		<div id='tblidx' style='width: 215px; height: 270px; overflow: auto;'>
			<table>
				<tr><th>Tables</th></tr>
				<?php
				if ($dbok)
				{
					$rs_sqlcmd = mysql_query('show tables;');
					$cols = mysql_num_fields($rs_sqlcmd);
					while ($row = mysql_fetch_array($rs_sqlcmd, MYSQL_NUM)) 
					{
						echo "<tr>";
						for ($col=0; $col < $cols ; $col++ ) 
						{
		      		echo "<td><a href='xql.php' onclick='document.getElementById(\"showtable\").value = \"data." . $row[$col]. "\";f1.submit();return false;' >data</a>"
		      			, "&nbsp;<a href='xql.php' onclick='document.getElementById(\"showtable\").value = \"ddl." . $row[$col]. "\";f1.submit();return false;' >" , $row[$col] , "</a>"
		      			, "</td>";
				  	}
				  	echo "</tr>";
			  	}
				}
				?>
			</table>
		</div>
	</td>
</tr>
</table>
<!-- =============================================================================================================== -->
<div style='height: 60%; overflow: auto; background-color: white; width: 100%;'>
	<?php
	// ==================================================================================================================
	// write the output
	// ==================================================================================================================
	if ($dbok)
	{
		if ($_REQUEST['showtable'] != '?')
		{
			$rq = explode ('.', $_REQUEST['showtable']);
			if ($rq[0] == 'data') 
			{
				$sqlcmd1 = 'select * from ' . $rq[1];
				if ($sqllimit != 0)
				{
					$sqlcmd1 .= ' limit ' . $sqllimit;
				}				
				$rs_sqlcmd = mysql_query($sqlcmd1 . ';');
			}
			if ($rq[0] == 'ddl')
			{
				$rs_sqlcmd = mysql_query('show columns from ' . $rq[1] . ';');
			}
			$cols = mysql_num_fields($rs_sqlcmd);
			$display_result = "<thead><tr>";
			for ($col=0; $col < $cols ; $col++ ) 
			{
	  		$display_result .= "<th>" . mysql_field_name($rs_sqlcmd, $col) . "</th>";
	  	}
	  	$display_result .= "</tr></thead>";
	  	$lnct = 0;
	  	$display_result .= "<TBODY>";
			while ($row = mysql_fetch_array($rs_sqlcmd, MYSQL_NUM)) 
			{
				$display_result .= "<tr class='tr" . ($lcnt % 2) . "'>";
				for ($col=0; $col < $cols ; $col++ ) 
				{
	    		$display_result .= "<td" . (is_null($row[$col]) ? " class='null' " : " class='showdata' " )  . ">" . (is_null($row[$col]) ? "(null)" : htmlspecialchars($row[$col]) ) . "</td>";
		  	}
		  	$display_result .= "</tr>";
		  	$lcnt ++;
	  	}
		  $display_result .= "</TBODY>";
		}
		else
		{
			if ($_POST['sql'])
			{
				$sqlcmd = get_magic_quotes_gpc() ? stripslashes($_POST['sql']) : $_POST['sql'] ;
				if ($sqllimit != 0)
				{
					$sqlcmd .= ' limit ' . $sqllimit;
				}
				$rs_sqlcmd = mysql_query($sqlcmd) or die('Error or running query: ' . mysql_error());
				if (strtoupper(trim(substr($sqlcmd,0,6)))=='SELECT' || strtoupper(trim(substr($sqlcmd,0,4)))=='SHOW')
				{
					$rows = mysql_num_rows($rs_sqlcmd);
					if ($rows > 0)
					{
						$cols = mysql_num_fields($rs_sqlcmd);
						$display_result = "<tr>";
						for ($col=0; $col < $cols ; $col++ ) 
						{
							$display_result .= "<th>" . mysql_field_name($rs_sqlcmd, $col) . "</th>";
							if ($csv)
							{
								$expdata .= $fs . '"' . mysql_field_name($rs_sqlcmd, $col) . '"';
								$fs = $fs_def;
							}
						}
						$display_result .= "</tr>";
						if ($csv)
						{
							$expdata .= $rs;
						}
						$lcnt = 0;
						while ($row = mysql_fetch_array($rs_sqlcmd, MYSQL_NUM)) 
						{
							$display_result .= "<tr class='tr" . ($lcnt % 2) . "'>";
							if ($csv)
							{
								$fs='';
							}
							for ($col=0; $col < $cols ; $col++ ) 
							{
						 		$display_result .= "<td" . (is_null($row[$col]) ? " class='null' " : " " )  . ">" . (is_null($row[$col]) ? "(null)" : htmlspecialchars($row[$col]) ) . "</td>";
								if ($csv)
								{
									$expdata .= $fs . '"' . $row[$col] . '"';
									$fs = $fs_def;
								}
					 		}
						 	$display_result .= "</tr>";
							if ($csv)
							{
								$expdata .= $rs;
							}
							$lcnt ++;
						}
					}
				}
				else
				{
					$rows = mysql_affected_rows($link);
					$display_result = "<tr><td><br>&nbsp;&nbsp;$rows rows affected&nbsp;&nbsp;<br>&nbsp;</td></tr>";
				}
			}
		}
	}
	echo "<table border='1' class='tab00'>";
	echo $display_result;
 	echo "</table>";
	if ($csv)
	{
		$handle = fopen($tmpfname, "w");
		fwrite($handle, $expdata);
		fclose($handle);
	}
	?>
</div>
</body>
</html>
<?php
?>