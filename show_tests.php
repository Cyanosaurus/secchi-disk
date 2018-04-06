<?php
/* show_tests.php
   Date: 5/23/07
   Purpose: After logging in, shows tests already taken by the user, for the current test.
   
   Incoming Variables <-- index.php
	 * TestID and TestTID

   Code Map:
	 - 1.0 Recieve test information
     - 2.0 Show the user any test instances he or she has already taken
		- 2.1 If no tests have been taken, continue
     - 3.0 Give option to continue to test

   Outgoing Variables --> 
     * TestID and TestTID
*/
	include_once("libraries.php");

//Paging
$numPerPage = 10;
page_action("test");
page_action("reading");
page_action("answers");

//Make sure we're a user
if ( $_SESSION['level'] != 1 ) { header("Location: index.php"); exit; }


//1.0 Revieve test information
	if ( ($TestID = get_post("TestID")) == "" ) { $TestID = super_cleantext($_GET['TestID']); }
	if ( ($TestTID = get_post("TestTID")) == "" ) { $TestTID = super_cleantext($_GET['TestTID']); }

	//If still nothing, assume we're woring with the currently enabled test
	if ( $TestID == "" ) { $TestID = sqlTest_getCurrentTest(); }

	//Clear data if bad happened
		unset($_SESSION['_startFLAG']);
		unset($_SESSION['TestTID']);

		$_SESSION['_startFLAG'] = 'unused';

//Process incoming actions ----------------------------------

$alert_text = "";				//Message User, if Any

//-----------------------------------------------------------

	print_start_to_navbar("Secchi Recertification");
	print_navbar_to_content();
	
		delimiter_start();

				//2.0 Show the user any test instances he or she has already taken 
				
				$numTaken = sqlTest_numTestsTakenByAccountTest($TestID,$_SESSION['AccountID']);
				if ( $numTaken > 0 )
				{

					//Count number of questions for the test
					$numQuestions = sqlTest_numQuestions($TestTID,$TestID,"");		

					//Get the instances of tests
					$result = sqlTest_getTestsTaken("",$TestID,$_SESSION['AccountID']);

					//display the instances
					table1_start("You've taken this test before");
					table1_header(Array("Date and Time/100%","Grade/0"));
					$row_count = 0;
					while($row = mysql_fetch_array($result))
					{
						//Determine how many haven't been graded (short answer)
						$numUngraded = sqlTest_numUngraded($row['ID'],$TestID,$_SESSION['AccountID']);
						//Determine status
						if ( $numUngraded > 0 )
						{
							$grade = "<font color='#999999'>Pending</font>";
						} else {
							//Determine how many correct						
							$numCorrect = sqlTest_numCorrect($row['ID'],$TestID,$_SESSION['AccountID']);

							$grade = round(100*$numCorrect/$numQuestions);
							if ( $grade < PassingGrade ) { $grade = "<font color='red'>$grade</font>%"; } else { $grade .= '%'; }
						}

						$line = Array($row['Date'],$grade);
						table1_row($line,$row_count);
						$row_count++;
					}
					table1_end();
					$testTaken = $row_count > 0;
					
					if ( $TestID ) {
						echo "<br /><center><form action ='take_test.php' method='post'>".form_hidden("TestID",$TestID).form_hidden("TestTID",$TestTID).form_submit("Take Test Again")."</form></center>";
					
					} else { echo "<br /><center>No test Currently Available</center>\n"; }
				} else { //2.1 If no tests have been taken, continue



					echo "<center>Complete the test and take a Secchi Disk reading.<br />Your most recent reading and test will be used to certify you.</center>";
					echo "<br /><center><form action='take_test.php' method='post'>".form_hidden("TestID",$TestID).form_hidden("TestTID",$TestTID).form_submit("Start Test")."</form></center>";
				} 

				//Secchi simulator lnk

				echo "<br /><br />\n";
				/*
							Lake_type:
							01 - ID
							02 - Name
							03 - Range_low
							04 - Range_high
							05 - Tolerance
							06 - Description
							07 - Color
							Reading:
							08 - Id
							09 - TestID
							10 - AccountId
							11 - Reading
							12 - Generated
							13 - Lake_type
							14 - Attempt
							Test:
							15 - Version
							16 - Current
							17 - Date
							*/
				$row_count = 0;
				$result = sqlTest_getReadingsLimit("",$_SESSION['AccountID'],$PAGE_reading*$numPerPage,$numPerPage);
				if ( mysql_fetch_array($result) ) {
					$result = sqlTest_getReadingsLimit("",$_SESSION['AccountID'],$PAGE_reading*$numPerPage,$numPerPage);
					table1_start("Secchi Readings Taken - Page ".($PAGE_reading+1)." of ".cap(ceil($numReadings/$numPerPage),1,100000));
						table1_header(Array("Lake Type/0","Generated Reading/0","Measured Reading/0","Tolerance/0","Range/0","Reading Passed?/0"));
						$lastid = -1;
						$row_count = 0;
						while($row = mysql_fetch_row($result))
						{
							if ( $row[16] == 1 ) {
								if ( $row[10] > $row[11] - $row[4] && $row[10] < $row[11] + $row[4] ) { $passed = "Yes"; } else { $passed = "<font color='red'>No</font>"; }
			
								$line = Array($row[1],padme($row[11])."m",padme($row[10])."m","&plusmn;".padme($row[4])."m",padme($row[2])."-".padme($row[3])."m",$passed);
								table1_row($line,$row_count);
								$row_count++;
								}
						}
		
					table1_end();
	
					//Secchi Paging
					echo "<a name='view2'></a>\n";
					page_controls("reading",$numPerPage,$numReadings,form_hidden("target",$target).form_hidden("Action","Tests Taken"));
				} else {
					if ( $testTaken ) {
						echo "<center><br />Your most recent reading and test will be used to certify you.  You have not taken a reading for your lake type yet.</center><br />";
					}
				}
				echo "<form action='disk.php' method='POST'>\n";
				echo "<center>\n";
				if ( $row_count > 0 || $testTaken ) { echo "<br />" . form_submit("Go to Disk Simulator"); }
				echo "</center></form>\n";

			delimit();
		
		delimiter_end();
	
	print_content_to_end();
?>