<?php
    include_once("libraries.php"); 

/*
Creator: Jacob Landry
Date(last updated): 7/11/07
Description: take_test page for secchi site
Documentation:
-- Process Login
    -recieves TestID($CurrentTestID) and TestTID($TestTID) from login page
-- Current Test?
    -returns to login page if no test($CurrentTestID) provided
-- Account ID
    -acquires Account ID($AccountID) as session variable
-- Last Attempt Value
    -recieves attempt($attempt) if any questions have been answered
-- Max allowed extra-attempts
    -maximum amount of extra attempts($extraattempts) can be set
-- Counter
    -if the counter($counter) is not previously set, it starts at 0
-- Start New Test Taken
    -This will set a new TestTID every time a test is started.
-- Current Question ID
    -recieves ID of last question answered($QuestionID) (from self)
-- Last Question Answered?
    -check if last question left blank($blank)
-- DEBUG Information
    -debugger
-- IF Multiple Choice
    -if last question was multi-choice ($QuestionType == 0)
        -store answer if attempt($attempt) is not 0 (if question has been answered at least once) and if $num is not too high (not too many stored in database (prevent use of back button to get correct answers))
            -if answer count($count) for last question is 2, counter incremented ($counter++) and attempt set to 0 ($attempt = 0)
            -no second chance for 2 answer mutli-choice questions
		-if $next true (when 2 answer multi choice question answered/hinted) increment counter($counter++) and attempt set to 0($attempt = 0) and set $next to false ($next = false)
        -if too many attempts($attempt > $extraattempts) and not blank (&& !$blank), increment counter to move on to next question
        -if question being tried a second time ($attempt != 0) set hint (set_hint in lib_taketestfunctions.php)
            -if only a 2 answer question, hint is set to specific value, otherwise set_hint used (lib_taketestfunctions.php)
        -if answered correctly ($row['Correct'] == 1) and $num is not too high (not too many stored in database (prevent use of back button to get correct answers)), store to database and increment counter($counter++) and reset attempts($attempt = 0)
-- Store Short Answer
    -if last question was short answer ($QuestionType == 1)
        -if answered (!$blank) and $num is not too high (not too many stored in database (prevent use of back button to get correct answers))store answer to database, increment counter($counter++) and reset attempts($attempt = 0)
-- Form Start
    -start of form, submits to itself(take_test.php)
        -set Question Type (get_qtype($CurrentTestID) in lib_taketestfunctions.php)
        -get info about questions in test from database
        -find question with index($row['Index']) equal to counter($counter)
        -set $Submit to 'Submit' (once we know there IS a question we want the end of the form to say Submit)
        -set question to $Question (use str_replace to insert <BR> where the user pushed 'return')
        -set image info to $ImageTitle and $ImageURL
        -use $QuestionID to match/find answers
        -if there is an image ($ImageID != 0), display it
        -if short answer ($QuestionType == 1), set hint to display (set_hint in lib_taketestfunctions.php)
        -if multi-choice and only two answers (no extra attempts, counted by count_up in lib_taketestfunctions.php), set hint to display (set-hint in lib_taketestfunctions.php)
        -start table
            -display question($Question) and hint($Hint)
        -get $QuestionType again to make sure its up-to-date (get_qtype in lib_taketestfunctions.php)
        -if multi-choice ($QuestionType == 0), set answer($Answer) and answerID($AnsID)
        -display answers (if not second try at 2 answer multi-choice (if is: show hint and don't allow answer))
            -if short answer ($QuestionType == 1) then display text box (no answer was set)
            -if multi choice (else) then display radio button and answer.  pass value of $AnsID back to self.
            -print answers in row
        -end table
-- Question Disabled?
    -check if question is disabled
        -when counter($counter) equals index (selects question) and the enabled field of database is 0 (question is disabled)
            -increment counter($counter++), reset attempts($attempt = 0), set answerID($AnsID = 1)(so that the question won't be set as unanswered)
            -send all needed information back to self, force submission of form
                -this causes question to be skipped and no information to be lost or written.
-- Test Complete?
    -if $Question was never set, means there are no questions left in test.
        -show 'test complete' text, set $complete to true, update database Test_taken so Completed field = 1
    -if $Question not set, will skip regular submission(Submit/Reset) and move on to Completed Test Submission
-- Submit/Reset
        -if $Question was set, normal submission occurs:
            -attempt incremented ($attempt++), all needed information sent back to self (take_test.php)
            -Submit sends user back to self (take_test.php)
-- Completed Test Submission
        -submit button = Continue, all info sent to disk.php, only $CurrentTestID and $TestTID used/needed by disk.php.  user sent to disk.php
-- Progress
    -Progress Bar
        -find number of questions($amount with count_up in lib_taketestfunctions.php) and set to $numquestions
        -since $counter starts at 0 and we want question status to start at 1, $thisq = $counter + 1
        -$thisq2 set to $counter
        -$percent is percent of questions completed.  set to $thisq2 (will equal amount of questions DONE not SEEN) divided by $numquestions, all multiplied by 100.
        -if $complete has been set to true, test is over, do not display question status
        -else, echo question number ($thisq) out of total($numquestions)
        -set up table to show where bar should be at 100% completion
        -set bar's width to $percent
*/

