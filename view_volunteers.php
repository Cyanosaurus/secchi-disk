<?php
/* view_volunteers.php
   Minimum Access Level: Admin(2)
   Date: 5/21/07
   Created by: Jasper Turcotte
   Modifiers:
		<name> on <date>
   Purpose: Shows a quick rundown of the volunteers and their latest test instances, as well as admin-moderators

   Incoming variables:
	* None

   Code Map:
	-1.0 Main Content
	  -1.1 Show list of administrators
      -1.2 Show list of volunteers
	  -1.3 User paging

   Main Outgoing Variables 
	-> Null
		* None
	-> volunteer_record.pp
		* Account ID
*/

	include_once("libraries.php");
	
//Process incoming actions ----------------------------------

if ( $_SESSION['level'] < 2 ) { header("Location: index.php"); exit; }

//User list paging
$usersPerPage = 10;
if ( isset($_POST['showall']) ) { $usersPerPage = 100000000; }

if ( !page_action("vvusers") && isset($_GET['target']) ) { 
	$returnPAGE = $_GET['target']; $targFLAG = true;
 } else { 
	$targFLAG = false;
}

$action = get_post('action');
$alert_text = "";				//Message User, if Any


//-----------------------------------------------------------

	//1.0 Main Content
	print_start_to_navbar("View Volunteers");
	print_navbar_to_content();
	
		delimiter_start();
		
		delimit();

			if ( $alert_text != "" ) { Alert("Notice",$alert_text); $reset_continue_FLAG = true; }

		delimit(); //1.1 Show list of administrators

				//display data
				table1_start("Administrators");
				table1_header(Array("Full Name/0","Login/0","Status/0"));
					$result = sqlTest_getAdmins();
					$row_number = 0;
					while($row = mysql_fetch_array($result))
					{
						if ( $row['Status'] == 'a' ) { $status = "Active"; } else { $status = "<font color='#999999'>Inactive</font>"; }
						$line = Array("<a href='volunteer_record.php?target=".$row['Username']."'>".$row['Name']."</a>","<a href='volunteer_record.php?target=".$row['Username']."'>".$row['Username']."</a>",$status);
						table1_row($line,$row_number);
						$row_number++;
					}
				table1_end();
				echo "<br />\n";

		delimit(); //1.2 Show list of volunteers

				//Display user data
				if ( !$targFLAG ) {
					$PAGE = get_page("vvusers"); 
				} else { 
					$PAGE = $returnPAGE; 
					$_SESSION['_pageNumber_vvusers'] = $PAGE; 
				}
				$result = sqlTest_getUsersLimit($PAGE*$usersPerPage,$usersPerPage);
				$numUsers = sqlTest_numUsers();
				$row_number = 0;

			table1_start("Volunteers Page ".($PAGE+1)." of ".cap(ceil($numUsers/$usersPerPage),1,100000));
				//Proprietary version of table1_header("Account ID/0","Login/0","Status/0","Test Date/0","Test Version/0","Passed?/0","Lake Type/0","Reading Passed?/0","Certified?/0");
					//Process the incoming data
					$array_headers = Array("/0","<font color='red'>*</font>Account ID/0","Status/0","Test Date/100%","Test Version/0","Passed?/0","Lake Type/0","Reading Passed?/0","<font color='red'>**</font>Certified?/0");
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
						echo "<tr class='data'><td colspan='3'></td><td colspan='3' align='left' bgcolor='#c0caff'><i><b>Recent Test</b></i></td><td colspan='3' align='left' bgcolor='#c0caff'><i><b>Recent Secchi Reading</b></i></td></tr>\n";
 
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
				////////

				while($row = mysql_fetch_array($result))
				{
					//Build username and status fields
					$new_ID = $row['ID'];
					$new_username = "<a href='volunteer_record.php?target=".$row['Username']."&returntarg=view_volunteers $PAGE'>".$row['Username']."</a>";
					if ( $row['Status'] == 'a' ) { $new_status = "Active"; } else { $new_status = "<font color='#999999'>Inactive</font>"; }

						$query = "SELECT `Test_taken`.`ID`,`Test_taken`.`TestID`,`Test`.`Version`,`Test_taken`.`Date`,`Test_taken`.`AccountID` FROM `Test_taken`,`Test` WHERE `Test_taken`.`AccountID`='$new_ID' AND `Test`.`ID`=`Test_taken`.`TestID` AND `Test_taken`.`AccountID`='$new_ID' AND `Test_taken`.`Completed`='1' ORDER BY `Test_taken`.`Date` DESC";

						$result2 = mysql_get($query);
						$temp = mysql_fetch_row($result2);

					//Make sure that if no test data exists, it doesn't die
					if ( $temp != false ) {
						$new_date = $temp[3];
						$new_version = $temp[2];
						$new_tID = $temp[1];
					} else { $new_date = ""; $new_version = ""; $new_tID = 0;}

					//Grade it

						//Find out how many questions there were.
						$num_questions = sqlTest_numQuestions("",$new_tID,"");
	
						if ( $num_questions != 0 )
						{
							//Find out how many questions were correctly answered.
							$num_correct = sqlTest_numCorrect($temp[0],$new_tID,$temp[4]);
		
							//Count how many Short Answer questions haven't been graded
							$num_ungraded = sqlTest_numUngraded($temp[0],$new_tID,$temp[4]);

						} else { $num_correct = 0; $num_ungraded = 0; }
						if ( $num_questions > 0 && ( $num_correct / $num_questions > 0.7 ) ) { $new_passed = "Yes"; } 
						else { $new_passed = "<font color='red'>No</font>"; }

					//Secchi Readings

						/* Query key
							0 = `Lake_type`.`ID`
							1 = `Lake_type`.`Name`
							2 = `Lake_type`.`Range_low`
							3 = `Lake_type`.`Range_high`
							4 = `Lake_type`.`Tolerance`
							5 = `Lake_type`.`Description`
							6 = `Lake_type`.`Color`
							7 = `Reading`.`ID`
							8 = `Reading`.`TestID`
							9 = `Reading`.`AccountID`
							10= `Reading`.`Reading`
							11= `Reading`.`Generated`
							12= `Reading`.`Lake_type`
							13= `Reading`.`Attempt`
						*/
						$result2 = sqlTest_getReadings("",$row['ID']);
						$temp = mysql_fetch_row($result2);
		
						//Process certification flag
						$new_rpassed = $new_certified = "N/A";
						if ( $temp != false ) {
							$new_type = $temp[1];
							if ( ($temp[10] > $temp[11]-$temp[4] && $temp[10] < $temp[11]+$temp[4]) )
							{ $new_rpassed = "Yes"; } else { $new_rpassed = "<font color='red'>No</font>"; }
						} else { $new_rpassed = "<font color='#999999'>Not Taken</font>"; $new_type = "";}

					if ( $new_rpassed == "Yes" && $new_passed == "Yes" ) { $new_certified = "Yes"; } else { $new_certified = "<font color='red'>No</font>"; }

					if ( $new_tID == 0 ) { $new_passed = "<font color='#999999'>Not Taken</font>"; }
					if ( $num_ungraded > 0 ) { $new_passed = "<font color='#999999'>Pendng</font>"; $new_certified = "<font color='#999999'>Pendng</font>";}

					//See if a person's last taken is too old
					if ( $new_date != "" ) {
						$today = strtotime(date("Y-m-d")); 
						$expiration_date = strtotime($new_date); 
						if ($expiration_date < $today - CERTIFICATE_EXPIRE) { $oldFLAG = "<b><font color='red'>Expired</font></b>"; } else { $oldFLAG = ""; }
					} else { $oldFLAG = ""; }
					
					$line = Array($oldFLAG,$new_username,$new_status,convertdate($new_date),$new_version,$new_passed,$new_type,$new_rpassed,$new_certified);
					table1_row($line,$row_number);
					$row_number++;
				}
				

			table1_end_total($row_number);

			echo "<center><font color='#999999' style='font-size:10px;'>* Click on an account to open its information.</font></center>\n";
			echo "<center><font color='#999999' style='font-size:10px;'>** The most recent test/reading are the ones graded towards certification.</font></center>\n";

			delimit(); //1.3 User paging
			
				page_controls("vvusers",$usersPerPage,$numUsers,"");

			delimit();

				echo "<center><form action='' method='POST' onSubmit=\"return confirm('Warning: This may take a moment.');\">".form_hidden("showall","true").form_submit("Show All Volunteers")."</form><form action='volunteer_record.php' method='GET'><input type='text' name='target' size='16' />".form_hidden("returntarg","view_volunteers $PAGE").form_submit("Look Up Volunteer")."</form></center>";

		delimiter_end();
	
	print_content_to_end();
?>