<?php

	include_once("libraries.php");


	//Send password email
	$result = sqlTest_getUsers();
	while($row = mysql_fetch_array($result))
	{
		$email = $row['Email'];
		if(strpos("vlmp",$email) == false ) {
			$subject = "Account Password (Test)";
			$website = WEBSITE;

			if ( preg_match('/([a-zA-Z\- ^,]+),\s*(.*)/',$row['Name'],$matches) )
			{
				$name = $matches[2] . " " . $matches[1];
			} else { $name = $row['Name']; }

			$from = "From: VLMP_admin";
			if (!mail($email, $subject, $body, $from)) { $alert_text .= "Error sending email."; }
			echo "Sent $email<br />";

		}
	}
echo "Done.";
?>