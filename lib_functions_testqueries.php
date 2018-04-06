<?php /* COMMON FUNCTIONS */

/* File Index

-- Generic
(string) sqlTest_numCorrect($TestID,$TestTID,$AccID)

function sqlTest_numCorrect($TestTID,$TestID,$AccID)
function sqlTest_numUngraded($TestTID,$TestID,$AccID)
function sqlTest_getQuestionFromAll($TestTID,$TestID,$AccID)
function sqlTest_getQuestionFromAllLimit($TestTID,$TestID,$AccID,$ll,$lh)
function sqlTest_getQuestionByTest($TestID)
function sqlTest_getQuestionByID($QID)
function sqlTest_numQuestions($TestTID,$TestID,$AccID)
function sqlTest_numTests()
function sqlTest_numTestsTakenByID($TestID)
function sqlTest_numTestsTakenByAccount($AccID)
function sqlTest_numTestsTakenByAccountTest($TestID,$AccID)
function sqlTest_getTestsTaken($TestTID,$TestID,$AccID)
function sqlTest_getTestTaken($TestID)
function sqlTest_getTestTakenLimit($TestID,$ll,$lh)
function sqlTest_getTestTakenByID($TestTID)
function sqlTest_getTestTakenByIDLimit($TestTID,$ll,$lh)
function sqlTest_getTestTakenByAccount($AccID)
function sqlTest_getAdmins()
function sqlTest_getUsers()
function sqlTest_numUsers()
function sqlTest_numImages()
function sqlTest_getTest($TestID)
function sqlTest_getAccount($AccountID)
function sqlTest_getReadings($TestID,$AccID)
function sqlTest_numReadings($TestID,$AccID)
function isTestLocked($TestID)
function isTestLocked_Taken($TestID)
function sqlTest_getCurrentTest()
function sqlTest_newID($table)
function sqlTest_deepestLake()
function sqlTest_numAnsweredByQuestionTTaken($QuestionID,$TestID)
function sqlTest_numAnsweredByAccount($TestTID,$AccID)
function sqlTest_accountIsActive($AccID)
function sqlTest_isReported($TestID)

*/



