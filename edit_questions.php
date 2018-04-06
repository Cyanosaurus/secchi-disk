<?php
/* edit_questions.php
   Minimum Access Level: Admin(2)
   Date: 5/21/07
   Created by: Jasper Turcotte
   Modifiers:
		<name> on <date>
		Jacob Landry on 5/27/08 (added a feature to adjust default answer number)
   Purpose: Allows admin to add/modify/remove/grade questions, as well as review old test questions and see how people answered them

   Incoming variables:
	* TestID
	* Question Data
	* Modification commands

   Code Map:
	-1.0 Get and Process incoming data
	-2.0 Process Lock_flag (whether the test is editable or not)
    -3.0 Grade an ungraded test question
    -4.0 Change questions on Prev/Next
    -5.0 Enable or Disable the question
    -6.0 Move Question
	-7.0 Delete Question
	-8.0 Add Question
    -9.0 Save Changes
	-10.0 Preliminary Processing
		-10.1 Display Question Attributes
		-10.2 Build Image list
		-10.3 Primary Control Form
	-11.0 New Question (Also fetches old incomplete info)
	-12.0 Current Question
	-13.0 13.0 Show test results for this question, if any.

   Main Outgoing Variables 
	-> self
		* TestID
    	* Grading commands
    	* Question Data
		* Modification commands

*/
	include_once("libraries.php");

//Make sure only admins can access ths page
if ( $_SESSION['level'] < 2 ) { header("Location: index.php"); exit; }

page_action("question");
$numPerPage= 5;

//1.0 Get and Process incoming data

$action = get_post("action");
$QuestionID = get_post("QuestionID");
$numAnswers = get_post('num_answers');
$currentTestID = get_post("TestID");
$ImageID = get_post("ImageID");
$QuestionType = get_post('Type');
$GAction = get_post("grade_action");

//If no (valid) test is selected, redirect to manager
$query = "SELECT `ID`,`Version`,`Current` FROM `Test` WHERE `ID`='$currentTestID'";
$result = mysql_get($query);
$row = mysql_fetch_array($result);
if ( $row == false ) { header("Location: test_manager.php"); exit;}

$currentTestName = "Test Version " . $row['Version'];


//Determine flags for editing
$edit_carry_FLAG = get_post('edit_carry') == "yes"; //Makes sure we go through "Edit" again
$edit_question_FLAG = ($action == "Save Changes" || $action == "Move Question" || $action == "Swap Questions" || $action == "Change Question" || $action == "Enable" || $action == "Disable" || $action == "Prev" || $action == "Next" || $GAction == 'Correct' || $GAction == 'INcorrect' || ($action == "Update Image" && $edit_carry_FLAG) || ($action == "Change Number of Answers" && $edit_carry_FLAG) || ($action == "Change Question Type" && $edit_carry_FLAG));

//See whether to reset data fields or not (on edit)
if ( $action == "Change Question" || $action == "Prev" || $action == "Next" || $GAction == 'Correct' || $GAction == 'INcorrect' || $action == "Change Test"  ) { $newData_FLAG = true; } else { $newData_FLAG = false; }


//2.0 Process Lock_flag (whether the test is editable or not)
	//See if it should be locked or not (taken or current)
$lock_FLAG = isTestLocked_Taken($currentTestID) || $row['Current']=='1';	//Disallow any modification of uneditable tests

//Update QuestionId if locked, so look up first question
if ( $lock_FLAG && $QuestionID == "")
{
	//Get the ID of the first question
	$query = "SELECT `ID` FROM `Question` WHERE `TestID`='$currentTestID' ORDER BY `Index` ASC";
	$result = mysql_get($query);
	$row = mysql_fetch_array($result);
	if ( $row != false ) { 
		$QuestionID = $row['ID'];
		$edit_question_FLAG = true;
		$edit_carry_FLAG = true;
	}
}


//3.0 Grade an ungraded test question
if ( $GAction == "Correct" || $GAction == "INcorrect" )
{
	$AID = get_post("AID");
	if ( $GAction == "INcorrect" ) { $correct = '0'; } else { $correct = '1'; }	

	$query = "UPDATE `Account_answer` SET `Corrected`='1',`Correct`='$correct' WHERE `ID`='$AID'";
	$result = mysql_get($query);
	if (!$result ) { Alert("Uh oh!","$query"); } else { $alert_text .= "Item graded.<br />"; }
}

//4.0 Change questions on Prev/Next
	if ( $action == "Next" || $action == "Prev" ) {
		//Get the current index
		$query = "SELECT `Index` FROM `Question` WHERE `ID`='$QuestionID'";
		$result = mysql_get($query);
		$row = mysql_fetch_array($result);
		if ( $row == false ) { $alert_text .= "That question does not exist"; }
		else {
			if ( $action == "Next" )
			{
				$query = "SELECT `ID` FROM `Question` WHERE `TestID`='$currentTestID' AND `Index`='".($row['Index']+1)."'";
				$result = mysql_get($query);
				$row = mysql_fetch_array($result);
				if ( $row != false ) { $QuestionID = $row['ID']; }
			} else {
				$query = "SELECT `ID` FROM `Question` WHERE `TestID`='$currentTestID' AND `Index`='".($row['Index']-1)."'";
				$result = mysql_get($query);
				$row = mysql_fetch_array($result);
				if ( $row != false ) { $QuestionID = $row['ID']; }
			}
		}
	}

