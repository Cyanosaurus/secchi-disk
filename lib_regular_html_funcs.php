<?php	//REGULAR HTML FUNCTIONS, LIKE CAPTION TABLES

/* File Index

-- Secchi Specific
(void) regular_block($stuff)
(void) table1_start($title)
(void) table1_header($array_headers)
(void) table1_row($array_row, $rowcount)
(void) caption_white($title,$desc,$content)
(void) caption_black($title,$desc,$content)
(void) delimiter_start()
(void) delimit()
(void) delimiter_end()

-- Generic
(void) Alert($title,$text)
(string) newlines($val)
(string) return_link();
(null) page_controls($id,$numPerPage, $numA, $extras)
(null) page_action($id)
(string) get_page($id)

(string) form_hidden($name,$value)
(string) form_select($name, $listnames, $listvalues, $selected_value)
(string) form_submit($title)
(string) form_submit_name($title,$name)

*/

include_once("lib_functions.php");

//A simple highlighted block
function regular_block ($stuff)
{

	echo "\n\n<!-- ================================== REGULAR BLOCK =============================== -->\n\n
		<table class='data'>
		<tr><td align='left'>$stuff</td></tr>
		</table>
	";
}


//Table functions ------------------------


//Starts Table(variation1)
function table1_start($title)
{
	echo "\n\n<!-- ================================== TABLE VARIETY 1 =============================== -->
<table height='100%' width='100%'>
<table width='100%' height='0' border='0' cellpadding='0' cellspacing='0' margin='3'>
<tr><td class='data'>
	<!-- Title here -->
	$title
</td></tr>
<tr><td>
<table width='100%' height='100%' border='0' cellpadding='4' cellspacing='0' class='data'>
";
}

//Creates the table header, given column headers and widths in example format:
//table1_header(Array("Name/0","Age/0","Description/100%"));
//.. Name and Age will be narrow as possible, and Description would take up remaining space
function table1_header($array_headers)
{
	//Process the incoming data
	$widths = Array();
	$headers = Array();
	foreach ($array_headers as $value)
	{
		preg_match("/(.*)\<{0}\/(\d+\%{0,1})/",$value,$temp);
		$headers[] = $temp[1];
		$widths[] = $temp[2];
	}

	//Create the column group
	echo "<colgroup span='" . count($widths) . "'>\n";
	foreach ( $widths as $value )
	{
		echo " <col width='" . $value . "' >\n";
	}
	echo "</colgroup>\n";


	//Create column headers
	$count = 0;
	echo "<tr class='data'>";
	foreach ( $headers as $value )
	{
		if ( $count % 2 == 0 ) { echo "<td class='data_bold'>"; } else { echo "<td>"; }
		echo $value;
		echo "</td>";	
		$count++;
	}
	echo "</tr>\n";
}


//Creates the row data, given content array, and row count (establishes even/odd row color). Example:
//table1_row(Array("Bob","17","He's short."),4);
function table1_row($array_row, $rowcount)
{

	//Create column headers
	$count = 0;
	if ( $rowcount % 2 == 1 ) { echo "<tr class='data_altrow'>"; } else { echo "<tr>"; }
	foreach ( $array_row as $value )
	{
		if ( $count % 2 == 0 ) { echo "<td class='data_bold'>"; } else { echo "<td>"; }
		echo $value;
		echo "</td>";
		$count++;
	}
	echo "</tr>\n";
}


//Caps off table1
function table1_end()
{
	echo "</table>
		</td></tr>
	</table>";
	echo "\n<!-- ================================== END TABLE VARIETY 1 =============================== -->\n\n";
}

//Caps off table1
function table1_end_total($amt)
{
	echo "</table>
		</td></tr>
		<tr class='data'><td align='left'>
			<i>$amt results.</i>
		</td></tr>
	</table>\n<!-- ================================== END TABLE VARIETY 1 =============================== -->\n\n";
}


// Captions ------------------------------


