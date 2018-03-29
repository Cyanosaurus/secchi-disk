<?php
	include "database.php";
	//Sets up a new connection to the database with the specified values
	$con = new mysqli($MY_HOST, $MY_USERNAME, $MY_PASSWORD, $MY_DATABASE);
	if (!$con) {
	    die('Could not connect: ' . mysqli_error($con));
	}
	else
	{
		//Gets the variables from json format to an object which will have the values we need
		$lakeData = json_decode($_GET['lake']);
		$questionData = json_decode($_GET['question']);
		//Wonders if there is a user logged in, don't know if this will work because we don't have the old code where it would have been defined
		if(isset($_GET['user']))
		{
			$accountID = $_GET['user']->id;
		}
		else
		{
			$accountID = 0;
		}

		$currentDate = date("Y-m-d");

		$sql = "INSERT INTO `Test`(`ID`, `Version`, `Current`, `Date`) VALUES (null, 1, 1,DATE '$currentDate')";

		if ($con->query($sql) === TRUE)
		{
		    $last_id = mysqli_insert_id($con);
		}
		else
		{
		    echo "Error: " . $sql . "<br>" . $con->error;
		}

		if(isset($questionData))
		{
			//For the amount of arrays in the json object loop and put them in the database
			foreach($questionData as $key => $questionDatum)
			{
				if(isset($_GET['user']))
				{
					$accountID = $_GET['user']->id;
				}
				else
				{
					$accountID = 0;
				}
				$questionID = $questionDatum->questionID;
				$answer = $questionDatum->answer;
				$correct = $questionDatum->correct;
				$correctResponse = $questionDatum->correctResponse;

				$selected = function($type)
				{
					switch ($type) {
						case 'A':
							return 0;
							break;
						case 'B':
							return 1;
							break;
						case 'C':
							return 2;
							break;
						case 'D':
							return 3;
							break;
						case 'E':
							return 4;
							break;
						case 'F':
							return 5;
							break;
						
						default:
							return 0;
							break;
					}
				};

				//Sets variable for what is going to be parsed in SQL
				$sql = "INSERT INTO `Answer`(`ID`, `QuestionID`, `Answer`, `Correct`, `Index`)
				VALUES (null, $questionID, '$answer', $correct, $last_id)";

				//Executes the query and if true (successful) it will echo back the success message, otherwise it will give the error.
				if($con->query($sql) === TRUE)
				{
					echo "New record(s) created!";
				}
				else {
				    echo "Error: " . $sql . "<br>" . $con->error;
				}

				$response = $selected($answer);

				$sql = "INSERT INTO `Account_answer`
				(`ID`, `QuestionID`, `AccountID`, `TestTID`, `Selected`, `Answer`, `Correct`, `Attempt`, `Corrected`)
				VALUES (null, $questionID, $accountID, $last_id, $response, '$correctResponse', $correct, 1, 0)";
				if($con->query($sql) === TRUE)
				{
					echo "New record(s) created!";
				}
				else {
				    echo "Error: " . $sql . "<br>" . $con->error;
				}
			}
		}

		//If we were sent here with the values
		if(isset($lakeData))
		{
			//For the amount of arrays in the json object loop and put them in the database
			foreach($lakeData as $key => $lakeDatum)
			{
				//Need to be set from the test id, made when the test is put into the db
				$testID = $last_id;
				//Sets this variable to the measured depth
				$reading = $lakeDatum->measuredDepth;
				//Sets this variable to the generated depth for that simulation
				$generated = $lakeDatum->generatedDepth;
				//Sets this variable to the lake type on a scale of 1-5, look at db for conversion
				$lakeType = $lakeDatum->lakeType;
				//Sets this variable to the attempts performed
				$attempt = $lakeDatum->attemptsUsed;

				//Sets variable for what is going to be parsed in SQL
				$sql = "INSERT INTO `Reading`(`ID`, `TestID`, `AccountID`, `Reading`, `Generated`, `Lake_type`, `Attempt`)
				VALUES (null, $testID, $accountID, $reading, $generated, $lakeType, $attempt)";

				//Executes the query and if true (successful) it will echo back the success message, otherwise it will give the error.
				if($con->query($sql) === TRUE)
				{
					echo "New record(s) created!";
				}
				else {
				    echo "Error: " . $sql . "<br>" . $con->error;
				}
			}
		}
		else
		{
			echo "lake values not set";
		}
		$sql = "INSERT INTO `Test_taken`(`ID`, `TestID`, `AccountID`, `Date`, `Completed`)
		VALUES (null, $last_id, $accountID, DATE '$currentDate', 1)";
		if($con->query($sql) === TRUE)
		{
			echo "New record(s) created!";
		}
		else
		{
		    echo "Error: " . $sql . "<br>" . $con->error;
		}
		
	}

	mysqli_close($con);
?>