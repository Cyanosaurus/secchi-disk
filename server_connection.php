<?php
	$con = new mysqli('localhost','secchi','AsAp4U8u','mainevlm_secchi2');
	if (!$con) {
	    die('Could not connect: ' . mysqli_error($con));
	}
	else
	{	
		$json = json_decode($_GET['val']);
		if(isset($json))
		{
			foreach($json as $key => $lakeData)
			{
				$testID = 0;//Need to be set from the test id, i dont know
				if(isset($_GET['user']))
				{
					$accountID = $_GET['user']->id;
				}
				else
				{
					$accountID = 0;
				}
				$reading = $lakeData->measuredDepth;
				$generated = $lakeData->generatedDepth;
				$lakeType = $lakeData->lakeType;
				$attempt = $lakeData->attemptsUsed;

				$sql = "INSERT INTO `reading`(`ID`, `TestID`, `AccountID`, `Reading`, `Generated`, `Lake_type`, `Attempt`)
				VALUES (null, $testID, $accountID, $reading, $generated, $lakeType, $attempt)";

				if($con->query($sql) === TRUE)
				{
					echo "New record created!";
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

	// $sql="SELECT * FROM user WHERE id = '".$q."'";
	// $result = mysqli_query($con,$sql);

	mysqli_close($con);
?>