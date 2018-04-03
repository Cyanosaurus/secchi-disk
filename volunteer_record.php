<?php
/* Volunteer_record.php
   Minimum Access Level: Admin(2)
   Date: 5/21/07
   Created by: Jasper Turcotte
   Modifiers:
		<name> on <date>
   Purpose: Given a volunteer ID#, will display test and secchi-reading results, as well as provide the ability to update the details of the account.

   Incoming variables:
	* Account data fields
	* Grading commands 
    * Account ID

   Code Map:
	-1.0 Gather initial information about person, redirect back to view_Records.php otherwise
	-2.0 Update Account
    -3.0 Delete Account
    	-3.1 Clear Account Data
    -4.0 Grade an ungraded test question
    -5.0 Main Content
      -5.1 Show volunteer data primary
      -5.2 Show test/reading data only if a regular user.
      -5.3 Present test Information
      -5.4 Show list of secchi readings

   Main Outgoing Variables 
	-> self
		* Account data fields
    	* Grading commands
    	* Account ID
	-> test_manager.php
		* Test ID
*/

	include_once("libraries.php");
	
//Process incoming actions ----------------------------------

if ( $_SESSION['level'] < 2 ) { header("Location: index.php"); exit;}

$action = get_post('action');
$alert_text = "";				//Message User, if Any
$target = super_cleantext($_GET['target']);

//Paging
$numPerPage = 10;
page_action("test");
page_action("reading");
page_action("answers");


//1.0 Gather initial information about person, redirect back to view_Records.php otherwise
$query = "SELECT * FROM `Account` WHERE `Username`='$target'";
$result = mysql_get($query);
$info = mysql_fetch_array($result);
if ( $info == false ) { header("Location: view_volunteers.php"); exit; }
$AccID = $info['ID'];

//Process actions

//2.0 Update Account
if ( $action == "Update" )
{
	//Get variables
	$fullname = get_post("fullname");
	$account_clean = super_cleantext(get_post("account"));
	$account = get_post("account");
	$pass1 = get_post("pass1");
	$pass_clean = super_cleantext($pass1);
	$email = get_post("email");
	$status = get_post("status");
	$priv = get_post("priv");
	$modtarg = get_post("modtarg");

	$continue = true;	//Whether to finish processing in the end, or not

	//Check to see if account ID#/Username already exists
	$query = "SELECT `ID`,`Privileges` FROM `Account` WHERE `ID`='$modtarg'";
	$result = mysql_get($query);
	$row = mysql_fetch_array($result);
	$priv_current = $row['Privileges'];	

	//Validate input
	if ( $fullname == "" ) { $alert_text .= "You must supply a full name.<br />"; $continue = false;}
	if ( $account == "" ) { $alert_text .= "You must supply an Account ID name.<br />"; $continue = false;}
	if ( $account != "" && strlen($account) < 3 || strlen($account) > 16 ) { $alert_text .= "The Account ID length must be between 3 and 16 characters.<br />"; $continue = false;}
	if ( $account != $account_clean ) { $alert_text .= "The Account ID contains invalid symbols.  You can only use letters, numbers, underscores or dashes.<br />"; $continue = false;}
	if ( $pass1 == "" ) { $alert_text .= "You must supply a password.<br />"; $continue = false;}
	if ( $pass1 != $pass_clean ) { $alert_text .= "The password contains invalid symbols.  You can only use letters, numbers and underscores.<br />"; $continue = false;}
	if ( $pass1 != "" && (strlen($pass1) < 6 || strlen($pass1) > 16 )) { $alert_text .= "The password length must be between 6 and 16 characters.<br />"; $continue = false;}
	if ( $email == "" ) { $alert_text .= "You must supply an email address.<br />"; $continue = false;}
	if ( $email != "" && !preg_match("/[.a-zA-Z0-9_-]+@[.a-zA-Z0-9_-]+\.[a-zA-Z0-9_-]+/",$email,$matches) ) { $alert_text .= "Email address is invalid form.<br />"; $continue = false;}
	if ( $status != "i" && $status != "a" ) { $alert_text .= "You must supply a valid status type.<br />"; $continue = false;}
	if ( $priv == 'u' && $info['Login_key']==$_SESSION['sekey'] ) { $alert_text .= "You cannot change yourself to regular User.<br />"; $continue = false;}
	if ( $priv != 'u' && $priv != 'a' ) { $alert_text .= "Invalid account type.<br />"; $continue = false;}

	//Make sure we're trying to edit something that exists
	if ( $row == false ) { $alert_text .= "The account you are attempting to modify does not exist.<br />"; }
	else {


		$query = "SELECT `ID` FROM `Account` WHERE `Username`='$account' AND `ID`<>'$modtarg'";
		$result = mysql_get($query);
		$row = mysql_fetch_array($result);
		if ( $row != false ) { $alert_text .= "That Account ID already exists.<br />"; $continue = false; }
	

		//See if email is taken
		if (  $email != "" ) {
		$query = "SELECT `ID` FROM `Account` WHERE `Email`='$email' AND `ID`<>'$modtarg'";
		$result = mysql_get($query);
		$row = mysql_fetch_array($result);
		if ( $row != false ) { $alert_text .= "That Email address is already used.<br />"; $continue = false; }
		}

		//update database
		if ( $continue )
		{

			//Update account info
			if ( $accountID == "" ) { $accountID = $rand; }
			$query = "UPDATE `Account` SET `Name`='$fullname',`Username`='$account',`Password`='$pass1',`Email`='$email',`Status`='$status',`Privileges`='$priv' WHERE `ID`='$modtarg'";
			$results = mysql_get($query);
			if ( !$results) { Alert("Uh oh!",$query); }

			$alert_text .= "Account Modified.<br />";

			//Reload account data
			//Gather initial information about person, redirect back to view_Records.php otherwise
			$query = "SELECT * FROM `Account` WHERE `Username`='$target'";
			$result = mysql_get($query);
			$info = mysql_fetch_array($result);
			if ( $info == false ) { header("Location: view_volunteers.php"); }
			$AccID = $info['ID'];

			//Warn abut hotmail/yahoo
			if ( preg_match("/(hotmail|yahoo)/i",$email,$matches)) { $alert_text .= "The email provider '".strtoupper($matches[1])."' has been detected. <br /><font color='red'>Make sure VLMP messages aren't redirected to your Junk or Spam folder.</font><br /><br />"; }
	
			if ( get_post("notify") != "" ) { 
				$alert_text .= "Person has been notified of their new information.<br />"; 

				$subject = "Account Update";
				$website = WEBSITE;
				$body = "Dear $fullname,\n\nYour account with the VLMP has been modifed.\n\nYour current login information:\nLogin name: $account\nPassword:  $pass1\n\nGo to $website and log in to take your test, when ready.\n\nThis is an automated message.  Do not reply.\n-Volunteer Lake Monitoring Program";
				$from = "From: VLMP_admin";
				if (!mail($email,$subject, $body,$from)) { $alert_text .= "Error sending email."; }

			}
		}
	}
}