//5.0 Enable or Disable the question
	if ( $action == "Enable" || $action == "Disable" ) {
		//Get the current index
		$query = "SELECT `Enabled` FROM `Question` WHERE `ID`='$QuestionID'";
		$result = mysql_get($query);
		$row = mysql_fetch_array($result);
		if ( $row == false ) { $alert_text .= "That question does not exist"; }
		else {
			if ( $action == "Disable" )
			{
				$query = "UPDATE `Question` SET `Enabled`='0' WHERE `ID`='$QuestionID'";
				$result = mysql_get($query);
				 $alert_text.= "Question is now disabled<br />"; 
			} else {
				$query = "UPDATE `Question` SET `Enabled`='1' WHERE `ID`='$QuestionID'";
				$result = mysql_get($query);
				$alert_text.= "Question is now enabled<br />";
			}
		}
	}

//6.0 Move Question
if ( $action == "Move Question" )
{
	$moveID = get_post("MoveQuestionPosition");
	//Get this question info
	$query = sqlTest_getQuestionByTest($currentTestID);
	$result = mysql_get($query);
	$row_qThis = mysql_fetch_array($result);

		//Move Questions with higher index [at old position] back by 1
		if ( $moveID > $row_qThis['Index'] )
		{
			$query = "UPDATE `Question` SET `Index`=`Index`-1 WHERE `TestID`='$currentTestID' AND `Index`>='".$row_qThis['Index']."' AND `Index`<='$moveID'";
		} else {
			$query = "UPDATE `Question` SET `Index`=`Index`+1 WHERE `TestID`='$currentTestID' AND `Index`>='$moveID' AND `Index`<='".$row_qThis['Index']."'";
		}
		$result = mysql_get($query);

	//Move This to it's new position
	$query = "UPDATE `Question` SET `Index`='$moveID' WHERE `TestID`='$currentTestID' AND `ID`='$QuestionID'";
	$result = mysql_get($query);

	$alert_text.="Question Moved";
}

//7.0 Delete Question
if ( $action == "Delete Question" )
{
	//Shift other indexes
		//Get this question info
		$query = sqlTest_getQuestionbyID($QuestionID);

		//$result = mysql_get($query);
		$row_qThis = mysql_fetch_array($query);

		$query = "UPDATE `Question` SET `Index`=`Index`-1 WHERE `TestID`='$currentTestID' AND `Index`>='".$row_qThis['Index']."'";
		//echo "$query<br />";
        $result = mysql_get($query);

	//Delete Answer entries
	$query = "DELETE FROM `Answer` WHERE `QuestionID`='$QuestionID'";
	$result = mysql_get($query);		

	//Remove any `Question` and `Reading` entries with the TestID
	$query = "DELETE FROM `Question` WHERE `ID`='$QuestionID'";
	$result = mysql_get($query);	


	$alert_text .= "Question Removed";
}


//8.0 Add Question
if ( $action == "Add Question" )
{
	//First, check and see if the test is editable

	if ( $lock_FLAG )
	{
		$alert_text.="This test cannot be modified.  It is either currently enabled, or an older and finished test.";
	} else {

		$Question = get_post("question");
		$ShortAnswer = get_post("short");
		$Hint = get_post("hint");
		
		//Figure out the next question index
		$query = "SELECT `Index` FROM `Question` WHERE `TestID`='$currentTestID' ORDER BY `Index` DESC";
		$results = mysql_get($query);
		$row = mysql_fetch_array($results);
		if ( $row == false ) { $Index = 0; }
		else { $Index = $row['Index']+1; }
		
		//Count how many answers exist from last pass
		$count = 0;			
		if ( $QuestionType != 1 )
		{
			//Get correct radiobox
			$CorrectIndex = get_post("radio");
			//Count Answers
			for($i = 0; $i < $numAnswers; $i++)
			{
				if ( get_post("A$i") != "" ) { $count++; }
			}
		}

		//Validate data
		$continue = true;
		$out_text = "";
		if ( $Question == "" ) { $continue = false; $out_text .= "You need to add a question.<br />"; }
		if ( $CorrectIndex == -1 && $CorrectIndex > $count) { $continue = false; $out_text .= "You did not select a correct answer.<br />"; }
		if ( $ShortAnswer == "" && $QuestionType == 1) { $continue = false; $out_text .= "You need to add an answer.<br />"; }
		if ( $QuestionType != 1 && $count != $numAnswers) { $continue = false; $out_text .= "You did not fill up the available answer slots!<br />"; }
		
		//If all stipulations are met, continue adding it
		if( $continue )
		{
			$query = "INSERT INTO `Question` (`TestID`,`Question`,`Hint`,`Type`,`ImageID`,`Index`,`Enabled`) VALUES ('$currentTestID','$Question','$Hint','$QuestionType','$ImageID','$Index','1')";
			mysql_get($query);

			//Get the ID back
			$QuestionIDnew = sqlTest_newID('Question');;	
			$QuestionID = $QuestionIDnew;	
				
			if ( $QuestionType == 0 ) {
				for($i = 0; $i < $numAnswers; $i++)
				{
					if ( get_post("A$i") != "" )
					{
						if ($i == $CorrectIndex) { $Correct = "1"; } else { $Correct = "0"; }
						$query = "INSERT INTO `Answer` (`QuestionID`,`Answer`,`Correct`,`Index`) VALUES ('$QuestionIDnew',\"".get_post("A$i")."\",'$Correct','$i')";
						//echo "$query<br />";
						mysql_get($query);

					}
				}
			} else {
					$query = "INSERT INTO `Answer` (`QuestionID`,`Answer`,`Correct`,`Index`) VALUES ('$QuestionIDnew',\"$ShortAnswer\",'0','0')";
					//echo $query . "<br />";
					mysql_get($query);
			}
			$alert_text .= "Question Added";
			$newData_FLAG = true;

		} else { $alert_text .= $out_text; }
	}
}


