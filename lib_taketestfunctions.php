<?php
/*
Creator: Jacob Landry
Date(last updated): 5/29/07
Description: Library for take_test.php
Documentation:
-- Get Multiple Choice Answer
    -This retrieves the value to be stored in the database for a multiple choice answer
    -$AnsID is passed to take_test.php from itslef, then sent here for retrieval of the information to be written
    -this returns the index of the answer chosen to be written to the database (probably by take_test.php)
-- Store Answers
    -information to be written to a database is included in this function as arguments
    -this function simply takes all of the arguments and writes them into the database in their proper places
    -works for both multi-choice and short answer
-- Set Hint
    -If Multi-Choice:
        -if not answered ($blank = true) then hint = please answer
    -If Short Answer:
        -if not answered ($blank = true) then hint = hint in database + please answer
    -If Hint available in database, it gets set
    -If Hint unavailable
        -IF Multi-Choice and not first attempt: hint = try again
            -attempts checked to make sure a 2 answer question still gets a hint if it is set(attempt never increments on 2 answer questions)
        -else Short Answer or 2 answer multi-choice($attempt = 0): hint = " "
-- Get Question Type
    -This takes a QuestionID ($QuestionID) and retrieves its QuestionType out of the database
    -The QuestionType is then returned
-- Count Items in a Table
    -This takes the table you want to access, and a column to limit your count
    -It will return the number of items that match the column value you send into it.
--Starts Table (variation2)
    -This builds a table to be used to display data in the test
--Starts Table Row (variation2)
    -Creates the row data, given content array, and row count (establishes even/odd row color)
    -Example: table1_row(Array("Bob","17","He's short."),4);
--Count Occurrences
    -This will count the number of times the question has been asnwered on a certain attempt in a specific test
    -This is used to eliminate saving an answer multiple times because the 'back' button was hit
-- Submit Hidden Information
    -This prepares all of the hidden information that take_test.php needs to send to itself and to disk.php
    -once his is all set, the submit button is pushed in the site that calls this function and the information is passed
*/


//Get Multiple Choice Answer
function get_answer($AnsID)
    {
    $query3 = "Select `Index` FROM `Answer` WHERE `ID` = '$AnsID'";
    $result3 = mysql_get($query3);
    $row = mysql_fetch_array($result3);
    return $Storeans = $row['Index'];
    }

//Store Answers
function store_answer($QuestionID,$AccountID,$TestTID,$Storeans,$Correct,$attempt)
    {
	if ( $TestTID != 0 ) {
    $QuestionType = get_qtype($QuestionID);
    if ($QuestionType == 0)
        {
        $query2 = "UPDATE Account_answer SET Corrected='0' WHERE Corrected='1' AND QuestionID='$QuestionID' AND AccountID='$AccountID' AND TestTID='$TestTID'";
        $query = "INSERT INTO Account_answer (QuestionID, AccountID, TestTID, Selected, Correct, Attempt, Corrected) VALUES('$QuestionID', '$AccountID', '$TestTID', '$Storeans', '$Correct', '$attempt', '1')";
        $result2 = mysql_get($query2)
            or die("Query failed: 1".$query2.mysql_error());
        $result = mysql_get($query)
         or die("Query failed: 2".mysql_error());
        }
    if ($QuestionType == 1)
        {
        $query = "INSERT INTO Account_answer (QuestionID, AccountID, TestTID, Answer, Correct, Attempt) VALUES('$QuestionID', '$AccountID', '$TestTID', '$Storeans', '$Correct', '$attempt')";
        $result = mysql_get($query)
            or die("Query failed: 3".mysql_error());
        }
	}
    }

//Set Hint
function set_hint($CurrentTestID,$QuestionID,$counter,$blank,$QuestionType,$attempt)
    {
	$query = "SELECT * FROM `Question` WHERE `TestID`='$CurrentTestID'";
	$hint2 = mysql_get($query);
    while ($row = mysql_fetch_array($hint2))
        {
        if ($counter == $row['Index'])
		  {
		  $help = $row['Hint'];
		  if ($blank)
		      {
				  if ($attempt > 1)
					{
		          	return $Hint = "<BR><font color='#FFCCCC'>Hint: ".$help."<br><br>Please provide an answer</font>";
					}
				  else
					{
					return $Hint = "<BR><font color='#FFCCCC'>Please provide an answer</font>";
		     		} 
			 }
		  else if ($help != "")
		      {
		      return $Hint = "<BR><font color='#FFCCCC'>Hint: ".$help."</font>";
		      } 
		  else
		      {
		      if ($QuestionType == 0 && $attempt != 0)
		          {
		          return $Hint = "<BR><font color='#FFCCCC'>Try Again</font>";
		          }
		      else
		          {
		          return $Hint = "";
		          }
		      }
		  }
        }
    }	

//Get Question Type
function get_qtype($QuestionID)
    {
    $query = "SELECT `Type` FROM `Question` WHERE `ID`='$QuestionID'";
        $result = mysql_get($query);
        $row = mysql_fetch_array($result);
        $QuestionType = $row['Type'];
        return $QuestionType;
    }
    
//Count items in a Table
function count_up($table,$column,$var)
    {
    $query = "SELECT Count(*) FROM `$table` WHERE `$column`='$var'";
    $result = mysql_get($query);
    $row = mysql_fetch_row($result);
    return $row[0];
    }

//Starts Table(variation2)
function table2_start($title)
{
	echo "\n\n<!-- ================================== TABLE VARIETY 1 =============================== -->\n\n
<table height='100%' width='100%'>
<table width='100%' height='0' border='0' cellpadding='2' cellspacing='0' margin='3'>
<tr><td bgcolor='#648cc9'>
	<!-- Title here -->
	<font color='FFFFFF'>$title</font>
</td></tr>
<tr><td>
<table width='100%' height='100%' border='0' cellpadding='4' cellspacing='0' class='data'>
";
}

//Start Table Row (variation2)
function table2_row($array_row, $rowcount)
{

	//Create column headers
	$count = 0;
	if ( $rowcount % 2 == 1 ) { echo "<tr class='data_altrow'>"; } else { echo "<tr align='left'>"; }
	foreach ( $array_row as $value )
	{
		if ( $count % 2 == 0 ) { echo "<td align='left' class='data_bold'>"; } else { echo "<td align='left'>"; }
		echo $value;
		echo "</td>";
		$count++;
	}
	echo "</tr>\n";
}

//Count Occurrences
function find_attempt($QuestionID,$TestTID,$attempt)
    {
    $query = "SELECT Count(*) FROM `Account_answer` WHERE `QuestionID`='$QuestionID' AND `TestTID`='$TestTID' AND `Attempt`='$attempt'";
    $result = mysql_get($query);
    $row = mysql_fetch_row($result);
    return $row[0];
    }
    
//Submit Hidden Information
function submitinfo($counter,$attempt,$CurrentTestID,$TestTID,$QuestionID,$QuestionType,$count)
    {
    echo '<input type="hidden" name="counter" value='.$counter.'>';
    echo '<input type="hidden" name="attempt" value='.$attempt.'>';
    echo '<input type="hidden" name="TestID" value='.$CurrentTestID.'>';
    echo '<input type="hidden" name="TestTID" value='.$TestTID.'>';
    echo '<input type="hidden" name="QuestionID" value='.$QuestionID.'>';
    echo '<input type="hidden" name="type" value='.$QuestionType.'>';
    echo '<input type="hidden" name="count" value='.$count.'>';
    }
?>