//Find out how many questions were correctly answered.
//Given a Test-Taken ID, Test ID, Account Id, returns select statement to count how many (corrected) are correct
function sqlTest_numCorrect($TestTID,$TestID,$AccID)
{
	$result = mysql_get("
	SELECT count(*) 
	FROM `Account_answer`,`Answer`,`Question`,`Test_taken` 
	WHERE 
		`Test_taken`.`AccountID`='$AccID' AND 
		`Test_taken`.`TestID`='$TestID' AND 
		( 
			( 
				`Account_answer`.`Correct`='1' AND 
				`Question`.`Type`='1'
			) OR 
			( 
				`Question`.`Type`='0' AND 
				`Account_answer`.`Selected`=`Answer`.`Index` AND
				`Answer`.`Correct`='1'
			) 
		) AND 
		`Account_answer`.`Corrected`='1' AND 
		`Question`.`TestID`='$TestID' AND 
		`Answer`.`QuestionID`=`Question`.`ID` AND 
		`Answer`.`QuestionID`=`Account_answer`.`QuestionID` AND 
		`Account_answer`.`AccountID`='$AccID' AND
		`Test_taken`.`ID`=`Account_answer`.`TestTID` AND
		`Test_taken`.`ID`='$TestTID' AND
		`Question`.`Enabled`='1'
		");
	$row = mysql_fetch_row($result);
	return $row[0];
						
}


//Given a Test-Taken ID, Test ID, Account Id, returns select statement to count how many (Short-answer) are not corrected
function sqlTest_numUngraded($TestTID,$TestID,$AccID)
{
	$result = mysql_get("
		SELECT Count(*)
		FROM `Account_answer` , `Question` , `Test_taken`
		WHERE `Question`.`Type` = '1'
			AND `Account_answer`.`Corrected` = '0'
			AND `Account_answer`.`QuestionID` = `Question`.`ID`
			AND `Account_answer`.`TestTID` = `Test_Taken`.`ID`
			AND `Test_taken`.`ID` = '$TestTID'
			AND `Account_answer`.`AccountID` = '$AccID'
			AND `Account_answer`.`AccountID` = `Test_taken`.`AccountID` 
		");
	$row = mysql_fetch_row($result);
	return $row[0];
}

								
//Given a Test-Taken ID, Test ID, Account Id, returns select statement to get Questions
//For a test. (Note, $TestID not necesary.  Just keep to pattern with blank)
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
function sqlTest_getQuestionFromAll($TestTID,$TestID,$AccID)
{

	return mysql_get("
		SELECT `Question`.`Question`,`Question`.`Index`,`Account_answer`.`Answer`,`Account_answer`.`Selected`,`Answer`.`Correct`,`Question`.`Type`,`Answer`.`Index`,`Account_answer`.`Corrected`,`Account_answer`.`ID`,`Answer`.`Answer`,`Question`.`Enabled`,`Account_answer`.`Correct`
		FROM `Question`,`Answer`,`Account_answer`,`Test_taken` 
		WHERE 
			`Question`.`ID`=`Answer`.`QuestionID` AND 
			`Question`.`ID`=`Account_answer`.`QuestionID` AND 
			`Account_answer`.`AccountID`='$AccID' AND 
			`Test_taken`.`AccountID`=`Account_answer`.`AccountID` AND
			`Test_taken`.`AccountID`='$AccID' AND
			`Test_taken`.`ID`='$TestTID' AND
			`Account_answer`.`TestTID`=`Test_taken`.`ID` AND
			`Account_answer`.`QuestionID`=`Answer`.`QuestionID` AND
			(
				(
					`Answer`.`Index`=`Account_answer`.`Selected` AND
					`Question`.`Type`='0'
				) OR
				`Question`.`Type`='1'
			)
		ORDER BY `Question`.`Index` ASC,`Account_answer`.`ID` ASC
		");
}

function sqlTest_getQuestionFromAllLimit($TestTID,$TestID,$AccID,$ll,$lh)
{
	$query = "
		SELECT `Question`.`Question`,`Question`.`Index`,`Account_answer`.`Answer`,`Account_answer`.`Selected`,`Answer`.`Correct`,`Question`.`Type`,`Answer`.`Index`,`Account_answer`.`Corrected`,`Account_answer`.`ID`,`Answer`.`Answer`,`Question`.`Enabled`,`Account_answer`.`Correct`
		FROM `Question`,`Answer`,`Account_answer`,`Test_taken` 
		WHERE 
			`Question`.`ID`=`Answer`.`QuestionID` AND 
			`Question`.`ID`=`Account_answer`.`QuestionID` AND 
			`Account_answer`.`AccountID`='$AccID' AND 
			`Test_taken`.`AccountID`=`Account_answer`.`AccountID` AND
			`Test_taken`.`AccountID`='$AccID' AND
			`Test_taken`.`ID`='$TestTID' AND
			`Account_answer`.`TestTID`=`Test_taken`.`ID` AND
			`Account_answer`.`QuestionID`=`Answer`.`QuestionID` AND
			(
				(
					`Answer`.`Index`=`Account_answer`.`Selected` AND
					`Question`.`Type`='0'
				) OR
				`Question`.`Type`='1'
			)
		ORDER BY `Question`.`Index` ASC,`Account_answer`.`ID` ASC
		LIMIT $ll,$lh";
	return mysql_get($query);
}

//Gets all questions for a specific test (enabled or not)
function sqlTest_getQuestionByTest($TestID)
{
	return mysql_get("SELECT * FROM `Question` WHERE `TestID`='$TestID'");
}

//Gets question from an ID (enabled or not)
function sqlTest_getQuestionByID($QID)
{
	return mysql_get("SELECT * FROM `Question` WHERE `ID`='$QID'");
}

//Find out how many questions there were (that are enabled)
//Note, only $TestID is necesary.
function sqlTest_numQuestions($TestTID,$TestID,$AccID)
{
	 $result = mysql_get("SELECT count(*) FROM `Question` WHERE `TestID`='$TestID' AND `Enabled`='1'");
	 $row = mysql_fetch_row($result);
	 return($row[0]);
}

//Find out how many tests have been taken of a particular one (that have been completed)
//Note, only $TestID is necesary.
function sqlTest_numTests()
{
	 $result = mysql_get("SELECT count(*) FROM `Test` WHERE 1");
	 $row = mysql_fetch_row($result);
	 return($row[0]);
}

//Find out how many tests have been taken of a particular one (that have been completed)
//Note, only $TestID is necesary.
function sqlTest_numTestsTakenByID($TestID)
{
	 $result = mysql_get("SELECT count(*) FROM `Test_taken` WHERE `TestID`='$TestID' AND `Completed`='1'");
	 $row = mysql_fetch_row($result);
	 return($row[0]);
}

//Find out how many tests have been taken of a particular one (that have been completed)
//Note, only $TestID is necesary.
function sqlTest_numTestsTakenByAccount($AccID)
{
	 $result = mysql_get("SELECT count(*) FROM `Test_taken` WHERE `AccountID`='$AccID' AND `Completed`='1'");
	 $row = mysql_fetch_row($result);
	 return($row[0]);
}

//Find out how many tests have been taken of a particular one (that have been completed)
//Note, only $TestID is necesary.
function sqlTest_numTestsTakenByAccountTest($TestID,$AccID)
{
	 $result = mysql_get("SELECT count(*) FROM `Test_taken` WHERE `AccountID`='$AccID' AND `TestID`='$TestID' AND `Completed`='1'");
	 $row = mysql_fetch_row($result);
	 return($row[0]);
}


//Find out how many tests have been taken of a particular one (that have been completed)
function sqlTest_getTestsTaken($TestTID,$TestID,$AccID)
{
	 return mysql_get("SELECT * FROM `Test_taken` WHERE `TestID`='$TestID' AND `Completed`='1' AND `AccountID`='$AccID'");
}

//Given a Test ID, gets test_takens from that (non account specific)
function sqlTest_getTestTaken($TestID)
{
	return mysql_get("SELECT * FROM `Test_taken` WHERE `TestID`='$TestID' AND `Completed`='1' ORDER BY `Date` DESC");
}

//Given a Test ID, gets test_takens from that (non account specific)
function sqlTest_getTestTakenLimit($TestID,$ll,$lh)
{
	return mysql_get("SELECT * FROM `Test_taken` WHERE `TestID`='$TestID' AND `Completed`='1' ORDER BY `Date` DESC LIMIT $ll,$lh");
}

//Given a Test ID, gets test_takens from that (non account specific)
function sqlTest_getTestTakenByAccount($AccID)
{
	return mysql_get("SELECT * FROM `Test_taken` WHERE `AccountID`='$AccID' AND `Completed`='1' ORDER BY `Date` DESC");
}

//Given a Test ID, gets test_takens from that (non account specific)
function sqlTest_getTestTakenByAccountLimit($AccID,$ll,$lh)
{
	return mysql_get("SELECT * FROM `Test_taken` WHERE `AccountID`='$AccID' AND `Completed`='1' ORDER BY `Date` DESC LIMIT $ll,$lh");
}

//Given a TestTaken ID, gets test_takens from that
function sqlTest_getTestTakenByID($TestTID)
{
	return mysql_get("SELECT * FROM `Test_taken` WHERE `ID`='$TestTID' ORDER BY `Date` DESC ");
}

//Given a TestTaken ID, gets test_takens from that
function sqlTest_getTestTakenByIDLimit($TestTID,$ll,$lh)
{
	return mysql_get("SELECT * FROM `Test_taken` WHERE `ID`='$TestTID' ORDER BY `Date` DESC LIMIT $ll,$lh");
}

//Get a list of the Admins in the account table
function sqlTest_getAdmins()
{
	return mysql_get("SELECT * FROM `Account` WHERE `Privileges`='a' ORDER BY `Status`,`Username` ASC");
}

//Get a list of the Users(testers) in the account table
function sqlTest_getUsers()
{
	return mysql_get("SELECT * FROM `Account` WHERE `Privileges`='u' ORDER BY `Status`,`Username` ASC");
}

//Get a list of the Users(testers) in the account table
function sqlTest_getTests()
{
	return mysql_get("SELECT * FROM `Test` WHERE 1 ORDER BY `Version` DESC");
}

//Get a list of the Users(testers) in the account table
function sqlTest_getTestsLimit($ll,$lh)
{
	return mysql_get("SELECT * FROM `Test` WHERE 1 ORDER BY `Version` DESC LIMIT $ll,$lh");
}


//Get a list of the Users(testers) in the account table
function sqlTest_numImages()
{
	$result = mysql_get("SELECT Count(*) FROM `Image` WHERE 1");
	$row = mysql_fetch_row($result);
	return $row[0];
}

//Get a list of the Users(testers) in the account table
function sqlTest_getUsersLimit($limlow,$limhigh)
{
	$LIMIT="LIMIT $limlow,$limhigh";
	return mysql_get("SELECT * FROM `Account` WHERE `Privileges`='u' ORDER BY `Status`,`Username` ASC $LIMIT");
}

//Get a list of the Users(testers) in the account table
function sqlTest_numUsers()
{
	$result = mysql_get("SELECT Count(*) FROM `Account` WHERE `Privileges`='u'");
	$row = mysql_fetch_row($result);
	return $row[0];
}

//Get data from a test
function sqlTest_getTest($TestID)
{
	return mysql_get("SELECT * FROM `Test` WHERE `ID`='$TestID'");
}

//Gets a single account data
function sqlTest_getAccount($AccountID)
{
	return mysql_get("SELECT * FROM `Account` WHERE `ID`='$AccountID'");
}

//Gets all secchi readings for a test
/*
Lake_type:
				Lake_type:
				00 - ID
				01 - Name
				02 - Range_low
				03 - Range_high
				04 - Tolerance
				05 - Description
				06 - Color
				Reading:
				07 - Id
				08 - TestID
				09 - AccountId
				10 - Reading
				11 - Generated
				12 - Lake_type
				13 - Attempt
				Test:
				14 - Version
				15 - Current
				16 - Date
*/
function sqlTest_getReadings($TestID,$AccID)
{
	if ( $TestID != "" && $AccID == "" )
	{
		return mysql_get("SELECT * 
					FROM `Lake_type`,`Reading`,`Test`
					WHERE
						`Lake_type`.`ID`=`Reading`.`Lake_type` AND
						`Test`.`ID`=`Reading`.`TestID` AND
						`Reading`.`TestID`='$TestID'
						GROUP BY `Reading`
					ORDER BY `Reading`.`ID` DESC
				");
	} else if ( $TestID == "" && $AccID != "" ) {
		return mysql_get("SELECT *
								FROM `Lake_type`,`Reading`,`Test`
								WHERE
									`Reading`.`AccountID`='$AccID' AND
									`Lake_type`.`ID`=`Reading`.`Lake_type` AND
									`Test`.`ID`=`Reading`.`TestID`
								GROUP BY `Reading`
								ORDER BY `Reading`.`ID` DESC
							");
	} else {
		return mysql_get("SELECT *
								FROM `Lake_type`,`Reading`,`Test`
								WHERE
									`Reading`.`AccountID`='$AccID' AND
									`Lake_type`.`ID`=`Reading`.`Lake_type` AND
									`Test`.`ID`=`Reading`.`TestID` AND
									`Reading`.`TestID`='$TestID'
								GROUP BY `Reading`
								ORDER BY `Reading`.`ID` DESC
							");

	}

}

//Gets all secchi readings for a test
function sqlTest_getReadingsLimit($TestID,$AccID,$ll,$lh)
{
	if ( $TestID != "" && $AccID == "" )
	{
		return mysql_get("SELECT *
					FROM `Lake_type`,`Reading`,`Test`
					WHERE
						`Lake_type`.`ID`=`Reading`.`Lake_type` AND
						`Test`.`ID`=`Reading`.`TestID` AND
						`Reading`.`TestID`='$TestID'
					ORDER BY `Reading`.`ID` DESC LIMIT $ll,$lh
				");
	} else if ( $TestID == "" && $AccID != "" ) {
		return mysql_get("SELECT *
								FROM `Lake_type`,`Reading`,`Test`
								WHERE
									`Reading`.`AccountID`='$AccID' AND
									`Lake_type`.`ID`=`Reading`.`Lake_type` AND
									`Test`.`ID`=`Reading`.`TestID`
								ORDER BY `Reading`.`ID` DESC LIMIT $ll,$lh
							");
	} else {
		return mysql_get("SELECT *
								FROM `Lake_type`,`Reading`,`Test`
								WHERE
									`Reading`.`AccountID`='$AccID' AND
									`Lake_type`.`ID`=`Reading`.`Lake_type` AND
									`Test`.`ID`=`Reading`.`TestID` AND
									`Reading`.`TestID`='$TestID'
								ORDER BY `Reading`.`ID` DESC LIMIT $ll,$lh
							");

	}

}
//Gets all secchi readings for a test
function sqlTest_numReadings($TestID,$AccID)
{
	if ( $TestID != "" && $AccID == "" )
	{
		$result = mysql_get("SELECT Count(*) 
					FROM `Lake_type`,`Reading`,`Test`
					WHERE
						`Lake_type`.`ID`=`Reading`.`Lake_type` AND
						`Test`.`ID`=`Reading`.`TestID` AND
						`Reading`.`TestID`='$TestID'
					ORDER BY `Reading`.`ID` DESC
				");
	} else if ( $TestID == "" && $AccID != "" ) {
		$result = mysql_get("SELECT Count(*)
								FROM `Lake_type`,`Reading`,`Test`
								WHERE
									`Reading`.`AccountID`='$AccID' AND
									`Lake_type`.`ID`=`Reading`.`Lake_type` AND
									`Test`.`ID`=`Reading`.`TestID`
								ORDER BY `Reading`.`ID` DESC
							");
	} else {
		$result = mysql_get("SELECT Count(*)
								FROM `Lake_type`,`Reading`,`Test`
								WHERE
									`Reading`.`AccountID`='$AccID' AND
									`Lake_type`.`ID`=`Reading`.`Lake_type` AND
									`Test`.`ID`=`Reading`.`TestID` AND
									`Reading`.`TestID`='$TestID'
								ORDER BY `Reading`.`ID` DESC
							");

	}

	$row = mysql_fetch_row($result);
	return $row['0'];

}

//Returns true if the test is locked
function isTestLocked($TestID)
{

	//See if any questions for the test have answers
	$query = "SELECT `TestID` FROM `Test_taken` WHERE `TestID`='$TestID'";
	$result_account_answer = mysql_get($query);
	$row = mysql_fetch_array($result_account_answer);
	$lock_FLAG = ($row['TestID'] == $TestID);

	//Check for current flag
	$query = "SELECT `Current` FROM `Test` WHERE `ID`='$TestID'";
	$result = mysql_get($query);
	$row = mysql_fetch_array($result);
	if ( $row['Current'] != 0 ) { $lock_FLAG = true; }

	return $lock_FLAG;
}

//Returns true if the test is locked (only if tests taken)
function isTestLocked_Taken($TestID)
{
	//See if any questions for the test have answers
	$query = "SELECT `TestID` FROM `Test_taken` WHERE `TestID`='$TestID'";
	$result_account_answer = mysql_get($query);
	$row = mysql_fetch_array($result_account_answer);

	return $row['TestID']==$TestID;
}


//returns Id of test that is current Current
function sqlTest_getCurrentTest()
{
	$result = mysql_get("SELECT `ID` FROM `Test` WHERE `Current`='1'");
	$row = mysql_fetch_array($result);
	return $row['ID'];
}

//Given a table name, returns the most recent ID# (if autoincrement/unique)
function sqlTest_newID($table)
{
	//Gah! Rewritten for backward compatibility
	$result = mysql_get("SELECT MAX(`ID`) FROM `$table`;");
	$row = mysql_fetch_array($result);
	return $row[0];
}

//Get the deepest high-range of the deepest lake
function sqlTest_deepestLake()
{
	$result = mysql_get("SELECT MAX(`Range_high`) FROM `Lake_type`");
	$row = mysql_fetch_array($result);
	return $row['Range_high'];
}

//Get how many answeres for a question and test
//**REwritten for backward compatibliity
function sqlTest_numAnsweredByQuestionTTaken($QuestionID,$TestID)
{
	//Gah!
	$result = mysql_get("SELECT `ID` FROM `Test_taken` WHERE `TestID`='$TestID'");
	$list = Array();
	while($row = mysql_fetch_array($result))
	{
		$list[] = $row['ID'];	
	}	

	//$result = mysql_get("SELECT COUNT(*) FROM `Account_answer` WHERE `QuestionID`='$QuestionID' AND `TestTID` IN (SELECT `ID` FROM `Test_taken` WHERE `TestID`='$TestID')");
	$count = 0;
	foreach($row as $key)
	{
		$result = mysql_get("SELECT `ID` FROM `Account_answer` WHERE `QuestionID`='$QuestionID' AND `TestTID`='$key'");
		if ( $row2 = mysql_fetch_array($result) )
		{
			$count++;
		}
	}

	return $count;
}

//Get how many answeres for a test and user
function sqlTest_numAnsweredByAccount($TestTID,$AccID)
{
	$result = mysql_get("SELECT COUNT(*) FROM `Account_answer` WHERE `AccountID`='$AccID' AND `TestTID`='$TestTID'");
	$row = mysql_fetch_row($result);
	return $row[0];

}

//Returns true if the account is currently active.
function sqlTest_accountIsActive($AccID)
{
	$result = mysql_get("SELECT `Status` FROM `Account` WHERE `AccountID`='$AccID'");
	$row = mysql_fetch_row($result);
	return $row[0]=='a';
}

//Returns true if the given test has been reported on
function sqlTest_isReported($TestID)
{
	$query = "SELECT `TestID` FROM `Report_history` WHERE `TestID`='$TestID'";

	$result = mysql_get($query);
	$row = mysql_fetch_array($result);
	return ($row['TestID']==$TestID);
}