//9.0 Save Changes
if ( $action == "Save Changes" || ($edit_question_FLAG && !$newData_FLAG))
{
	//First, check and see if the test is editable


	if ( $lock_FLAG )
	{
		$alert_text .= "This test cannot be modified.  It is either currently enabled, or an older and finished test.";
	} else {

		$Question = get_post("question");
		$ShortAnswer = get_post("short");
		$Hint = get_post("hint");
		
		//Figure out the next question index
		$query = "SELECT `Index`,`Enabled` FROM `Question` WHERE `TestID`='$currentTestID' AND `ID`='$QuestionID' ORDER BY `Index` DESC";
		$results = mysql_get($query);
		$row = mysql_fetch_array($results);
		if ( ($Index = $row['Index'])=="") { $Index=0; }
		if ( ($Enabled = $row['Enabled'])=="") { $Enabled=0; }

		if ( $QuestionType != 1 )
		{
			//Get correct radiobox
			$CorrectIndex = -1;
			
			$CorrectIndex = get_post("radio");
	
			//Count Answers
			$count = 0;
			for($i = 0; $i < $numAnswers; $i++)
			{
				if ( get_post("A$i") != "" ) { $count++; }
			}
	
		}
		$continue = true;
		$out_text = "";

		if ( $Question == "" ) { $continue = false; $out_text .= "You need to add a question.<br />"; }
		if ( $CorrectIndex == -1 || $CorrectIndex > $count) { $continue = false; $out_text .= "You need to select a correct answer.<br />"; }
		if ( $ShortAnswer == "" && $QuestionType == 1) { $continue = false; $out_text .= "You need to add an answer.<br />"; }
		if ( $QuestionType != 1 && $count != $numAnswers) { $continue = false; $out_text .= "You need to fill up the available answer slots!<br />"; }
		
		//If all stipulations are met, continue adding it
		if( $continue )
		{

			//First, Remove old entry
				//Delete Answer entries
				$query = "DELETE FROM `Answer` WHERE `QuestionID`='$QuestionID'";
				$result = mysql_get($query);		
			
				//Remove any `Question` and `Reading` entries with the TestID
				$query = "DELETE FROM `Question` WHERE `ID`='$QuestionID'";
				$result = mysql_get($query);	

			$query = "INSERT INTO `Question` (`TestID`,`Question`,`Hint`,`Type`,`ImageID`,`Index`,`Enabled`) VALUES ('$currentTestID','$Question','$Hint','$QuestionType','$ImageID','$Index','$Enabled')";
			mysql_get($query);


			//Get the ID back
			$query = "SELECT `ID` FROM `Question` WHERE `TestID`='$currentTestID' AND `Index`='$Index' AND `Hint`='$Hint' ORDER BY `ID` DESC";
			$results = mysql_get($query);
			$row = mysql_fetch_array($results);
			$QuestionIDnew = $row['ID'];	
			$QuestionID = $QuestionIDnew;	
				
			if ( $QuestionType == 0 ) {

				for($i = 0; $i < $numAnswers; $i++)
				{
					if ( get_post("A$i") != "" )
					{
						if ($i == $CorrectIndex) { $Correct = "1"; } else { $Correct = "0"; }
						$query = "INSERT INTO `Answer` (`QuestionID`,`Answer`,`Correct`,`Index`) VALUES ('$QuestionIDnew',\"".get_post("A$i")."\",'$Correct','$i')";
						mysql_get($query);

					}
				}
			} else {

					$query = "INSERT INTO `Answer` (`QuestionID`,`Answer`,`Correct`,`Index`) VALUES ('$QuestionIDnew',\"$ShortAnswer\",'0','0')";
					mysql_get($query);
			}
			if ( $action == "Save Changes" ) { $alert_text .= "Question Saved"; }
			$newData_FLAG = true;

		} else { $alert_text .= $out_text; }
	}
}

// ------------------------------------------------------

	//10.0 Preliminary Processing
	print_start_to_navbar("Edit Questions");
	print_navbar_to_content();
	
		delimiter_start();
		
