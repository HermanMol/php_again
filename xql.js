// H.J.A.M. Mol 04-04-2009 23:03:44
// JavaScript to support the XQL tool
var xmlHttp;
// ----------------------------------------------------------------------------
// function to instantiate the xmlHttp-object
function GetXmlHttpObject()
{ 
	var objXMLHttp = null;
	if (window.XMLHttpRequest)
	{
		objXMLHttp=new XMLHttpRequest();
	}
		else if (window.ActiveXObject)
	{
		objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	return objXMLHttp;
}
// ----------------------------------------------------------------------------
// read a file
function readfile(p_data)
{
	if (p_data != "")
	{
		xmlHttp = GetXmlHttpObject();
		if (xmlHttp==null)
		{
		  alert ("Functionality (ajax) appears not to be supported by the browser; cannot read file '" + p_data + "'.");
			return 99;
		} 
		var myurl = "xql_readfile.php?p_data=" + p_data;
		xmlHttp.onreadystatechange = readfile_callback;
		xmlHttp.open ( "POST", myurl, false );	// fire and forget
		xmlHttp.send(null);
		window.f1.sql.focus();
	}
}
function readfile_callback()
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{
		//alert ("filedata08-11-2008 22:56:47");
		filedata=xmlHttp.responseText;
		if (filedata != "")
		{
			if (document.getElementById("sql").value == "")
			{
				document.getElementById("sql").value = filedata;
			}
			else
			{
				if (confirm("Overwrite SQL code in current window"))
				{
					document.getElementById("sql").value = filedata;
				}
			}
		}
		else
		{
			alert ("SQL-file not found or empty.");
		}
	}
	window.f1.sql.focus();
}
// ----------------------------------------------------------------------------
// save data to a file
function savefile()
{
	var selfil = document.getElementById('sqlfiles');
	var filsel = selfil.selectedIndex;
	var fname = selfil.options[filsel].value;
	if (fname == "")
	{
		fname = "SomeQuery";
	}
	else
	{
		fparts =  fname.split(".");
		fname = fparts[0];
	}
	fname = prompt("Enter filename for this SQL (without SQL extension)" , fname );
	if ( fname != null && fname != "")
	{
		xmlHttp = GetXmlHttpObject();
		if (xmlHttp==null)
		{
		  alert ("Functionality (ajax) appears not to be supported by the browser; cannot save file '" + fname + "'.");
			return 99;
		} 
		var myurl = "xql_savefile.php?filename=" + fname + "&lines=" + encodeURI(document.getElementById("sql").value);
		xmlHttp.onreadystatechange = savefile_callback;
		xmlHttp.open ( "POST", myurl, false );	// fire and forget
		xmlHttp.send(null);
	}
}
function savefile_callback()
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{
		filedata=xmlHttp.responseText;
		if (filedata != "")
		{
			alert (filedata);
		}
	}
}
// ----------------------------------------------------------------------------
function show_popup()
{
	// show some popup text... to be worked out later
	var p=window.createPopup()
	var pbody=p.document.body
	pbody.style.backgroundColor="lime"
	pbody.style.border="solid black 1px"
	pbody.innerHTML="This is a pop-up! Click outside to close."
	p.show(150,150,200,50,document.body)
}
function showit()
{
	try
	{
		// get selected text e.g. the table name
		// thanks to the-stickman.com 24-12-2007 09:51:52
		var txt = '';
		var element = document.getElementById( 'sql' ); 
		if( document.selection )
		{ // The current selection 
			var range = document.selection.createRange(); 
			// We'll use this as a 'dummy' 
			var stored_range = range.duplicate(); 
			// Select all text 
			stored_range.moveToElementText( element ); 
			// Now move 'dummy' end point to end point of original range 
			stored_range.setEndPoint( 'EndToEnd', range ); 
			// Now we can calculate start and end points 
			element.selectionStart = stored_range.text.length - range.text.length; 
			element.selectionEnd = element.selectionStart + range.text.length; 
			txt=(element.value).substring(element.selectionStart,element.selectionEnd);  
			document.getElementById( 'sql2' ).value=txt;
			//alert(document.getElementById( 'sql2' ).value);
		}		
	}
	catch (err)
	{
	}
}
function form_submit(p_form, p_object, p_value)
{
	p_form.submit()
}
function setfocus()
{
}
function losefocus()
{
}
