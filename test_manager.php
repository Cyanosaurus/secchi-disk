<?php
/* test_manager.php
   Minimum Access Level: Admin(2)
   Date: 5/21/07
   Created by: Jasper Turcotte
   Modifiers:
		<name> on <date>
   Purpose: Allows admin to manage tests and see who's taken what test so far (with test status)

   Incoming variables:
	* TestID of which to view status

   Code Map:
	-1.0 Action: Create New Test (Creates blank test)
    -2.0 Action: Copy Test
    -3.0 Action: Delete Test
    -4.0 Action: Enable Test
    -5.0 Action: Enable Test
    -6.0 Main Content
      -6.1 Test Control Panel
	  -6.2 Show people who've taken the test
	  -6.3 Show list of secchi readings

   Main Outgoing Variables 
	-> Self
		* TestID of which to view status
	-> volunteer_record.php
		* AccountId of which to view
    -> edit_questions.php
		* Test ID of which set of questions to view/edit
*/

	include_once("libraries.php");
	
//Process incoming actions ----------------------------------

if ( $_SESSION['level'] < 2 ) { header("Location: index.php"); exit;}

//Paging
page_action("test2");
page_action("reading2");
page_action("tests");
$PAGE_tests = get_page("tests");
$PAGE_test = get_page("test2");
$PAGE_reading = get_page("reading2");
$numPerPage = 10;

$action = get_post('Action');
$alert_text = "";				//Message User, if Any


if ( $action == "Tests Taken" || isset($_GET['backtrig']) || isset($_GET['target']) ) { $hideTests_FLAG = true; } else { $hideTests_FLAG = false; }

//1.0 Action: Create New Test (Creates blank test)
if ($action == "New")
{
	//Get Last Version, create new

	//Add new entry
	$query = "INSERT INTO `Test` (`Current`,`Date`) VALUES ('0',now())";
	$result = mysql_get($query);

	//Match version to ID
	$id = sqlTest_newID('Test');
	mysql_get("UPDATE `Test` SET `Version`='$id' WHERE `ID`='$id'");

	//Feedback to User
	if ( $result != false ) { $alert_text = "New blank test created."; } else { $alert_text = "Error in Test-new syntax."; }
}

//2.0 Action: Copy Test
else if ($action == "Copy") 
{
	//Get the ID of Test to Copy
	$ID = get_post('target');

	//Get Test Data
	$result = sqlTest_getTest($ID);
	if (($row = mysql_fetch_array($result))== false) { $alert_text .= "That test does not exist."; }
	else {
		//Get Last Version, create new
		/*$query = "SELECT * FROM `Test` ORDER BY `Version` DESC";
		$result = mysql_get($query);
		if ( ($row = mysql_fetch_array($result)) == false )
		{
			$new_version = 1;
		} else {
			$new_version = $row['Version']+1;
		}*/

		//Make new test
		$query = "INSERT INTO `Test` (`Version`,`Current`,`Date`) VALUES ('$new_version','0',now())";
		$result = mysql_get($query);

		//Match version to ID
		$id = sqlTest_newID('Test');
		mysql_get("UPDATE `Test` SET `Version`='$id' WHERE `ID`='$id'");

		if ( $result != false ) { $alert_text = "Test copied as <b>Version $new_version</b>."; } else { $alert_text = "Error in Test-copy syntax."; }

		//Get the new ID
		$newID = sqlTest_newID('Test');

		//Copy all Related questions/answers
		$result = sqlTest_getQuestionByTest($ID);
		while($row=mysql_fetch_array($result))
		{
			$qID = $row['ID'];

			//Write new question
			$Question = $row['Question'];
			$TestID = $newID;
			$Hint = $row['Hint'];
			$Type = $row['Type'];
			$ImageID = $row['ImageID'];
			$Index = $row['Index'];
			$Enabled = $row['Enabled'];
			$query_newquestion = "INSERT INTO `Question` (`TestID`,`Question`,`Hint`,`Type`,`ImageID`,`Index`,`Enabled`) VALUES ('$TestID','$Question','$Hint','$Type','$ImageID','$Index','$Enabled')";
			//echo $query_newquestion . "<br />";
			mysql_get($query_newquestion);
			$query_newquestion = "SELECT `ID` FROM `Question` WHERE `TestID`='$newID' AND `Question`='$Question' AND `Hint`='$Hint' AND `Type`='$Type' AND `Index`='$Index' ORDER BY `ID` DESC";
			
			//echo $query_newquestion . "<br />";
			$result_newquestion = mysql_get($query_newquestion);
			$rowID = mysql_fetch_array($result_newquestion);
			$newQID = $rowID['ID'];

			//Copy all answers linked to question
			$query_answer = "SELECT * FROM `Answer` WHERE `QuestionID`='$qID'";
			$result_answer = mysql_get($query_answer);
			while($row_answer = mysql_fetch_array($result_answer))
			{
				$query_new = "INSERT INTO `Answer` (`QuestionID`,`Answer`,`Correct`,`Index`) VALUES ('$newQID','" . $row_answer['Answer'] . "','" . $row_answer['Correct'] . "','" . $row_answer['Index'] . "')";
				
				//echo $query_new . "<br />";
				$result_new = mysql_get($query_new);
			}

		}
	}
}