//Given caption title, footer($desc), and content, will make a white-background caption box
function caption_white($title,$desc,$content)
{
	echo "\n\n<!-- ================================== WHITE CAPTION =============================== -->\n\n
<table cellspacing='0' valign='top' cellpadding='0' border='0' width='350' height='200' margin='3' align='right' class='caption'>
	<tr height='0'>
		<td width='0'><img src='interface/caption_top_left.gif'  alt='Border Image'/></td>
		<td width='100%' background='interface/caption_top_mid2.gif' align='center'>
			<table cellspacing='0' cellpadding='0' border='0' width='0' height='0'>
				<tr>
					<td width='0'><img src='interface/caption_top_mid1_left.gif'  alt='Border Image'/></td>
					<td width='100%' background='interface/caption_top_mid1_mid.gif' align='center' class='caption_title' valign='top'>
						<!-- Caption Title here --> $title <!-- End Caption Title -->
					</td>
					<td width='0'><img src='interface/caption_top_mid1_right.gif'  alt='Border Image'/></td>
				</tr>
			</table>
		</td>
		<td width='0'><img src='interface/caption_top_right.gif'  alt='Border Image'/></td>
	</tr>
	<tr height='100%'>
		<td width='0' background='interface/caption_mid_left.gif'><img src='interface/caption_mid_left.gif'  alt='Border Image'/></td>
		<td width='100%' bgcolor='white' align='center'>
			<!-- Caption Content goes here -->
				$content									
			<!-- End Caption Content -->
		</td>
		<td width='0' background='interface/caption_mid_right.gif'><img src='interface/caption_mid_right.gif'  alt='Border Image'/></td>
	</tr>
	<tr height='0'>
		<td width='0'><img src='interface/caption_bot_left.gif' /></td>
		<td width='100%' bgcolor='white' align='right' valign='middle' background='interface/caption_bot_mid.gif'>
			<!-- Other Content goes here -->
				$desc
			<!-- End Other Caption Content -->
		</td>
		<td width='0'><img src='interface/caption_bot_right.gif'  alt='Border Image'/></td>
	</tr>
</table>
";

}


//Given caption title, footer($desc), and content, will make a white-background caption box
function caption_black($title,$desc,$content)
{
	echo "\n\n<!-- ================================== BLACK CAPTION =============================== -->\n\n
<table cellspacing='0' valign='top' cellpadding='0' border='0' width='350' height='200' margin='3' align='center' class='caption'>
	<tr height='0'>
		<td width='0'><img src='interface/caption_black_top_left.gif'  alt='Border Image'/></td>
		<td width='100%' background='interface/caption_black_top_mid2.gif' align='center'>
			<table cellspacing='0' cellpadding='0' border='0' width='0' height='0'>
				<tr>
					<td width='0'><img src='interface/caption_black_top_mid1_left.gif'  alt='Border Image'/></td>
					<td width='100%' background='interface/caption_black_top_mid1_mid.gif' align='center' class='caption_title' valign='top'>
						<!-- Caption Title here --> $title <!-- End Caption Title -->
					</td>
					<td width='0'><img src='interface/caption_black_top_mid1_right.gif'  alt='Border Image'/></td>
				</tr>
			</table>
		</td>
		<td width='0'><img src='interface/caption_black_top_right.gif'  alt='Border Image'/></td>
	</tr>
	<tr height='100%'>
		<td width='0' background='interface/caption_black_mid_left.gif'><img src='interface/caption_black_mid_left.gif'  alt='Border Image'/></td>
		<td width='100%' bgcolor='black' align='center'>
			<!-- Caption Content goes here -->
				$content									
			<!-- End Caption Content -->
		</td>
		<td width='0' background='interface/caption_black_mid_right.gif'><img src='interface/caption_black_mid_right.gif'  alt='Border Image'/></td>
	</tr>
	<tr height='0'>
		<td width='0'><img src='interface/caption_black_bot_left.gif' /></td>
		<td width='100%' bgcolor='white' align='right' valign='middle' background='interface/caption_black_bot_mid.gif'>
			<!-- Other Content goes here -->
				$desc
			<!-- End Other Caption Content -->
		</td>
		<td width='0'><img src='interface/caption_black_bot_right.gif'  alt='Border Image'/></td>
	</tr>
</table>\n\n
";
}


//Page delimiters -------------- to help space out content on the page (vertically)


function delimiter_start()
{
	echo "\n\n<table width='100%' height='100%' border='0'><tr><td> <!-- ================================== DELIMITER START =============================== -->\n\n";
}

function delimit()
{
	echo "\n\n</td></tr><tr><td> <!-- ======================== DELIMITER ==================== -->\n\n";
}

function delimiter_end()
{
	echo "\n\n</td></tr></table> <!-- ================================== DELIMITER END=============================== -->\n\n";
}




