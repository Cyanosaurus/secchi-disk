<?php
	//Sets up a new connection to the database with the specified values
	$con = new mysqli('localhost','secchi','AsAp4U8u','mainevlm_secchi2');
	if (!$con) {
	    die('Could not connect: ' . mysqli_error($con));
	}
	else
	{
		//Gets the variables from json format to an object which will have the values we need
		$json = json_decode($_GET['val']);

		//If we were sent here with the values
		if(isset($json))
		{
			//For the amount of arrays in the json object loop and put them in the database
			foreach($json as $key => $lakeData)
			{
				//Need to be set from the test id, made when the test is put into the db
				$testID = 0;

				//Wonders if there is a user logged in, don't know if this will work because we don't have the old code where it would have been defined
				if(isset($_GET['user']))
				{
					$accountID = $_GET['user']->id;
				}
				else
				{
					$accountID = 0;
				}
				//Sets this variable to the measured depth
				$reading = $lakeData->measuredDepth;
				//Sets this variable to the generated depth for that simulation
				$generated = $lakeData->generatedDepth;
				//Sets this variable to the lake type on a scale of 1-5, look at db for conversion
				$lakeType = $lakeData->lakeType;
				//Sets this variable to the attempts performed
				$attempt = $lakeData->attemptsUsed;

				//Sets variable for what is going to be parsed in SQL
				$sql = "INSERT INTO `reading`(`ID`, `TestID`, `AccountID`, `Reading`, `Generated`, `Lake_type`, `Attempt`)
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
			echo "values not set";
		}
	}

	mysqli_close($con);
?>