//Process Login
if ( ( $CurrentTestID = get_post('TestID') ) == "" ) 
    {
    $CurrentTestID = super_cleantext($_GET['TestID']);
    }
if ( ( $TestTID = get_post('TestTID') ) == "" ) 
    {
    $TestTID = super_cleantext($_GET['TestTID']);
    $counter = get_post('counter');
    if ($counter != 0) {
    $query = "SELECT `ID` FROM `Test_taken` WHERE `ID` IN (SELECT MAX(`ID`) FROM `Test_taken` WHERE `AccountID` = '$AccountID')";
    $result = mysql_get($query);
    $row = mysql_fetch_array($result);
    $TestTID = $row['ID'];
    }
    }
    
//Current Test?
if ($CurrentTestID == null)
    {
    header('Location: index.php' ); exit;
    }
    
//Account ID
check_level();
$AccountID = $_SESSION['AccountID'];

//Last attempt value
$attempt = get_post('attempt');

//Max allowed extra-attempts
$extraattempts = 1;

//Counter
$counter = get_post('counter');
if ($counter == "")
	{
	$counter = 0;
	}

//Start new Test-Taken
if ($TestTID == "" && ($counter == 0 || $counter == "") && ( $_SESSION['_startFLAG'] == "unused" || !isset($_SESSION['_startFLAG']) ))
    {
	$_SESSION['_startFLAG'] = "used";
	//delete incomplete tests for user
	$query = "DELETE FROM `Test_taken` WHERE `AccountID` = '$AccountID' AND `Completed` = '0'";
	$result = mysql_get($query);

    //Look up Account ID
    $query = "SELECT `ID` FROM `Account` WHERE `Login_key`='".$_SESSION['sekey']."'";
	$result = mysql_get($query);
	$temp = mysql_fetch_array($result);
	$AccID = $temp['ID']; 
	//Start Test Taken entry
	$query = "INSERT INTO `Test_taken` (`AccountID`,`TestID`,`Date`) VALUES('$AccID','$CurrentTestID','".date("Y-m-d H:i:s")."')";
	mysql_get($query); 
	//Get its ID back
	//$TestTID = sqlTest_newID('Test_taken');
	$query = "Select Max(`ID`) FROM `Test_taken` WHERE `TestID` = '$CurrentTestID'";
	$result = mysql_get($query);
	$row = mysql_fetch_array($result);
	$TestTID = $row[0];
    }

//Make sure TestTID is set
if ( !isset($TestTID) || $TestTID == 0 )
{
	header("show_tests.php?Problem=TestTID_was_missing_"); exit();
}








//Current Question ID
$QuestionID = get_post('QuestionID');

//Last Question Answered?
$answered = get_post('AnsID');
if ($answered == "")
    {
    $blank = true;
    }
    else
    {
    $blank = false;
    }
    
