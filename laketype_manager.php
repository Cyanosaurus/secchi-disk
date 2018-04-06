<?php
/* laketype_manager.php
   Minimum Access Level: User-tester (2)
   Date: 5/21/07
   Created by: Jasper Turcotte
   Modifiers:
		<name> on <date>
   Purpose: Allows modification of laketype information

   Incoming variables:
	* Laketype data

   Code Map:
	-1.0 Get and process brightness factor
	-2.0 Update database

   Main Outgoing Variables 
	-> self
		* Lake Type data


*/
	include_once("libraries.php");

	
//Process incoming actions ----------------------------------

if ( $_SESSION['level'] < 2 ) { header("Location: index.php"); exit; }

$action = get_post('action');
$alert_text = "";				//Message User, if Any

$hideOther_FLAG = ( $action == "Edit" || $action == "Update" || $action == "Darker" || $action == "Brighter");

//1.0 Get and process brightness factor
if ( ( $factor = get_post("factor") ) == "" ) { $factor = 1.0; }
if ( $action == "Brighter" ) { $factor = $factor + 0.2; }
if ( $action == "Darker" ) { $factor = $factor - 0.2; }
if ( $factor < 0.1 ) { $factor = 0.1; }
if ( $factor > 3.0 ) { $factor = 3.0; }

//2.0 Update database
if ( $action == "Update" )
{
	//Get data
	$target = get_post("target");
	$name = get_post("name");
	$rl = get_post("rl");
	$rh = get_post("rh");
	$tol = get_post("rt");
	$desc = get_post("desc");
	$colorB = get_post("color");

	//validate input
	$continue = true;
	
	if ( $name == "" ) { $alert_text .= "You must supply a Lake Type name.<br />"; $continue = false; }
	if ( $rl == "" || !preg_match("/[.0-9]+/",$rl,$matches)) { $alert_text .= "You must supply a positive numeric value for Range Low.<br />"; $continue = false; }
	if ( $rh == "" || !preg_match("/[.0-9]+/",$rh,$matches)) { $alert_text .= "You must supply a positive numeric value for Range High.<br />"; $continue = false; }
	if ( $rh > 35 || $rl > 35 ) { $alert_text .= "The maximum upper range is 35 meters.<br />"; $continue = false; }
	if ( $rl > $rh ) { $temp = $rl; $rl = $rh; $rh = $temp; }
	if ( $tol == "" || !preg_match("/[.0-9]+/",$tol,$matches)) { $alert_text .= "You must supply a positive numeric value for Tolerance.<br />"; $continue = false; }
	if ( !preg_match("/#[a-fA-F0-9]{6}/",$colorB,$matches) ) { $alert_text .= "You must supply a 6-digit hexadecimal value for color. (ie. example: #F4E845)<br />"; $continue = false; }

	if ( $continue )
	{
		//Cap the values
		$rl = cap($rl,0.15, 42);
		$rh = cap($rh,0.15, 42);
		$tol = cap($tol,0.01, 100);

		$query = "UPDATE `Lake_type` SET `Name`='$name',`Range_low`='$rl',`Range_high`='$rh',`Tolerance`='$tol',`Description`='$desc',`Color`='$colorB' WHERE `ID`='$target'";
		$results = mysql_get($query);
		if (!$results) { Alert("Uh oh!",$query); } else { $alert_text .= "Lake Type updated successfully. <br />"; }
	}
}