// =====================================================================================================================================
// =====================================================================================================================================

			if ( $alert_text != "" ) { Alert("Notice",$alert_text); }
			echo "<center><h2 style='font-size:20px;'>$currentTestName</h2></center>\n";
		delimit();

			//10.1 Display Question Attributes
				//Build Test List
				$query = "SELECT * FROM `Test` ORDER BY `ID` ASC";
				$result = mysql_get($query);
				
				$tests = form_hidden("TestID",get_post("TestID"));


				//Figure Question Type
				if ( $QuestionID != "" && $action != "Edit List" )
				{
					$query = "SELECT `Type` FROM `Question` WHERE `ID`='$QuestionID'";
					$result = mysql_get($query);
					$row = mysql_fetch_array($result);
					//Determine Question Type List
					if ( ($QuestionType = get_post("Type")) == "") { $QuestionType = $row['Type']; }
				}


				//Build Question List

				//Get current question
				if ( !$lock_FLAG )
				{
					$namelist = Array("[New Question]");
					$valuelist = Array("");
				}

				$namelist_swap = Array();
				$valuelist_swap = Array();

				//Build question list if test is selected
				if ( $currentTestID != "") { 
					$query = "SELECT * FROM `Question` WHERE `TestID`='$currentTestID' ORDER BY `Index` ASC";
					$result = mysql_get($query);
					$QEnabled_FLAG = true;	//Initially assume current page's question is enabled by default
					while($row = mysql_fetch_array($result))
					{
						if ( strlen($row['Question']) > 30 ) { $Question = substr($row['Question'],0,30) . "..."; } else { $Question = $row['Question']; }
						$namelist[] = "Q" . ($row['Index']+1) . " - " . $Question;
						$valuelist[] = $row['ID'];

						$namelist_swap[] = "Q" . ($row['Index']+1) . " - " . $Question;
						$valuelist_swap[] = $row['ID'];
						if ( $row['ID'] == $QuestionID ) {
						 $QEnable_FLAG = $row['Enabled']; 
						 if ( $row['Type'] == 1 ) { $numPerPage = 5; } else { $numPerPage = 10; }
						}
					}
				}

				if ( !$edit_question_FLAG ) { $question_num1 = form_select("QuestionID",$namelist,$valuelist,""); }
				else { $question_num1 = form_select("QuestionID",$namelist,$valuelist,$QuestionID); }

				if ( $edit_question_FLAG ) { 
					$question_num2 = form_select("SwapQuestionID",$namelist_swap,$valuelist_swap,"");

					//Build Position List
					$namelist = Array();
					$valuelist = Array();
					$query = "SELECT * FROM `Question` WHERE `TestID`='$currentTestID' ORDER BY `Index` ASC";
					$result = mysql_get($query);
					$count = 0;

					$curr_index = -1;

					while ( $row = mysql_fetch_array($result) )
					{
						if ( $QuestionID != $row['ID'] ) { 
							$namelist[] = $count + 1; 
							$valuelist[] = $count;
						} else { $curr_index = $count+1; }
						$count++;
					}
					$num_questions = $count;
					$question_num3 = form_select("MoveQuestionPosition",$namelist,$valuelist,"");

				} else { $question_num2 = ""; $question_num3 = ""; }
	
				//10.2 Build Image list	
				//Create thumbnail image tag, if any
					//Look up question data
					$query = "SELECT * FROM `Question` WHERE `ID`='$QuestionID'";
					$result = mysql_get($query);
					$row_question = mysql_fetch_array($result);
					$answer_list = Array();
					if ( ($ImageID = get_post("ImageID")) == "" || $newData_FLAG) { $ImageID = $row_question['ImageID']; }

				$namelist_img = Array("[No Image]");
				$valuelist_img = Array("");
				$thumbnail = ""; 

				$query = "SELECT * FROM `Image` ORDER BY `Title` ASC";
				$result = mysql_get($query);
				while($row = mysql_fetch_array($result))
				{
					if ( strlen($row['Title']) > 20 ) { $Title = substr($row['Title'],0,20) . "..."; } else { $Title = $row['Title']; }
					$namelist_img[] = $Title;
					$valuelist_img[] = $row['ID'];
					if ( $row['ID'] == $ImageID && $ImageID != "") { $thumbnail = $row['Content']; }
				}

				if ( $thumbnail != "" )
				{
					$thumbnail_display = "<img src='thumbs/$thumbnail' border='3' valign='bottom' title=\"".$row['Title']."\" alt=\"".$row['Title']."\"/>";
				} else { $thumbnail_display = ""; }



				//10.3 Primary Control Form

				//Next/Prev buttons
				if ( $QuestionID != "" ) {
					if ( $curr_index > 1 ) { $prevbutton = form_submit_name("Prev","action"); } else { $prevbutton = "<input type='submit' value='Prev' disabled='disabled' />"; }
					if ( $curr_index < $num_questions ) { $nextbutton = form_submit_name("Next","action"); } else { $nextbutton = "<input type='submit' value='Next' disabled='disabled' />"; }
				} else { $nextbutton = ""; $prevbutton = ""; }

				echo "<form action='' method='POST'>" . form_hidden("TestID","$currentTestID") ."\n";
				echo "<table class='data'>\n";
				echo "<tr><td align='left'></td><td align='left'>$tests</td><td></td></tr>\n";
				echo "<tr><td align='left'>Question Number: </td><td align='left'>$question_num1</td><td>".form_submit_name("Change Question","action")." $prevbutton $nextbutton</td></tr>\n";
					if ( !$lock_FLAG && $QuestionID != "" && $action != "Delete Question" ) 
					{ 
						echo "<tr><td align='left'><i>... Move to Position: </i></td><td align='left'>$question_num3</td><td>";
						echo form_submit_name("Move Question","action");
						echo "</td></tr>\n";
							if ( $QEnable_FLAG == '1' )
							{
								$enableAction = form_submit_name("Disable","action");
							} else { $enableAction = form_submit_name("Enable","action"); }

						echo "<tr><td align='left'><i>Question Enable: </i></td><td></td><td>".$enableAction."</td><td>";
						echo "</td></tr>\n";
					}
				echo "</table>\n";
				echo "<br />\n";

				//Make sure we keep paging information on edit
				echo form_hidden("page_A",get_post("page_A"));

		delimit();

			//11.0 New Question (Also fetches old incomplete info)
			//========================================================================================================================
			if ( !$edit_question_FLAG || $action == "Change Test" || $QuestionID == "")
			{	
	
				//New Question Data Gathering

				if ( $numAnswers == "" ) { $numAnswers = 5; }
				$numQuestionList_list = Array(2,3,4,5,6,7,8,9,10);
				$numQuestionList = form_select("num_answers",$numQuestionList_list,$numQuestionList_list,$numAnswers);
 
				//Question Type
				$namelist = Array("Multiple Choice","Short Answer");
				$valuelist = Array(0,1);
				
				$question_type = form_select("Type",$namelist,$valuelist,$QuestionType);

				//Image Selector
				$imagelist = form_select("ImageID",$namelist_img,$valuelist_img,$ImageID);

				//Figure out which 'correct' radiobox to enable
				$radioINDEX = get_post("radio");

				//Question & Hint texts
				if ( !$newData_FLAG ) 
				{ 
					$Question = get_post("question");
					$Hint = get_post("hint");
				} else { $Question = ""; $Hint = ""; }

				echo "<table class='data'>";
					echo "<tr valign='bottom'><td>\n";
						echo "<table width='100%' height='100%'>\n";
							if ( !$lock_FLAG ) { 
								echo "<tr><td align='left'>Question: </td></tr><tr class='data_altrow'><td align='left'><textarea cols='80' maxlength='1000' rows='5' name='question' >$Question</textarea></td></tr>\n";
								echo "<tr><td align='left'>Hint (optional): </td></tr><tr class='data_altrow'><td align='left'><input type='text' maxlength='1000' size='80' name='hint' value=\"$Hint\"/></td></tr>\n";
							} else  { 
								echo "<tr><td align='left'>Question: </td></tr><tr class='data_altrow'><td align='left'><textarea cols='80' maxlength='1000' rows='5' disabled='disabled' name='question' >$Question</textarea></td></tr>\n";
								echo "<tr><td align='left'>Hint (optional): </td></tr><tr class='data_altrow'><td align='left'><input type='text' maxlength='1000' disabled='disabled' size='80' name='hint' value=\"$Hint\"/></td></tr>\n";
							}
						echo "</table>\n";
					echo "</td></tr>\n";
					echo "<tr><td>\n";
						echo "<table width='100%' height='100%'>\n";
							echo "<tr><td width='100%'>\n";
								echo "<table width='100%' height='100%'>\n";
									echo "<tr><td align='left'>Question Type: </td></tr><tr><td align='left'>$question_type ";
										if ( !$lock_FLAG ) { echo form_submit_name("Change Question Type","action"); }
									echo "</td></tr>\n";
									if ( $QuestionType != 1 ) { echo "<tr><td align='left'>Number of Answers: </td></tr><tr><td align='left'>$numQuestionList "; }
										if ( !$lock_FLAG && $QuestionType != 1 ) { echo form_submit_name("Change Number of Answers","action"); }
									if ( $QuestionType != 1 ) { echo "</td></tr>\n"; }
									echo "<tr><td align='left'>Use Image:</td></tr><tr><td align='left'>$imagelist ";
										if ( !$lock_FLAG) { echo form_submit_name("Update Image","action"); }
									echo "</td></tr>\n";
								echo "</table>\n";
							echo "</td><td width='0'>$thumbnail_display</td></tr>";
						echo "</table>\n";
				echo "</table><br />\n";

				table1_start("Answer List");
				if ( $QuestionType == 1 ) 
				{	//Short Answer
					//Make Sure we remember the MC questions in case we change our collective minds
					if ( get_post("num_answers") != "" ) { echo form_hidden("num_answers",$numAnswers); }
					if ( get_post("radio") != "" ) { echo form_hidden("radio",$radioINDEX);	}				
					for($i = 0; $i < 10; $i++)
					{
						if ( !$newData_FLAG ) { echo form_hidden("A$i",get_post("A$i")); }			
					}

					table1_header(Array("Answer (Grader's hidden notes)/100%",""));
					if ( !$lock_FLAG ) { 
						$Question_Question_proc = "<textarea cols='80' maxlength='1000' rows='8' name='short' >";
						if (  !$newData_FLAG ) { $Question_Question_proc .= get_post("short"); }
						$Question_Question_proc .= "</textarea>";
					 }
					else { 
						$Question_Question_proc = "<textarea cols='80' maxlength='1000' disabled='disabled' rows='8' name='short' >";
						if (  !$newData_FLAG ) { $Question_Question_proc .= get_post("short"); }
						$Question_Question_proc .= "</textarea>";
					 }				

					$row_array = Array($Question_Question_proc,"");
					table1_row($row_array,0);

				} else { //Multiple Choice
					//Make Sure we remember the short answer
					echo form_hidden("short",get_post("short"));

					table1_header(Array("Index/0","Correct/0","Answer/100%"));
					$ind = "A";
					if ( $numAnswers < 2 ) { $numAnswers = 2; }
					for($i = 0; $i < $numAnswers; $i++)
					{

						//Correct Radiobox
						$Question_Correct_proc = "<input type='radio' name='radio' value='$i'";
						if ( !$newData_FLAG && get_post("radio") == $i ) { $Question_Correct_proc .= " checked='checked'"; }
						if ( $lock_FLAG ) { $Question_Correct_proc .= " disabled='disabled'"; }
						$Question_Correct_proc .= "/>";
					
						if ( !$newData_FLAG ) { $Answer = get_post("A$i"); } else { $Answer = ""; }

						//Automatically guess true/false question
						if ( ($i == 0 || $i == 1) && $numAnswers == 2 )
						{	//Preset True/False if appropriate
							if ( $i == 0 ) { $truefalse = "True"; } else { $truefalse = "False"; }
							if ( get_post("Q0") == "" && get_post("Q1") == "") 
							{ 
								if ( !$lock_FLAG ) { $Question_Question_proc = "<input type='text' size='80' name='A$i' value=\"$truefalse\"/>"; }
								else { $Question_Question_proc = "<input type='text' size='80' name='A$i' disabled='disabled' value=\"$truefalse\"/>"; }
							}
							else {
								if ( !$lock_FLAG ) { $Question_Question_proc = "<input type='text' size='80' name='A$i' value=\$Answer\"/>"; }
								else { $Question_Question_proc = "<input type='text' size='80' name='A$i' disabled='disabled' value=\"$Answer\"/>"; } 
							}

						}
						else {
							if ( !$lock_FLAG ) { $Question_Question_proc = "<input type='text' size='80' name='A$i' value=\"$Answer\"/>"; }
							else { $Question_Question_proc = "<input type='text' size='80' name='A$i' disabled='disabled' value=\"$Answer\"/>"; }
						}						

						$row_array = Array($ind, $Question_Correct_proc, $Question_Question_proc);
						table1_row($row_array,$i);

						$ind++;
					}
					//Rember other MC questions, in case we resize
					for($i = $numAnswers; $i <= 10; $i++)
					{
						if ( !$newData_FLAG ) { echo form_hidden("A$i",get_post("A$i")); }				
					}
				}
				table1_end(); 
				if ( !$lock_FLAG ) { echo "<br />" . form_submit_name("Add Question","action") . "\n"; }
				echo "</form>\n";

			}

			//12.0 Current Question
			//========================================================================================================================
			else 
			{
				echo form_hidden("edit_carry","yes");

				if ( $row_question != false ) 
				{
					if ($newData_FLAG || ($Question = get_post("question")) == "") { $Question = $row_question['Question']; }
					if ($newData_FLAG || ($Hint = get_post("Hint")) == "") { $Hint = $row_question['Hint']; }
					//Look up Answer information 
					$query = "SELECT * FROM `Answer` WHERE `QuestionID`='$QuestionID' ORDER BY `Index` ASC";
					$result = mysql_get($query);

					//Figure out which 'correct' radiobox to enable
					$radioINDEX_db = -1;
					
					$count = 0;
					while($row_answer = mysql_fetch_array($result)) 
					{
						$answer_list[] = $row_answer; 
						if ( $row_answer['Correct'] == 1 ) { $radioINDEX_db = $count; }
						$count++;
					}
					if ( $radioINDEX == "" ) { $radioINDEX = $radioINDEX_db; }

					if ($action == "Change Question" || $action == "Prev" || $action == "Next" || ($QuestionType = get_post("Type")) == "") { $QuestionType = $row_question['Type']; }
				} else {
					$Question = "";
					$Hint = "";
					$QuestionType = 0;
				}


				if ( !$edit_question_FLAG ) { $imagelist = form_select("ImageID",$namelist_img,$valuelist_img,""); }
				else { $imagelist = form_select("ImageID",$namelist_img,$valuelist_img,$ImageID); }


				//Question & Hint texts

				//Question Type
				$namelist = Array("Multiple Choice","Short Answer");
				$valuelist = Array(0,1);
				
				$question_type = form_select("Type",$namelist,$valuelist,$QuestionType);

					//New Question Data Gathering
					if (!$newData_FLAG && !$lock_FLAG) { $numAnswers = get_post("num_answers"); } else { $numAnswers = count($answer_list); }

					
					$numQuestionList = "<select name='num_answers'>";
						if($numAnswers == '2') {
							$selected = "SELECTED";
						}
						else {
							$selected = "";
						}
						$numQuestionList .= "<option value='2' ".$selected.">2</option>";
						if($numAnswers == '3') {
							$selected = "SELECTED";
						}
						else {
							$selected = "";
						}
						$numQuestionList .= "<option value='3' ".$selected.">3</option>";
						if($numAnswers == '4') {
							$selected = "SELECTED";
						}
						else {
							$selected = "";
						}
						$numQuestionList .= "<option value='4' ".$selected.">4</option>";
						if($numAnswers == '5') {
							$selected = "SELECTED";
						}
						else {
							$selected = "";
						}
						$numQuestionList .= "<option value='5' ".$selected.">5</option>";
						if($numAnswers == '6') {
							$selected = "SELECTED";
						}
						else {
							$selected = "";
						}
						$numQuestionList .= "<option value='6' ".$selected.">6</option>";
						if($numAnswers == '7') {
							$selected = "SELECTED";
						}
						else {
							$selected = "";
						}
						$numQuestionList .= "<option value='7' ".$selected.">7</option>";
						if($numAnswers == '8') {
							$selected = "SELECTED";
						}
						else {
							$selected = "";
						}
						$numQuestionList .= "<option value='8' ".$selected.">8</option>";
						if($numAnswers == '9') {
							$selected = "SELECTED";
						}
						else {
							$selected = "";
						}
						$numQuestionList .= "<option value='9' ".$selected.">9</option>";
						if($numAnswers == '10') {
							$selected = "SELECTED";
						}
						else {
							$selected = "";
						}
						$numQuestionList .= "<option value='10' ".$selected.">10</option>
					</select>\n";

				echo "<table class='data'>";
					echo "<tr valign='bottom'><td>\n";
						echo "<table width='100%' height='100%'>\n";
							if ( !$lock_FLAG ) { 
								echo "<tr><td align='left'>Question: </td></tr><tr class='data_altrow'><td align='left'><textarea cols='80' maxlength='1000' rows='5' name='question' >$Question</textarea></td></tr>\n";
								echo "<tr><td align='left'>Hint (optional): </td></tr><tr class='data_altrow'><td align='left'><input type='text' maxlength='1000' size='80' name='hint' value=\"$Hint\"/></td></tr>\n";
							} else  { 
								echo "<tr><td align='left'>Question: </td></tr><tr class='data_altrow'><td align='left'><textarea cols='80' maxlength='1000' rows='5' disabled='disabled' name='question' >$Question</textarea></td></tr>\n";
								echo "<tr><td align='left'>Hint (optional): </td></tr><tr class='data_altrow'><td align='left'><input type='text' maxlength='1000' disabled='disabled' size='80' name='hint' value=\"$Hint\"/></td></tr>\n";
							}
						echo "</table>\n";
					echo "</td></tr>\n";
					echo "<tr><td>\n";
						if ( !$lock_FLAG )
						{
						echo "<table width='100%' height='100%'>\n";
							echo "<tr><td width='100%'>\n";
								echo "<table width='100%' height='100%'>\n";
									echo "<tr><td align='left'>Question Type: </td></tr><tr><td align='left'>$question_type ";
										echo form_submit_name("Change Question Type","action");
									echo "</td></tr>\n";
									if ( $QuestionType != 1 ) { echo "<tr><td align='left'>Number of Questions: </td></tr><tr><td align='left'>$numQuestionList "; }
										if ( $QuestionType != 1 ) { echo form_submit_name("Change Number of Answers","action"); }
									if ( $QuestionType != 1 ) { echo "</td></tr>\n"; }
									echo "<tr><td align='left'>Use Image:</td></tr><tr><td align='left'>$imagelist ";
										echo form_submit_name("Update Image","action");
									echo "<a href='manage_images.php' target='_blank'>Manage Images</a></td></tr>\n";
								echo "</table>\n";
							echo "</td><td width='0'>$thumbnail_display</td></tr>";
						echo "</table>\n";
						} else {	//Display image, if any, anyway.
							echo "$thumbnail_display";
						}
				echo "</td></tr></table><br />\n";


				if ( $answer_list != false )
				{
	
					table1_start("Answer List");

					if ( $QuestionType == 1 ) 
					{	//Short Answer
						//Make Sure we remember the MC questions in case we change our collective minds
						echo form_hidden("num_answers",$numAnswers);
						echo form_hidden("rCorrect",get_post("rCorrect"));

						table1_header(Array("Answer (Grader's hidden notes)/100%","",));

						if (($newData_FLAG || ($shortpost = get_post("short")) == "") && $row_question['Type'] == 1) { $shortpost = $answer_list[0]['Answer']; } else { $shortpost = get_post("short"); }
						if ( !$lock_FLAG ) { $Question_Question_proc = "<textarea cols='80' maxlength='1000' rows='8' maxlength='1000' name='short' >$shortpost</textarea>"; }
						else { $Question_Question_proc = "<textarea cols='80' rows='8' name='short' maxlength='1000' disabled='disabled'>$shortpost</textarea>"; }				
	
						$row_array = Array($Question_Question_proc,"");
						table1_row($row_array,0);
	
					} else { //Multiple Choice
	
						table1_header(Array("Index/0","Correct/0","Answer/100%"));
						$ind = "A";
						for($i = 0; $i < $numAnswers || $i < 2; $i++)
						{
	
							//Correct Radiobox
							$Question_Correct_proc = "<input type='radio' name='radio' value='$i'";
							if ( $i == $radioINDEX ) { $Question_Correct_proc .= " checked='checked'"; }
							if ( $lock_FLAG ) { $Question_Correct_proc .= " disabled='disabled'"; }
							$Question_Correct_proc .= "/>";
						
							//Automatically guess true/false question
							if ($newData_FLAG || ($Answer = get_post("A$i")) == "" ) { $Answer = $answer_list[$i]['Answer']; }

							if ( ($i == 0 || $i == 1) && $numAnswers == 2 && !$lock_FLAG )
							{	//Preset True/False if appropriate
								if ( $i == 0 ) { $truefalse = "True"; } else { $truefalse = "False"; }
								if ( get_post("A0") == "" && get_post("A1") == "" && $answer_list[$i]['Correct'] == 0 && $answer_list[$i]['Correct'] == 0) { 
									$Question_Question_proc = "<input type='text' size='70' name='A$i' value=\"$truefalse\"/>";
								}
								else {
									$Question_Question_proc = "<input type='text' size='70' name='A$i' value=\"$Answer\"/>";
								}
							}
							else {
								if ( !$lock_FLAG ) { $Question_Question_proc = "<input type='text' size='70' name='A$i' value=\"$Answer\"/>"; }
								else { $Question_Question_proc = "<input type='text' size='70' name='A$i' disabled='disabled' value=\"$Answer\"/>"; }
							}						
	
							$row_array = Array($ind, $Question_Correct_proc, $Question_Question_proc);
							table1_row($row_array,$i);
	
							$ind++;
						}

						echo form_hidden("rCorrect",$radioINDEX);
					}
					table1_end(); 

					Delimit();

					//13.0 Show test results for this question, if any.
					if ( $lock_FLAG )
					{
						//Total statistics
							//Get total correct (from all)
							$query = "
								SELECT Count(*)
								FROM `Question` , `Answer` , `Account_answer`
								WHERE 
									`Question`.`ID` = `Account_answer`.`QuestionID`
									AND (
										(
											`Question`.`Type` = '0'
											AND `Answer`.`Correct` = '1'
											AND `Account_answer`.`Selected` = `Answer`.`Index`
										)
									OR (
											`Question`.`Type` = '1'
											AND `Account_answer`.`Correct` = '1'
										)
									)
									AND `Account_answer`.`Corrected` = '1'
									AND `Question`.`ID` = '$QuestionID'
									AND `Account_answer`.`QuestionID` = '$QuestionID'
									AND `Answer`.`QuestionID` = `Question`.`ID`
									AND `Answer`.`QuestionID` = '$QuestionID'
							";
							$statresults = mysql_get($query);
							if ( !$statresults ) { Alert("Uh oh!",$query); }
							$statrow = mysql_fetch_row($statresults);
							$num_correct = $statrow[0];

							//Get total answers
							$query = "
 								SELECT Count(*) 
								FROM `Account_answer`,`Test_taken`,`Question` 
								WHERE 
									`Account_answer`.`QuestionID`='$QuestionID' AND 
									`Account_answer`.`Corrected`='1' AND 
									`Test_taken`.`ID`=`Account_answer`.`TestTID` AND 
									`Question`.`ID`=`Account_answer`.`QuestionID` AND 
									`Question`.`TestID`=`Test_taken`.`TestID` AND
									`Test_taken`.`TestID`='$currentTestID'
							";

							$statresults = mysql_get($query);
							if ( !$statresults) { Alert("Uh oh!",$query); }
							$statrow = mysql_fetch_row($statresults);
							$num_total = $statrow[0];
				
								//Display statistics
								if ( $num_total > 0 )
								{
									$perc_corr = round(100*$num_correct/$num_total);
									$perc_incorr = 100-$perc_corr;
									echo "<br /><table class='data'>\n";
									echo "<tr><td class='data_bold'>Percent Correct:</td><td>$perc_corr% ($num_correct/$num_total)</td>\n";
									echo "<tr><td class='data_bold'>Percent Incorrect:</td><td><font color='red'>$perc_incorr%</font></td>\n";
									echo "</table><br />\n";
								}					

						Delimit();

							//
							//Get test data
		
								$TestTID = get_post("TestTID");
								$query = "SELECT * FROM `Question` WHERE `ID`='$QuestionID'";
								$result = mysql_get($query);
								$row = mysql_fetch_array($result);
								if ( $row != false )
								{
									$TestID = $row['TestID'];
		
									//Get the Questions
									$PAGE = get_page("question");
									$numAnswered = sqlTest_numAnsweredByQuestionTTaken($QuestionID,$TestID);
									$query = "
											SELECT * 
											FROM `Account_answer`
											WHERE `QuestionID`='$QuestionID'
											ORDER BY `AccountID` DESC,`TestTID` DESC,`Corrected` DESC
											LIMIT ".($PAGE*$numPerPage).",".($numPerPage)."
											";

									$result2 = mysql_get($query);
									$row_count = 0;
									$lastindex = -1;	//To keep track of change of question

									echo "<a name='page'></a>\n";
									table1_start("Answers Given (From all test instances) - Page ".($PAGE+1)." of ".cap(ceil($numAnswered/$numPerPage),1,100000));
									table1_header(Array("Mark/0","<font color='red'>*</font>Account[ID]/0","Answer Given/100%","Correct?/0"));

									while( $row2 = mysql_fetch_array($result2) )
									{
											//Keep track of last Account ID versus current
											if ( $lastindex != $row2['AccountID'] ) { $lastindex = $row2['AccountID']; $acc_FLAG = true; } else { $acc_FLAG = false; }
		
											//Correct it
											//Look up correct answer (If MC question)
											$query = "SELECT * FROM `Answer` WHERE `QuestionID`='$QuestionID' AND `Index`='".$row2['Selected']."'";
											$result3 = mysql_get($query);
											$row3 = mysql_fetch_array($result3);
		
											//Look up Account Data
											$query = "SELECT * FROM `Account` WHERE `ID`='".$row2['AccountID']."'";
											$result = mysql_get($query);
											$info = mysql_fetch_array($result);
		
											if ( $row3['Correct']=='0' ) { $MCcorrect = false; } else { $MCcorrect = true; }
		
										if ( ($row['Type']=='1' && $row2['Corrected']=='1' && $row2['Correct']=='1') || ($row['Type']=='0' && $MCcorrect ) ) { $correct = "Yes"; } else { $correct = "<font color='red'>No</font>"; }
										if ( $row2['Corrected']=='0' && $row['Type']=='1' ) {
											$gradeitform = "<form action='#grade' method='POST'>".form_hidden("AID",$row2['ID']).form_hidden("QuestionID",$QuestionID).form_hidden("TestID",$TestID).form_hidden("edit_carry","yes").form_submit_name("Correct","grade_action").form_submit_name("INcorrect","grade_action")."</form>";
			
											$correct = "<font color='#999999'>Pending</font>";
										}
											
										if ( $row['Type'] == '0' ) { $answer = $row3['Answer']; } else { $answer = $row2['Answer']; }
										if ( $acc_FLAG ) { $account = "<a href=\"volunteer_record.php?target=".$info['Username']."\">".str_replace(" ","&nbsp;",$info['Name'])."</a>[".$info['Username']."]"; } else { $account = "..."; }
		
										$line = Array($gradeitform,$account,newlines($answer),$correct);
										table1_row($line,$row_count);

										if ( $row['Type'] == '0' )
										{
											$row_count++;
										} else {	//Add a row space if short answer question
											table1_row(Array("&nbsp;","","",""),$row_count+1);
											$row_count+=2;
										}
									}
									
								}
			
							table1_end_total($row_count);
							echo "		<center><font color='#999999' style='font-size:10px;'>* Click on an Account to open its information.</font></center>";

						}
						echo "<br /><a name='grade' ></a>\n";
		
						if ( !$lock_FLAG ) { echo "<br />(<i>Make sure changes are saved before continuing.</i>)<br /><table width='100%'><tr><td>" . form_submit_name("Save Changes","action") . "\n</td></tr></table>\n"; }
						echo "</form>\n";
						if ( !$lock_FLAG ) { echo "<td align='right'><form action='' method='POST' onSubmit=\"return confirm('Are you sure you want to delete this?');\">" . form_hidden("QuestionID","$QuestionID") . form_hidden("TestID","$currentTestID") . "<span class='warning'>" . form_submit_name("Delete Question","action") . "</span></form></td>\n"; }
	
						//Qestion Paging
						page_controls("question",$numPerPage,$numAnswered,form_hidden("TestID",$currentTestID).form_hidden("QuestionID",$QuestionID).form_hidden("action","Change Question"));
					
				}
			}

		delimiter_end();
	
	print_content_to_end();
?>