//3.0 Delete Account
if ( $action == "Delete Account" )
{
	$modtarg = get_post("modtarg");
	$query = "SELECT * FROM `Account` WHERE `ID`='$modtarg' AND `Login_key`='".$_SESSION['sekey']."'";
	$result = mysql_get($query);
	if ( !$result ) { Alert("Uh oh!",$query); }
	$row = mysql_fetch_array($result);
	if ( $row == false ) {
		//Remove if not current login
		$query = "DELETE FROM `Account` WHERE `ID`='$modtarg'"; 
		$result = mysql_get($query);
		if ( !$result ) { Alert("Uh oh!",$query); }

		$query = "DELETE FROM `Test_taken` WHERE `AccountID`='$modtarg'"; 
		$result = mysql_get($query);
		if ( !$result ) { Alert("Uh oh!",$query); }

		$query = "DELETE FROM `Account_answer` WHERE `AccountID`='$modtarg'"; 
		$result = mysql_get($query);
		if ( !$result ) { Alert("Uh oh!",$query); }

		$query = "DELETE FROM `Reading` WHERE `AccountID`='$modtarg'"; 
		$result = mysql_get($query);
		if ( !$result ) { Alert("Uh oh!",$query); }
		header("Location: view_volunteers.php");

	} else { $alert_text .= "You cannot delete yourself!.<br />"; }
}

//3.1 Clear Account Data
if ( $action == "Clear Account Data" )
{
	$modtarg = get_post("modtarg");
		$query = "DELETE FROM `Test_taken` WHERE `AccountID`='$modtarg'"; 
		$result = mysql_get($query);

		$query = "DELETE FROM `Account_answer` WHERE `AccountID`='$modtarg'"; 
		$result = mysql_get($query);

		$query = "DELETE FROM `Reading` WHERE `AccountID`='$modtarg'"; 
		$result = mysql_get($query);
}