//-----------------------------------------------------------

	print_start_to_navbar("Manage Laketype Information");
	print_navbar_to_content();
	
		delimiter_start();
		
		delimit();

			if ( $alert_text != "" ) { echo "<a name='editform' />\n"; Alert("Notice",$alert_text); $reset_continue_FLAG = true;}

		delimit();

			if ( !$hideOther_FLAG ) {
				//Display current laketypes
				$query = "SELECT * FROM `Lake_type` ORDER BY `ID` ASC";
				$result = mysql_get($query);
				
				table1_start("Lake Types");
				table1_header(Array("Name/0","Opacity Low Limit/0","Opacity High Limit/0","Tolerance/0","Description/100%","Color/0","/0"));
	
					$row_count=0;
					while($row = mysql_fetch_array($result))
					{
						$color = "<table border='2' bgcolor='".$row['Color']."' width='75' height='25'><tr><td>&nbsp;</td></tr></table>";
						$form = "<form action='#editform' method='post'>".form_hidden("target",$row['ID']).form_submit_name("Edit","action")."</form>";
						$line = Array($row['Name'],padme($row['Range_low'])."m",padme($row['Range_high'])."m",padme($row['Tolerance'])."m",newlines($row['Description']),$color,$form);
						table1_row($line,$row_count);
						$row_count++;
					}
				
				table1_end();
			} else { echo "<form action='' method='POST'>".form_submit("Back to Lake Types")."</form>"; }

		delimit();

			//Edit Lake Type
			if ( $action == "Edit" || $action == "Update" || $action == "Darker" || $action == "Brighter")
			{
				if ( $target = get_post("target") )
				{
					$query = "SELECT * FROM `Lake_type` WHERE `ID`='$target'";
					$result = mysql_get($query);
					$row = mysql_fetch_array($result);
					if ( $row == false ) { Alert("Error!","That Lake Type does not exist."); }
					else {
						$colortable = generate_ctable($factor);

						if ( ( $name = get_post("name") ) == "" || $action == "Update") { $name = $row['Name']; }
						if ( ( $rl = get_post("rl") ) == "" || $action == "Update" ) { $rl = ($row['Range_low']); }
						if ( ( $rh = get_post("rh") ) == "" || $action == "Update" ) { $rh = ($row['Range_high']); }
						if ( ( $tol = get_post("rt") ) == "" || $action == "Update" ) { $tol = ($row['Tolerance']); }
						if ( ( $desc = get_post("desc") ) == "" || $action == "Update" ) { $desc = $row['Description']; }
						if ( ( $colorB = get_post("color") ) == "" || $action == "Update" ) { $colorB = $row['Color']; }
						$color = "<table border='2' bgcolor='".$colorB."' width='75' height='25'><tr><td>&nbsp;</td></tr></table>";
						$colorT = "<table border='2' width='75' height='25'><tr><td id='color2' bgcolor='".$colorB."' >&nbsp;</td></tr></table>";

						if ( $alert_text == "" ) { echo "<a name='editform' ></a>\n"; }
						echo "<form action='#editform' method='post'>".form_hidden("factor",$factor).form_hidden("target",$target);
						echo "<table class='data' width='75%'>\n";

						echo "<tr><td class='data_bold' width='0' align='right'>Name:</td><td align='left' width='100%'><input type='text' name='name' value=\"".$name."\" size='55'/></td></tr>\n";
						echo "<tr><td class='data_bold' width='0' align='right'>Range Low:</td><td align='left' width='100%'><input type='text' name='rl' value=\"".padme($rl)."\" size='5'/> meters</td></tr>\n";
						echo "<tr><td class='data_bold' width='0' align='right'>Range High:</td><td align='left' width='100%'><input type='text' name='rh' value=\"".padme($rh)."\" size='5'/> meters</td></tr>\n";
						echo "<tr><td class='data_bold' width='0' align='right'>Tolerance:</td><td align='left' width='100%'><input type='text' name='rt' value=\"".padme($tol)."\" size='5'/> meters</td></tr>\n";
						echo "<tr><td class='data_bold' width='0' align='right'>Description:</td><td align='left' width='100%'><textarea name='desc' cols='55' rows='3'>".$desc."</textarea></td></tr>\n";
						echo "<tr><td class='data_bold' width='0' align='right'>Current Color:</td><td align='left' width='100%'>$color</td></tr>\n";
						echo "<tr><td class='data_bold' width='0' align='right'>New Color:</td><td align='left' width='100%'>$colorT</td></tr>\n";
						echo "<tr><td class='data_bold' width='0' align='right'></td><td align='left' width='100%'><input type='hidden' name='color' id='color' value=\"".$colorB."\" size='9'/> <small>Select a color in the Color Triangle below.</small></td></tr>\n";
						echo "<tr bgcolor='#eaf0fd'><td class='data_bold' width='0' align='center'>Adjust Brightness:<br /><br />".form_submit_name("Brighter","action").form_submit_name("Darker","action")."</td><td align='left' width='100%'>$colortable</td></tr>\n";
						echo "<tr><td class='data_bold' width='0' align='right'>".form_submit_name("Update","action")."</td><td align='left' width='100%'></td></tr>\n";

						echo "</table></form>\n";
					}
				}
			}
	
		delimiter_end();
	
	print_content_to_end();
?>