//A small alert window
function Alert($title,$text)
{
	echo "<center><table align='center' class='alert' width='400' height='20'>\n";
	echo "<th colspan='1' class='alert'>$title</th>\n";
	echo "<tr class='alert'><td>$text</td></tr>\n";
	echo "</table></center>";
}

//Changes newlines and carriage returns into <br /> tags
function newlines($val)
{
	return str_replace("\n","<br />",str_replace('\n',"<br />",str_replace("\r","",$val)));
}


//If returntarg is set ([webpage-'.php'] [target]), have back button, else blank
function return_link()
{
	if ( !preg_match("/([_a-zA-Z0-9]{2,20})\s([_a-zA-Z0-9]+)/",cleantext($_GET['returntarg']),$matches) )
	{ return ""; }
	else {
		return "<form action='".$matches[1].".php' method='GET'>".form_hidden("target",$matches[2]).form_submit("Back")."</form><br />\n";
	}
}

//Creates page controls, given page ID, how many to display per page, how many total of each, and extra hidden tags
//Pages are stored in sessions
function page_controls($id,$numPerPage,$numA,$extras)
{
	$PAGE_A = $_SESSION["_pageNumber_$id"];
	if ( $numA > 0 ) {
		echo "<table align='center'><tr>\n";
			if ( $PAGE_A > 0 ) 
			{ 
					echo "<td><form action='#page' method='POST'>".form_hidden("_pageKeep","yes").form_hidden("_pageAction_$id","prev_").$extras.form_submit("<<")."</form></td>";
			}
			for($i = 0; $i <= floor(($numA-1)/$numPerPage) && floor(($numA-1)/$numPerPage) != 0; $i++)
			{
				if ( $i == $PAGE_A ) { $disable = "disabled='disabled'"; } else { $disable = ""; }
				if ( ($i > $PAGE_A-3 && $i < $PAGE_A + 3) || $i == 0 || $i == floor($numA/$numPerPage)) 
				{ echo "<td><form action='#page' method='POST'>".form_hidden("_pageKeep","yes").form_hidden("_pageAction_$id","goto_$i")."<input type='submit' value='".($i+1)."' $disable />$extras</form></td>"; }
				else { echo "<td>.</td>"; }
			}
			if ( $PAGE_A <= floor(($numA-1)/$numPerPage)-1 ) { echo "<td><form action='#page' method='POST'>".form_hidden("_pageKeep","yes").form_hidden("_pageAction_$id","next_").form_submit(">>")."$extras</form></td>"; }
		echo "</tr></table>";
	}
}

//Recieves page change action and updates
function page_action($id)
{
	if ( get_post("_pageKeep") != "yes" ) { $_SESSION["_pageNumber_$id"] = 0; }

	if ( !isset($_SESSION["_pageNumber_$id"]) ) { $_SESSION["_pageNumber_$id"] = 0; }
	$action = explode("_",get_post("_pageAction_$id"));
 
	if ( $action[0] == "prev" ) { $_SESSION["_pageNumber_$id"]--;  return true;}
	else if ( $action[0] == "next" ) { $_SESSION["_pageNumber_$id"]++;  return true;}
	else if ( $action[0] == "goto" ) { $_SESSION["_pageNumber_$id"] = $action[1]; return true;}

	return false;
}

//Gets the page for Id
function get_page($id)
{
	return $_SESSION["_pageNumber_$id"];
}

//---- FORM STUFF

//Returns a formatted hidden input field
function form_hidden($name,$value)
{
	return "<input type='hidden' name='$name' value=\"" . addslashes($value) . "\" />";
}

//Given a list of frontside and backside enties (arrays), and selected (optional)... returns a select field.
function form_select($name, $listnames, $listvalues, $selected_value)
{
	if ( count($listnames) != count($listvalues) ) { return "Form_select error: lists do not match."; }
	if ( $name == "" ) { return "Form_select error: no name given."; }

	$temp = "<select name='$name'>";
	for($i = 0; $i < count($listnames); $i++)
	{
		$temp .= "<option value=\"" . addslashes($listvalues[$i]) . "\"";
		if ( $selected_value == $listvalues[$i] ) { $temp .= " selected='selected'"; }
		$temp .= ">" . $listnames[$i] . "</option>";
	}
	$temp .= "</select>";
	return $temp;
}

//Creates a submit button
function form_submit($title)
{
	return "<input type='submit' value=\"$title\" />";
}

//Creates a submit button (With name set)
function form_submit_name($title,$name)
{
	return "<input type='submit' name=\"$name\" value=\"$title\" />";
}

?>