//3.0 Action: Delete Test
else if ($action == "Delete")
{
	//Get the ID of Test to Copy
	$ID = get_post('target');

	//Remove any `Account_Answer` and `Answer` Entries of any `Question` entries that are linked to this test.
	$result = sqlTest_getQuestionByTest($ID);
	while($row = mysql_fetch_array($result))
	{	//Looks up each linked question
		$qID = $row['ID'];		

		//Delete Answer entries
		$query = "DELETE FROM `Answer` WHERE `QuestionID`='$qID'";
		if ( mysql_get($query) == false ) { Alert("Uh oh!",$query); }		

		//Delete Account_Answer entries
		$query = "DELETE FROM `Account_Answer` WHERE `QuestionID`='$qID'";
		mysql_get($query);		
	}

	//Remove any `Question` and `Reading` entries with the TestID
	$query = "DELETE FROM `Question` WHERE `TestID`='$ID'";
	$result = mysql_get($query);		

	$query = "DELETE FROM `Reading` WHERE `TestID`='$ID'";
	$result = mysql_get($query);	

	//Delete test takens
	$query = "DELETE FROM `Test_taken` WHERE `TestID`='$ID'";
	$result = mysql_get($query);	

	//Delete reports
	$query = "SELECT `Version` FROM `Test` WHERE `ID`='$ID'";
	$result = mysql_get($query);	
	$row = mysql_fetch_array($result);
	if(file_exists("reports/Report_VTest_".$row['Version'].".txt") ) { unlink("reports/Report_VTest_".$row['Version'].".txt"); }
	
	$query = "DELETE FROM `Report_history` WHERE `TestID`='$ID'";
	$result = mysql_get($query);	

	//Remove the Test
	$query = "DELETE FROM `Test` WHERE `ID`='$ID'";
	$result = mysql_get($query);
	if ( $result != false ) { $alert_text .= "Test Deleted."; } 
} 

//4.0 Action: Enable Test
else if ($action == "Enable")
{
	//Get the ID of Test to Copy
	$ID = get_post('target');
	if ( !sqlTest_isReported($ID) ) 
	{
	
		//First, disable all tests
		$query = "UPDATE `Test` SET `Current`='0'";
		$result = mysql_get($query);
	
		//Second, enable this test.
		$query = "UPDATE `Test` SET `Current`='1' WHERE `ID`='$ID'";
		$result = mysql_get($query);
		if ( $result != false ) { $alert_text = "Test Enabled"; } else { $alert_text = "Error in Test-enable syntax."; }
	} else {
		$alert_text .= "That test can not be enabled.  It has already been process for reporting.";
	}
}

//5.0 Action: Enable Test
else if ($action == "Disable")
{
	//Get the ID of Test to Copy
	$ID = get_post('target');

	//disable this test.
	$query = "UPDATE `Test` SET `Current`='0' WHERE `ID`='$ID'";
	$result = mysql_get($query);
	if ( $result != false ) { $alert_text = "Test Disabled"; } else { $alert_text = "Error in Test-Disable syntax."; }
}