//4.0 Grade an ungraded test question
$GAction = get_post("grade_action");
if ( $GAction == "Correct" || $GAction == "INcorrect" )
{
	$ID = get_post("AID");
	if ( $GAction == "INcorrect" ) { $correct = '0'; } else { $correct = '1'; }	

	$query = "UPDATE `Account_answer` SET `Corrected`='1',`Correct`='$correct' WHERE `ID`='$ID'";
	$result = mysql_get($query);
	if (!$result ) { Alert("Uh oh!","$query"); } else { $alert_text .= "Item graded.<br />"; }
}


$hideOther_FLAG = (get_post("action") == "Review It" || get_post("action") == "Grade It" || $GAction != "" );

//5.0 Main Content
//-----------------------------------------------------------

	print_start_to_navbar("Volunteer Record");
	print_navbar_to_content();
	
		delimiter_start();

			if ( $alert_text != "" ) { Alert("Notice",$alert_text); $reset_continue_FLAG = true; }

			//Provide 'back' return button
			echo return_link();
		
		delimit(); //5.1 Show volunteer data primary

			if ( $info['Privileges'] == 'u' ) 
			{ 
				$infoID = "<input type='text' name='account_id' value=\"".$info['ID']."\" />";

			} else { 
				$infoID = "<font color='#999999'>N/A</font>"; 
			}

			//Status list
			$namelist = Array('Active','Inactive');
			$valuelist = Array('a','i');
			$status = form_select("status",$namelist,$valuelist,$info['Status']);

			//Privileges list
			$namelist = Array('Admin','User');
			$valuelist = Array('a','u');
			$privileges = form_select("priv",$namelist,$valuelist,$info['Privileges']);

			//Modify user forms
			$forms = form_submit_name("Update","action");

			if ( !$hideOther_FLAG ) {
			echo "<form action='' method='post'>".form_hidden("modtarg",$AccID)."<table class='data' width='100%' cellspacing='0' cellpadding='3'>\n";
				echo "<tr class='data'><td width='30%' align='right' class='data_bold'>Full Name (Last,First)</td><td align='left'><input type='test' size='50' maxlength='50' name='fullname' value=\"".$info['Name']."\" /></td></tr>\n";
				echo "<tr><td width='30%' align='right' class='data_bold'>Account ID</td><td align='left'><input type='test' maxlength='16' name='account' value=\"".$info['Username']."\" /></td></tr>\n";
				echo "<tr><td width='30%' align='right' class='data_bold'>Password</td><td align='left'><input type='test'  maxlength='16' name='pass1' value=\"".$info['Password']."\" /></td></tr>\n";
				echo "<tr><td width='30%' align='right' class='data_bold'>Email Address</td><td align='left'><input type='test' maxlength='50' size='50' name='email' value=\"".$info['Email']."\" /><a href='mailto:".$info['Email']."'>Email&nbsp;User</a></td></tr>\n";
				echo "<tr><td width='30%' align='right' class='data_bold'>Status</td><td align='left'>".$status."</td></tr>\n";
				echo "<tr><td width='30%' align='right' class='data_bold'>Privileges</td><td align='left'>$privileges</td></tr>\n";
				echo "<tr><td width='30%' align='right' class='data_bold'>Notify person when modified (Email).</td><td align='left'><input type='checkbox' name='notify'/> You should test to make sure the email arrives properly.</td></tr>\n";
				echo "<tr><td width='30%' align='right' class='data_bold'></td><td align='left'>$forms</td></tr>\n";
			echo "</table></form>\n";
			} else { 
				echo "<form action='' method='POST'>".form_submit("Back to all Account Data").form_hidden("target",$target)."</form>";
			}
		delimit(); //5.2 Show test/reading data only if a regular user.
			
			if ( $info['Privileges'] == 'u' )
			{

				if ( !$hideOther_FLAG ) {
					$numTests = sqlTest_numTestsTakenByAccount($info['ID']);	
					$numReadings = sqlTest_numReadings("",$info['ID']);	
	
					$PAGE_test = get_page("test");
					$PAGE_reading = get_page("reading");
	
					//Show list of tests
					echo "<br />\n";
					table1_start("Tests Taken - Page ".($PAGE_test+1)." of ".cap(ceil($numTests/$numPerPage),1,100000));
						table1_header(Array("/0","<font color='red'>*</font>Test/0","Date/0","Grade/0","Number Correct/0","Passed?/0"));
		
						//display data
						$result = sqlTest_getTestTakenByAccountLimit($info['ID'],$PAGE_test*$numPerPage,$numPerPage);
						$row_number = 0;
						while($row = mysql_fetch_array($result))
						{
		
							$new_ID = $row['TestID'];
							$new_date = $row['Date'];
							
							//Look up Test Version	
							$query = "SELECT `Version` FROM `Test` WHERE `ID`='$new_ID'";
							$result2 = mysql_get($query);
							$temp = mysql_fetch_array($result2);
							if ( $temp == false ) { $version = ""; $TID = "0"; }
							else { $version = "<a alt='Open Test Version ".$temp['Version']."' href='test_manager.php?target=".$row['TestID']."&backtrig=yes&returntarg=volunteer_record%20$target'>Version&nbsp;".$temp['Version']."</a>"; $TID=$new_ID;}
		
							//Find out how many questions there were.
							$num_questions = sqlTest_numQuestions("",$new_ID,"");
		
							//Find out how many questions were correctly answered.
							$num_correct = sqlTest_numCorrect($row['ID'],$row['TestID'],$info['ID']);
		
							//Count how many Short Answer questions haven't been graded
							$num_ungraded = sqlTest_numUngraded($row['ID'],$row['TestID'],$info['ID']);
	
							$pending = ( ( $num_questions == 0 ) || ( $num_ungraded != 0 ) );
	
							if ( $pending ) 
							{
								$viewform = "<form action='#grade' method='POST'>".form_hidden("TestTID",$row['ID']).form_submit_name("Grade It","action")."</form>";
							} else {
								$viewform = "<form action='#grade' method='POST'>".form_hidden("TestTID",$row['ID']).form_submit_name("Review It","action")."</form>";
							}
			
							//Calculate and color grade
							if ( !$pending ) { 
								$grade = round(100 * $num_correct / $num_questions);
								if ( $grade < PassingGrade ) { $grade = "<font color='red'>$grade</font>"; }
								$grade .= "%";
							} else { $grade = "<font color='#999999'>Pending</font>"; }
	
							//Calculate and color #correct and flags
							if ( !$pending ) { $numcorrect = $num_correct."/".$num_questions;  } else { $numcorrect = "<font color='#999999'>Pending</font>"; }
							if ( !$pending ) { if ( $grade > PassingGrade ) { $passed = "Yes"; } else { $passed = "<font color='red'>No</font>"; }  } else { $passed = "<font color='#999999'>Pending</font>"; }
		
							$line = Array($viewform,$version,$new_date,$grade,$numcorrect,$passed);
							table1_row($line,$row_number);
							$row_number++;
						}
		
					table1_end_total($row_number);
						echo "		<center><font color='#999999' style='font-size:10px;'>* Click on a Test Version to open its information.</font></center>";

						//Test Paging
						echo "<a name='view'></a>\n";
						page_controls("test",$numPerPage,$numTests,form_hidden("target",$target).form_hidden("Action","Tests Taken"));
					}

	
			  //5.3 Present test Information
	
				if ( get_post("action") == "Review It" || get_post("action") == "Grade It" || $GAction != "" )
				{
					echo "<a name='grade'></a>\n";
					//Get test data
					//Grab the Test Taken data
					$TestTID = get_post("TestTID");
					$result = sqlTest_getTestTakenByID($TestTID);
					$row = mysql_fetch_array($result);

					$numAnswers = sqlTest_numAnsweredByAccount($TestTID,$info['ID']);
					$PAGE_answers = get_page("answers");

					table1_start("Questions and Answers - Page ".($PAGE_answers+1)." of ".cap(ceil($numAnswers/$numPerPage),1,100000));
					table1_header(Array("Mark/0","Number/0","Question/50%","Answer Given/50%","Correct?/0"));
	
					//If it exists, paw through the entries
					if ( $row )
					{
						$TestID = $row['TestID'];
						$AccID = $row['AccountID'];
						$TestTID = $row['ID'];
						//Get the Questions
						$result2 = sqlTest_getQuestionFromAllLimit($TestTID,$TestID,$info['ID'],$PAGE_answers*$numPerPage,$numPerPage);

						$row_count = 0;
						$lastindex = -1;	//To keep track of change of question
				
						while( $row2 = mysql_fetch_row($result2) )
						{
								//Determine if not same question, but different attempt
								if ( $lastindex != $row2[1] ) { $Question = $row2[0]; $lastindex = $row2[1]; $currrow = $row2[1]+1; } else { $Question = "..."; $currrow = ""; }
		
								//Correct it
								/* Query reference ($row2)
									0 = `Question`.`Question`
									1 = `Question`.`Index`
									2 = `Account_answer`.`Answer`
									3 = `Account_answer`.`Selected`
									4 = `Answer`.`Correct`
									5 = `Question`.`Type`
									6 = `Answer`.`Index`
									7 = `Account_answer`.`Corrected`
									8 = `Account_answer`.`ID`
									9 = `Answer`.`Answer`
									10= `Question`.`Enabled`
									11= `Account_answer`.`Correct`
								*/

								if ( ($row2[3]==$row2[6] && $row2[7]=='1' && $row2[5]=='0' && $row2[4]=='1') || ($row2[5]=='1' && $row2[7]=='1' && $row2[11]=='1') ) { $correct = "Yes"; } else { $correct = "<font color='red'>No</font>"; }
								if ( $row2[7]=='0' && $row2[5]=='1' ) {
									$gradeitform = "<form action='#grade' method='POST'>".form_hidden("TestTID",$TestTID).form_hidden("AID",$row2[8]).form_hidden("index",$row2[1]).form_submit_name("Correct","grade_action").form_submit_name("INcorrect","grade_action")."</form>";
									$Question .= "<br /><font color='#999999' size='10px'>(Short Answer)</font>";
	
									$correct = "<font color='#999999'>Pending</font>";
								} else { $gradeitform = ""; }
	
								//Load up answer given
								if ( $row2['5'] == '1' ) { $theAnswer = $row2['2']; }
								else {
									$theAnswer = $row2['9'];
								}	
								
								$line = Array($gradeitform,$currrow,newlines($Question),newlines($theAnswer),$correct);
							table1_row($line,$row_count);
							$row_count++;
						}
					}
	
					table1_end_total($row_count);

					page_controls("answers",$numPerPage,$numAnswers,form_hidden("target",$target).form_hidden("TestTID",$TestTID).form_hidden("action","Review It"));


				}

			delimit(); //5.4 Show list of secchi readings

				if ( !$hideOther_FLAG ) {
					echo "<br /><br />\n";
	
					table1_start("Secchi Readings Taken - Page ".($PAGE_reading+1)." of ".cap(ceil($numReadings/$numPerPage),1,100000));
						table1_header(Array("<font color='red'>*</font>Test/0","Lake Type/0","Generated Reading/0","Measured Reading/0","Tolerance/0","Range/0","Reading Passed?/0"));
						$result = sqlTest_getReadingsLimit("",$info['ID'],$PAGE_reading*$numPerPage,$numPerPage);
	
						$lastid = -1;
						$row_count = 0;
						while($row = mysql_fetch_row($result))
						{
							if ( $row[15] != $lastid ) { $lastid = $row[15]; $version = "<a alt='Open Test Version ".$lastid."' href='test_manager.php?target=".$row[8]."&backtrig=yes&returntarg=volunteer_record%20$target'>Version&nbsp;$lastid</a>"; } else { $version = "..."; } 
	
							if ( $row[10] > $row[11] - $row[4] && $row[10] < $row[11] + $row[4] ) { $passed = "Yes"; } else { $passed = "<font color='red'>No</font>"; }
		
							$line = Array($version,$row[1],padme($row[11])."m",padme($row[10])."m","&plusmn;".padme($row[4])."m",padme($row[2])."-".padme($row[3])."m",$passed);
							table1_row($line,$row_count);
							$row_count++;
						}
		
					table1_end_total($row_count);
					echo "		<center><font color='#999999' style='font-size:10px;'>* Click on a Test Version to open its information.</font></center>";
	
					//Secchi Paging
					echo "<a name='view2'></a>\n";
					page_controls("reading",$numPerPage,$numReadings,form_hidden("target",$target).form_hidden("Action","Tests Taken"));
				} 
			}

		delimit();  
		
			//Make it so we can't delete our own account
			if ( $_SESSION['username'] != $info['Username'] && !$hideOther_FLAG ) 
			{ 
				echo "<table><tr><td><form action='' method='post' onSubmit=\"return confirm('Are you sure you want to delete this?');\">".form_hidden("modtarg",$AccID)."<span class='warning'>".form_submit_name("Delete Account","action")."</span></form></td>";
				if ( $info['Privileges'] == 'u' ) { echo "<td><form action='' method='post' onSubmit=\"return confirm('This will clear all Secchi Readings and Tests taken by this account. Continue?');\">".form_hidden("modtarg",$AccID)."<span class='warning'>".form_submit_name("Clear Account Data","action")."</span></form></td>"; }
				echo "</tr></table>";
			}

		delimiter_end();
	
	print_content_to_end();
?>