//DEBUG Information
if( false )
{
	$txt = "";
	foreach($_POST as $key=>$value) { $txt .= "$key:$value <br />"; }
	Alert("eh",$txt);
}
	print_start_to_navbar("Take Test");
	
	print_navbar_to_content();
	
		delimiter_start();

		delimit();

			
            //IF Multiple Choice
	$QuestionType = get_post("type");
            if ($QuestionType == 0)
            {
				$AnsID = get_post("AnsID");
				$query = "SELECT `Correct` FROM `Answer` WHERE `ID` = '$AnsID'";
            	$Correct = mysql_get($query);
				$row = mysql_fetch_array($Correct);
          		if ($row['Correct'] == 0)
                	{
					$Correct = $row['Correct'];
			
		if ($attempt != 0 && $TestTID != 0)
			{
                		$AnsID = get_post("AnsID");
                		$Storeans = get_answer($AnsID);
                		$num = find_attempt($QuestionID,$TestTID,$attempt);
						if (!$num) {
							$num=0;
							}
                		if (!$blank && $num < 1)
                		{
                			store_answer($QuestionID,$AccountID,$TestTID,$Storeans,$Correct,$attempt);
						}
						else {
							if (!$blank) {
								$cheater = 1;
							}
							}
		//Next
        $next = get_post('next');
        if ($next)
            {
            //increment counter
            $counter++;
            //reset attempts
            $attempt = 0;
			$next = false;
            }
            }
					if (($attempt > $extraattempts) && !$blank)
						{
						//increment counter
						$counter++;
						//reset attempts
						$attempt = 0;
						}
					if ($attempt != 0)
						{
                    //Set Hint For Multi-Choice
                    $count2 = get_post('count');
                    if ($count2 <= 2 && !$blank && $Correct == 0 && $attempt !=0)
                        {
                        $Hint = "<BR><font color='#FFCCCC'>Your answer was incorrect.</font>";
                        }
					else if ($cheater == 1) {
						$Hint = "<BR><font color='#FFCCCC'>Since you used the back button no answer was stored.  Please don't use the back button.  Answer the question and move on.</font>";
						}
                    else
                        {
                        $Hint = set_hint($CurrentTestID,$QuestionID,$counter,$blank,$QuestionType,$attempt);
                        }
                    if ($blank)
                        {
                        $attempt = $attempt - 1;
                        }
						}
					}
					
				//IF correct, store and increment counter
                if ($row['Correct'] == 1)
                	{
					$Correct = $row['Correct'];
                	//Store Multi choice
			        $QuestionType = get_post("type");
			        if ($QuestionType == 0)
			            {
                		$AnsID = get_post("AnsID");
                		$Storeans = get_answer($AnsID);
                		$num = find_attempt($QuestionID,$TestTID,$attempt);
                		if ($num < 1 && $TestTID != 0)
                		  {
                		  store_answer($QuestionID,$AccountID,$TestTID,$Storeans,$Correct,$attempt);
							}
					   //increment counter
					   $counter++;
					   //reset attempts
					   $attempt = 0;
			           }
                	} 
            }

			//Store Short Answer 
			$QuestionType = get_post("type");
			if ($QuestionType == 1)
			 {
                if (!$blank)
                    {
				    $Ans = get_post("AnsID");
				    $cleanAns = clean_shortanswer($Ans);
				    $num = find_attempt($QuestionID,$TestTID,$attempt);
				    if ($num < 1 && $TestTID != 0)
				        {
				        store_answer($QuestionID,$AccountID,$TestTID,$cleanAns,$Correct,$attempt);
						}
				    //increment counter
				    $counter++;
				    //reset attempts
				    $attempt = 0;
				    }
			}

			
			//Form Start
			echo '<form name="input" action="take_test.php" method="post">';
			
			$QuestionType = get_qtype($QuestionID);            

				//Get current question
					$query = "SELECT * FROM `Question` WHERE `TestID`='$CurrentTestID'";
					$question2 = mysql_get($query);
				
                    //Display Question
					while ($row = mysql_fetch_array($question2))
					{
					   if ($counter == $row['Index'] && $row['Enabled'] == 1)
					   {
						$Submit = "Submit";
						$Question = newlines($row['Question']);
						$ImageID = $row['ImageID'];
						$queryimage = "SELECT * FROM `Image` WHERE `ID`='$ImageID'";
						$resultimage = mysql_get($queryimage);
						$rowimage = mysql_fetch_array($resultimage);
						$ImageTitle = $rowimage['Title'];
						$ImageURL = $rowimage['Content'];
									        
				        //Get Answers
				        $QuestionID = $row['ID'];
				        $query = "SELECT * FROM `Answer` WHERE `QuestionID`='$QuestionID' ORDER BY `Index`";
				        $answer = mysql_get($query);

						// == Image Tag moved to after question ==

				        $QuestionType = get_qtype($QuestionID);
				        //Set Hint for Short Answer
				        if ($QuestionType == 1)
				            {
				            $Hint = set_hint($CurrentTestID,$QuestionID,$counter,$blank,$QuestionType,$attempt);
				            if ($blank)
				                {
				                $attempt = $attempt - 1;
				                }
				            }
$amount = count_up('Question','TestID',$CurrentTestID);
if ($amount != $counter) {
echo "<center><b><font face='Arial' style='font-size:15px'>Secchi Re-certification Test</font></b></center><br><br>"; }
				    table2_start("<font style='font-variant:none;'>$Question<BR>$Hint</font>");
					table1_header(Array("/0","/100%"));
					
					//Get Question Type
					   $QuestionType = get_qtype($QuestionID);
						
					$count = 0;
				        while($row2 = mysql_fetch_array($answer))
					   {
					   if ($QuestionType == 0)
					   {
						$Answer = $row2['Answer'];
						$AnsID = $row2['ID'];
						}                        
						
						//Display Answers
						$count2 = get_post('count');
        				if ($count2 <= 2 && !$blank && $Correct == 0 && $attempt !=0)
						{
						  $colstuff = '';
						  $Answer = '';
						  $Submit = 'Next';
						  $next = true;
						  echo '<input type="hidden" name="next" value='.$next.'>';
						}
						else if ($QuestionType == 1)
						{
						  $colstuff =  '<textarea cols="50" rows="4" name="AnsID" maxlength="1000"></textarea>';
						}
						else
						{
						  $colstuff =  '<input type="radio" name="AnsID" value='.$AnsID.'><BR>';
						}
						table2_row(Array($colstuff,$Answer),$count);
						$count++;
				    }
				table1_end();
                   			 }
				//Question Disabled?
				else
				{
				if ($counter == $row['Index'] && $row['Enabled'] == 0)
					{
					//increment counter
					$counter++;
					//reset attempts
					$attempt = 0;
					//question not unanswered
					$AnsID = 1;
					submitinfo($counter,$attempt,$CurrentTestID,$TestTID,$QuestionID,$QuestionType,$count);
					echo '<input type="hidden" name="AnsID" value='.$AnsID.'>';
					echo "<script>document.input.submit();</script>"; 
					}
				}
                }


                //Test Complete?
				if (!isset($Question))
					{
					echo '<BR><center>Test Complete! Please click continue to move on to the next part of your test.</center><BR>';
					$complete = true;
					$query = "UPDATE Test_taken SET Completed='1' WHERE ID='$TestTID'";
					$result = mysql_get($query)
                      or die("Query failed: ".mysql_error());
					$_SESSION['_startFLAG'] = "unused";
					}
				else
					{
					//Submit/Reset
					$attempt++;
					submitinfo($counter,$attempt,$CurrentTestID,$TestTID,$QuestionID,$QuestionType,$count);
					echo '<input type="submit" value='.$Submit.'>';
					}
				echo '</form>';

			//Show image (Moved here from above)
            delimit();

				if ($ImageID != 0 && $ImageURL != "")
				{
				echo "<center> <img src='images/$ImageURL' alt='$ImageTitle' /></center><BR>";
				}

			delimit();
				
				//Completed Test Submission
				if (!isset($Question))
				    {
				    echo '<form name="finish" action="disk.php" method="post">';
				    submitinfo($counter,$attempt,$CurrentTestID,$TestTID,$QuestionID,$QuestionType,$count);
				    echo '<center><input type="submit" value="Continue"></center>';
				    echo '</form>';
				    }
				    
                    //Progress
				    echo "<BR><BR><BR>";
				    $amount = count_up('Question','TestID',$CurrentTestID);
				    $numquestions = $amount;
				    $thisq = $counter + 1;
				    $thisq2 = $counter;
				    if ($complete)
				       {
				        echo "";
				        }
				    else
				        {
				        echo "<center> Question ".$thisq." out of ".$numquestions."</center>";
				        }
				    $percent = ($thisq2/$numquestions) * 100;
				    echo "<table border='1' bordercolor='#000000' width='100%'>";
				    echo "<tr><td><img src='interface/progressbar.gif' alt='progress' height='15'width='".$percent."%'></td></tr>";
				    echo "</table>";


		delimiter_end();

	print_content_to_end();
	
?>