//-----------------------------------------------------------

	//6.0 Main Content
	print_start_to_navbar("Manage Tests");
	print_navbar_to_content();
	
		delimiter_start();
		
		delimit();

			if ( $alert_text != "" ) { Alert("Notice",$alert_text); }
			
			//Provide 'back' return button
			echo return_link();

		delimit(); //6.1 Test Control Panel

			if ( !$hideTests_FLAG ) {

				echo "<form action='' method='POST'>" . form_hidden("Action","New") . form_submit("Create New Test") . "</form>";
				table1_start("Tests");
				table1_header(Array("<font color='red'>*</font>Current/0","Version/0","# Questions/0","Last Modified/0","<font color='red'>**</font>Test History/0","<font color='red'>***</font>/0","/0","/0","/0"));
	
					//Get Test List
					$rowcount = 0;	//To feed to table1_row()
					$query = "SELECT * FROM `Test` ORDER BY `Date`,`Version` ASC LIMIT ".($PAGE_tests*$numPerPage).",$numPerPage";
					$result = mysql_get($query);
					$numTests2 = sqlTest_numTests();
					while($row = mysql_fetch_array($result))
					{
	
						$numQuestions = sqlTest_numQuestions("",$row['ID'],"");

						//Forms - View/Edit
							//Find out if anyone has taken the test yet
	
							//Check for answered questions
							$lock_FLAG = isTestLocked($row['ID']);
							$lock_FLAG_Taken = isTestLocked_Taken($row['ID']);
	
						if ( $lock_FLAG )
						{
							$view_edit = "<form action='edit_questions.php' method='POST'>" . form_hidden("TestID",$row['ID']) . form_submit("View") . "</form>";
							if ( $lock_FLAG_Taken ) {
								$tests_taken = "<form action='#view' method='POST'>" . form_hidden("target",$row['ID']) . form_submit_name("Tests Taken","Action") . "</form>";
							} else { $tests_taken = "<font color='#999999'>No Tests Taken</font>"; }
						} else {
							if ( $lock_FLAG_Taken || $row['Current']=='1' ) {
								$view_edit = "<form action='edit_questions.php' method='POST'>" . form_hidden("TestID",$row['ID']) . form_submit("View") . "</form>";
							} else {
								$view_edit = "<form action='edit_questions.php' method='POST'>" . form_hidden("TestID",$row['ID']) . form_submit("Edit") . "</form>";
							}	
							$tests_taken = "<font color='#999999'>No Tests Taken</font>";
						}
						
						//Can always delete a test						
						$delete_test = "<form action='' method='POST' onSubmit=\"return confirm('Are you sure you want to delete this? This will remove the following things:\\n1)All Questions and Answers\\n2)All Account-given Answers and Test History\\n3)All Generated Report data\\nThis is not reversable.');\" >" . form_hidden("target",$row['ID']) . form_hidden("Action","Delete") . form_submit("Delete") . "</form>";
	
						if ( $row['Current'] == '1' ) 
						{
							$enable_test = "<form action='' method='POST' onSubmit=\"return confirm('Are you sure you want to disable this? You may not be able to re-enable it if anyone has taken the test.');\" >" . form_hidden("target",$row['ID']) . form_hidden("Action","Disable") . "<span style='padding:6px;background-color:#00aa00;'>" . form_submit("Disable") . "</span></form>";
						} else {
							if ( !sqlTest_isReported($row['ID']))
							{
								$enable_test = "<form action='' method='POST' onSubmit=\"return confirm('Are you sure you want to enable this?');\" >" . form_hidden("target",$row['ID']) . form_hidden("Action","Enable") . form_submit("Enable") . "</form>";
							} else {
								$enable_test = "[Report&nbsp;Generated]";
							}
						}
	
						$copy_test = "<form action='' method='POST'>" . form_hidden("target",$row['ID']) . form_hidden("Action","Copy") . form_submit("Copy") . "</form>";					
	
						$row_array = Array($enable_test,$row['Version'],$numQuestions,convertdate($row['Date']),$tests_taken,$view_edit,$copy_test,$delete_test,"");
						table1_row($row_array,$rowcount);
	
						$rowcount++;
					}

				table1_end_total($rowcount); 
				echo "		<center><font color='#999999' style='font-size:10px;'>* Only one test may be active at a time.</font></center>";
				echo "		<center><font color='#999999' style='font-size:10px;'>** Some tests may be in the process of being taken, but not yet complete.</font></center>";
				echo "		<center><font color='#999999' style='font-size:10px;'>*** Tests with history are not editable.</font></center>";
				
				page_controls("tests",$numPerPage,$numTests2,form_hidden("target",$target));

			} else { echo "<form action='' method='post'>".form_submit("View All Tests")."</form>"; }

		delimit(); //6.2 Show people who've taken the test

			//Show test/reading data only if a regular user.
			echo "<a name='view'></a>\n";
			if ( $action == "Tests Taken" || isset($_GET['backtrig']) || isset($_GET['target']))
			{

				if ( ($target = super_cleantext($_GET['target'])) == "" ) { $target = get_post('target'); }
				//Show list of tests

				$numTests = sqlTest_numTestsTakenByID($target);
				$numReadings = sqlTest_numReadings($target,"");

				//Look up test version
				$query = "SELECT `Version` FROM `Test` WHERE `ID`='$target'";
				$result = mysql_get($query);
				$row = mysql_fetch_array($result);
				$Version = $row['Version'];
					
				//Tests taken table
				table1_start("Tests Taken for Version $Version - Page ".($PAGE_test+1)." of ".cap(ceil($numTests/$numPerPage),1,100000));
					table1_header(Array("<font color='red'>*</font>Account [ID]/0","Date/100%","Grade/0","Number Correct/0","Passed?/0"));
	
					//display data - get test taken data
					$result = sqlTest_getTestTakenLimit($target,$PAGE_test*$numPerPage,($Page_test+1)*$numPerPage);

					$row_number = 0;
					while($row = mysql_fetch_array($result))
					{
	
						$new_ID = $row['AccountID'];
						$new_date = convertdate($row['Date']);
						$testID = $target;

						//Look up person's account info
						$result2 = sqlTest_getAccount($new_ID);
						$temp = mysql_fetch_array($result2);
						if ( $temp == false ) { $account = "[error]"; }
						else { $account = "<a href=\"volunteer_record.php?target=".$temp['Username']."&returntarg=test_manager%20$target\">".str_replace(" ","&nbsp;",$temp['Name'])."</a>[".$temp['Username']."]"; }
						

						//Find out how many questions there were.
						$num_questions = sqlTest_numQuestions("",$testID,"");
	
						//Find out how many questions were correctly answered.
						$num_correct = sqlTest_numCorrect($row['ID'],$testID,$new_ID);
	
						//Count how many Short Answer questions haven't been graded (for current test)
						$num_ungraded = sqlTest_numUngraded($row['ID'],$testID,$new_ID);;
	
						$pending = ( ( $num_questions == 0 ) || ( $num_ungraded != 0 ) );
	
						if ( $pending ) 
						{
							$viewform = "<form action='' method='POST'>".form_hidden("TestTID",$row['ID']).form_submit_name("Grade It","action")."</form>";
						} else {
							$viewform = "<form action='' method='POST'>".form_hidden("TestTID",$row['ID']).form_submit_name("Review It","action")."</form>";
						}
	
						//Calculate and color grades
						if ( !$pending ) { 
							$grade = round(100 * $num_correct / $num_questions);  
							if ( $grade < PassingGrade ) { $grade = "<font color='red'>$grade</font>"; }
							$grade .= "%";
						} else { $grade = "<font color='#999999'>Pending</font>"; }

						//Calculate #correct and flags
						if ( !$pending ) { $numcorrect = $num_correct."/".$num_questions;  } else { $numcorrect = "<font color='#999999'>Pending</font>"; }
						if ( !$pending ) { if ( $grade > PassingGrade ) { $passed = "Yes"; } else { $passed = "<font color='red'>No</font>"; }  } else { $passed = "<font color='#999999'>Pending</font>"; }

						$line = Array($account,$new_date,$grade,$numcorrect,$passed);
						table1_row($line,$row_number);
						$row_number++;
					}

				table1_end_total($row_number);

				//Test Paging

				page_controls("test2",$numPerPage,$numTests,form_hidden("target",$target).form_hidden("Action","Tests Taken"));

			delimit(); //6.3 Show list of secchi readings
	
				echo "<br />\n";
				
				table1_start("Secchi Readings Taken - Page ".($PAGE_reading+1)." of ".cap(ceil($numReadings/$numPerPage),1,100000));
					table1_header(Array("<font color='red'>*</font>Account[ID]/0","Lake Type/0","Generated Reading/0","Measured Reading/0","Tolerance/0","Range/0","Reading Passed?/0"));
					$result = sqlTest_getReadingsLimit($target,"",$PAGE_reading*$numPerPage,$numPerPage);
					$lastid = -1;
					$row_count = 0;
					while($row = mysql_fetch_array($result))
					{
						//Look up person's account info
						$result2 = sqlTest_getAccount($row['AccountID']);
						$temp = mysql_fetch_array($result2);
						if ( $temp == false ) { $account = "[error]"; }
						else { $account = "<a href=\"volunteer_record.php?target=".$temp['Username']."&returntarg=test_manager%20$target\">".str_replace(" ","&nbsp;",$temp['Name'])."</a>[".$temp['Username']."]"; }
						if ( $row['Reading'] > $row['Generated'] - $row['Tolerance'] && $row['Reading'] < $row['Generated'] + $row['Tolerance'] ) { $passed = "Yes"; } else { $passed = "<font color='red'>No</font>"; }
	
						$line = Array($account,$row['Name'],padme($row['Generated'])."m",padme($row['Reading'])."m","&plusmn;".padme($row['Tolerance'])."m",padme($row['Range_low'])."-".padme($row['Range_high'])."m",$passed);
						table1_row($line,$row_count);
						$row_count++;
					}
	
				table1_end_total($row_count);
				echo "<center><font color='#999999' style='font-size:10px;'>* Click on an account to open its information.</font></center>\n";

				//Reading Paging
				echo "<a name='view2'></a>\n";
				page_controls("reading2",$numPerPage,$numReadings,form_hidden("target",$target).form_hidden("Action","Tests Taken"));


			}
		delimiter_end();
	
	print_content_to_end();
?>