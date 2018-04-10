<?php
	include "database.php";
	session_start();
	//Sets up a new connection to the database with the specified values
	$con = new mysqli($MY_HOST, $MY_USERNAME, $MY_PASSWORD, $MY_DATABASE);
	if (!$con) {
	    die('Could not connect: ' . mysqli_error($con));
	}
	else
	{
		//Gets the variables from json format to an object which will have the values we need
		$lakeData = json_decode($_GET['lake']);

		//Wonders if there is a user logged in
		$accountID = (isset($_SESSION['AccountID']) ? ($_SESSION['AccountID'] == "" ? 0 : $_SESSION['AccountID']) : 0);

		$sql = "SELECT ID FROM `test` ORDER BY Date DESC LIMIT 1";
		$result = $con->query($sql);

		while ($row = $result->fetch_assoc()) {
		    $last_id = $row['ID'];
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

				if($accountID == 0)
				{
					echo "nolog";
				}
				else
				{
					//Sets variable for what is going to be parsed in SQL
					$sql = "INSERT INTO `Reading`(`ID`, `TestID`, `AccountID`, `Reading`, `Generated`, `Lake_type`, `Attempt`)
					VALUES (null, $testID, '$accountID', $reading, $generated, $lakeType, $attempt)";

					//Executes the query and if true (successful) it will echo back the success message, otherwise it will give the error.
					if($con->query($sql) === TRUE)
					{
						echo "success";
					}
					else {
					    echo "Error: " . $sql . "<br>" . $con->error;
					}
				}
			}
		}
		else
		{
			echo "lake values not set";
		}		
	}

	mysqli_close($con);
?>